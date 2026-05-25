<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = [
    'id_proof', 'driving_licence', 'vehicle_plate', 'insurance', 'vehicle_rc',
    'fitness_certificate', 'tax_receipt', 'registration_slip', 'tourist_permit',
    'driving_licence_tr', 'puc'
];

foreach ($columns as $col) {
    \Illuminate\Support\Facades\DB::update("UPDATE verify_id SET {$col} = REPLACE({$col}, 'public/', '') WHERE {$col} LIKE 'public/%'");
}

echo "Done.\n";
