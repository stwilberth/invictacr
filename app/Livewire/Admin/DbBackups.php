<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Livewire\Component;

class DbBackups extends Component
{
    public $backups = [];
    public $creating = false;
    public $status = '';

    public function mount()
    {
        $this->loadBackups();
    }

    public function loadBackups()
    {
        $backupDir = storage_path('backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = File::files($backupDir);
        $this->backups = collect($files)
            ->filter(fn ($f) => in_array($f->getExtension(), ['sql', 'gz']))
            ->map(fn ($f) => [
                'name' => $f->getFilename(),
                'path' => $f->getPathname(),
                'size' => $f->getSize(),
                'date' => $f->getMTime(),
            ])
            ->sortByDesc('date')
            ->values()
            ->toArray();
    }

    public function createBackup()
    {
        $this->creating = true;
        $this->status = 'Creando backup...';

        $backupDir = storage_path('backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'invictacr_' . now()->format('Y-m-d_His') . '.sql';
        $filepath = $backupDir . '/' . $filename;

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database', 'invictacr');
        $username = config('database.connections.mysql.username', 'root');
        $password = config('database.connections.mysql.password', '');

        $cmd = sprintf(
            'mysqldump -h %s -P %s -u %s %s %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $password ? '-p' . escapeshellarg($password) : '',
            escapeshellarg($database)
        );

        $output = shell_exec($cmd . " > " . escapeshellarg($filepath) . " 2>&1");

        if (File::exists($filepath) && File::size($filepath) > 0) {
            $this->status = "Backup creado: {$filename} (" . $this->formatSize(File::size($filepath)) . ")";
        } else {
            $this->status = 'Error al crear el backup.';
            if (File::exists($filepath)) {
                $content = File::get($filepath);
                if (!empty(trim($content))) {
                    $this->status .= ' Detalle: ' . substr($content, 0, 200);
                }
                File::delete($filepath);
            }
        }

        $this->creating = false;
        $this->loadBackups();
    }

    public function downloadBackup($name)
    {
        $backupDir = storage_path('backups');
        $filepath = $backupDir . '/' . basename($name);

        if (!File::exists($filepath)) {
            return;
        }

        return response()->download($filepath, basename($name), [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function deleteBackup($name)
    {
        $backupDir = storage_path('backups');
        $filepath = $backupDir . '/' . basename($name);

        if (File::exists($filepath)) {
            File::delete($filepath);
            $this->status = "Backup eliminado: {$name}";
        }

        $this->loadBackups();
    }

    public function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function render()
    {
        return view('livewire.admin.db-backups')
            ->layout('components.admin-layout', ['title' => 'Backups de Base de Datos']);
    }
}
