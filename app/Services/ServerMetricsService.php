<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ServerMetricsService
{
    protected string $baseUrl = 'http://127.0.0.1:19999';

    public function available(): bool
    {
        try {
            $res = Http::timeout(2)->get("{$this->baseUrl}/api/v1/info");
            return $res->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getCores(): int
    {
        $cpuinfo = @file('/proc/cpuinfo');
        $count = 0;
        foreach ($cpuinfo ?: [] as $line) {
            if (str_starts_with($line, 'processor')) {
                $count++;
            }
        }
        return max($count, 1);
    }

    public function current(): array
    {
        $cores = $this->getCores();

        $ram = $this->lastPoint('system.ram') ?: [];
        $cpu = $this->lastPoint('system.cpu') ?: [];
        $load = $this->lastPoint('system.load') ?: [];
        $uptime = $this->lastPoint('system.uptime') ?: [];

        $ramTotal = array_sum(array_diff_key($ram, ['time' => true]));
        $ramUsed = $ram['used'] ?? 0;

        $busy = 0.0;
        foreach ($cpu as $k => $v) {
            if ($k === 'time' || $k === 'idle') {
                continue;
            }
            $busy += is_numeric($v) ? (float) $v : 0;
        }

        return [
            'cpu_pct' => round($cores > 0 ? $busy / $cores : 0, 1),
            'cpu_raw_pct' => round($busy, 1),
            'ram_used_mib' => round($ramUsed, 0),
            'ram_total_mib' => round($ramTotal, 0),
            'ram_pct' => $ramTotal > 0 ? round(($ramUsed / $ramTotal) * 100, 1) : 0,
            'load1' => $load['load1'] ?? null,
            'load5' => $load['load5'] ?? null,
            'load15' => $load['load15'] ?? null,
            'uptime' => $uptime['uptime'] ?? null,
            'cores' => $cores,
        ];
    }

    public function peak(int $seconds = 604800): array
    {
        $cores = $this->getCores();

        $ramPeak = null;
        $cpuPeak = null;
        $usedWindow = null;

        foreach ([$seconds, 86400, 21600, 3600, 600] as $window) {
            $ramPeak = $this->maxSeries('system.ram', 'used', $window);
            $cpuPeak = $this->maxCpuSeries($window);
            if ($ramPeak !== null || $cpuPeak !== null) {
                $usedWindow = $window;
                break;
            }
        }

        return [
            'ram_used_mib' => $ramPeak,
            'cpu_pct' => $cores > 0 && $cpuPeak !== null ? round($cpuPeak / $cores, 1) : null,
            'cpu_raw_pct' => $cpuPeak !== null ? round($cpuPeak, 1) : null,
            'window_seconds' => $usedWindow,
        ];
    }

    public function series(int $seconds = 86400, int $points = 48): array
    {
        $ram = $this->data('system.ram', $seconds, $points, 'average');
        $cpu = $this->data('system.cpu', $seconds, $points, 'average');
        $cores = $this->getCores();

        $ramData = $ram['data'] ?? [];
        $cpuData = $cpu['data'] ?? [];
        $ramLabels = $ram['labels'] ?? [];
        $cpuLabels = $cpu['labels'] ?? [];

        $ramUsedIdx = array_search('used', $ramLabels, true);
        $cpuBusyIdx = [];
        foreach ($cpuLabels as $i => $label) {
            if ($label !== 'time' && $label !== 'idle') {
                $cpuBusyIdx[] = $i;
            }
        }

        $points = [];
        $count = max(count($ramData), count($cpuData));
        for ($i = 0; $i < $count; $i++) {
            $time = $ramData[$i][0] ?? $cpuData[$i][0] ?? null;
            $ramVal = ($ramUsedIdx !== false && isset($ramData[$i][$ramUsedIdx])) ? $ramData[$i][$ramUsedIdx] : null;
            $cpuVal = null;
            if (isset($cpuData[$i])) {
                $busy = 0.0;
                foreach ($cpuBusyIdx as $idx) {
                    if (isset($cpuData[$i][$idx]) && is_numeric($cpuData[$i][$idx])) {
                        $busy += (float) $cpuData[$i][$idx];
                    }
                }
                $cpuVal = $cores > 0 ? $busy / $cores : 0;
            }
            if ($ramVal !== null || $cpuVal !== null) {
                $points[] = [
                    'time' => (int) $time,
                    'ram' => $ramVal !== null ? round((float) $ramVal, 1) : null,
                    'cpu' => $cpuVal !== null ? round($cpuVal, 1) : null,
                ];
            }
        }

        return $points;
    }

    protected function lastPoint(string $chart): ?array
    {
        $data = $this->data($chart, 60, 1, 'average');
        $row = $data['data'][0] ?? null;
        $labels = $data['labels'] ?? [];
        if (!$row || !$labels) {
            return null;
        }
        return array_combine($labels, $row);
    }

    protected function maxSeries(string $chart, string $dimension, int $seconds): ?float
    {
        $data = $this->data($chart, $seconds, 100, 'max');
        $idx = array_search($dimension, $data['labels'] ?? [], true);
        if ($idx === false) {
            return null;
        }
        $max = null;
        foreach ($data['data'] ?? [] as $row) {
            if (isset($row[$idx]) && is_numeric($row[$idx])) {
                $max = max($max ?? -INF, (float) $row[$idx]);
            }
        }
        return $max;
    }

    protected function maxCpuSeries(int $seconds): ?float
    {
        $data = $this->data('system.cpu', $seconds, 100, 'max');
        $idx = [];
        foreach ($data['labels'] ?? [] as $i => $label) {
            if ($label !== 'time' && $label !== 'idle') {
                $idx[] = $i;
            }
        }
        $max = null;
        foreach ($data['data'] ?? [] as $row) {
            $busy = 0.0;
            $hasValue = false;
            foreach ($idx as $i) {
                if (isset($row[$i]) && is_numeric($row[$i])) {
                    $busy += (float) $row[$i];
                    $hasValue = true;
                }
            }
            if ($hasValue) {
                $max = max($max ?? -INF, $busy);
            }
        }
        return $max;
    }

    protected function data(string $chart, int $seconds, int $points, string $group = 'average'): array
    {
        try {
            $res = Http::timeout(5)->get("{$this->baseUrl}/api/v1/data", [
                'chart' => $chart,
                'after' => -$seconds,
                'points' => $points,
                'group' => $group,
                'format' => 'json',
            ]);
            return $res->successful() ? $res->json() : [];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
