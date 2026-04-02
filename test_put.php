<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/niche-grids/1', 'PUT', [
    'cells' => ['C-1' => ['status' => 'occupied']]
]);
$request->headers->set('Accept', 'application/json');

$response = app()->handle($request);
echo "Status: " . $response->status() . "\n";
echo "Content: " . $response->content() . "\n";
