<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$permits = App\Models\BurialPermit::all();

foreach ($permits as $p) {
    if (in_array($p->status, ['pending', 'approved']) && !$p->expiry_date) {
        echo $p->permit_number . " => SKIPPED (pending)\n";
        continue;
    }
    if ($p->status === 'expired') {
        echo $p->permit_number . " => SKIPPED (expired)\n";
        continue;
    }
    if (($p->renewal_count ?? 0) > 0) {
        echo $p->permit_number . " => SKIPPED (renewed)\n";
        continue;
    }
    $base = $p->issued_date ? Carbon\Carbon::parse($p->issued_date) : Carbon\Carbon::parse($p->created_at);
    $newExpiry = $base->copy()->addYears(5);
    $p->updateQuietly(['expiry_date' => $newExpiry]);
    echo $p->permit_number . " => fixed to: " . $newExpiry->toDateString() . "\n";
}
echo "\nDone!\n";