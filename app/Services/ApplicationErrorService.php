<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ApplicationErrorService
{
    public function getLast24hErrors(): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return ['count' => 0, 'critical_count' => 0, 'recent' => []];
        }

        $cutoff = now()->subHours(24)->timestamp;
        $errorPatterns = [
            '500' => '/\b500\b/',
            'payment' => '/payment|pasarela|pago|gateway|stripe|paypal|card.declined|insufficient_funds/i',
            'exception' => '/Exception|Error|Fatal|CRITICAL|ALERT|EMERGENCY/i',
        ];

        $count = 0;
        $criticalCount = 0;
        $recent = [];

        $handle = @fopen($logPath, 'r');
        if (!$handle) {
            return ['count' => 0, 'critical_count' => 0, 'recent' => []];
        }

        $buffer = '';
        while (($line = fgets($handle)) !== false) {
            $buffer .= $line;

            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $line, $m)) {
                $ts = strtotime($m[1]);
                if ($ts === false || $ts < $cutoff) {
                    if ($ts < $cutoff) {
                        $buffer = $line;
                    }
                    continue;
                }

                $isError = false;
                $isCritical = false;
                foreach ($errorPatterns as $type => $pattern) {
                    if (preg_match($pattern, $line)) {
                        $isError = true;
                        if (in_array($type, ['payment', 'exception'], true)) {
                            $isCritical = true;
                        }
                        break;
                    }
                }
                if (preg_match('/CRITICAL|ALERT|EMERGENCY/i', $line)) {
                    $isCritical = true;
                    $isError = true;
                }

                if ($isError) {
                    $count++;
                    if ($isCritical) {
                        $criticalCount++;
                    }
                    if (count($recent) < 10) {
                        $recent[] = [
                            'time' => $m[1],
                            'message' => mb_substr(trim($line), 0, 200),
                            'is_critical' => $isCritical,
                        ];
                    }
                }
            }
        }

        fclose($handle);

        return [
            'count' => $count,
            'critical_count' => $criticalCount,
            'recent' => $recent,
        ];
    }
}