<?php

require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";

$controller = new \Modules\SENAAPICOLA\Http\Controllers\ModuleController();

$response = $controller->reportView("monitorings");
$content = $response->getContent();

file_put_contents(__DIR__ . "/storage_test_monitorings.pdf", $content);

echo (str_starts_with($content, "%PDF") ? "pdf-ok" : "pdf-bad") . PHP_EOL;
echo "bytes=" . strlen($content) . PHP_EOL;

