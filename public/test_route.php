<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// This is a hacky way to run a route in a script
$request = Illuminate\Http\Request::create('/distribution', 'GET');
$response = $app->handle($request);

echo $response->getContent();
