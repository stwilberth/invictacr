<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$path = dirname(__DIR__, 1) . '/invictacostarica/firebase-export/clients.json';
$json = json_decode(file_get_contents($path), true);

if (!$json) { echo "Error reading JSON\n"; exit(1); }

$count = 0;
foreach ($json as $item) {
    App\Models\Client::updateOrCreate(
        ['email' => $item['email'] ?? 'no-email-' . $item['id']],
        [
            'name' => $item['name'] ?? $item['nombre'] ?? 'Cliente',
            'phone' => $item['phone'] ?? $item['telefono'] ?? null,
            'notes' => $item['notes'] ?? null,
        ]
    );
    $count++;
}
echo "Clientes sincronizados: $count\n";
echo "Total en DB: " . App\Models\Client::count() . "\n";
