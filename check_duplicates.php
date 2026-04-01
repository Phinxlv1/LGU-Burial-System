<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeceasedPerson;

$ds = DeceasedPerson::where('first_name', 'asd')->where('last_name', 'asd')->get();
foreach ($ds as $d) {
    echo "ID:{$d->id} | F:{$d->first_name} | L:{$d->last_name} | M:[" . ($d->middle_name ?? 'NULL') . "] | E:[" . ($d->name_extension ?? 'NULL') . "] | Created:{$d->created_at}\n";
}
