<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BurialPermit;

$permits = BurialPermit::with('deceased')->get();
foreach ($permits as $p) {
    if ($p->deceased && $p->deceased->first_name === 'asd') {
        $d = $p->deceased;
        printf("Permit:%s | F:%s | L:%s | M:%s | E:%s | Created:%s\n", 
            $p->permit_number, 
            $d->first_name, 
            $d->last_name, 
            $d->middle_name ?? 'NULL', 
            $d->name_extension ?? 'NULL', 
            $d->created_at->toDateTimeString()
        );
    }
}
