<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/kriteria', 'GET', ['tab' => 5]);
$controller = new App\Http\Controllers\KriteriaController();
$response = $controller->index($request);

echo $response->render();
