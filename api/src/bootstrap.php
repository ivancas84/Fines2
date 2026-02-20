<?php

// bootstrap.php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';  // your original config

header('Content-Type: application/json; charset=utf-8');

// Allow CORS – change origins in production!
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

function send_json($data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function error_json(string $msg, int $status = 400): never
{
    send_json(['error' => $msg], $status);
}

// Shared db & dataProvider
$db = \App\Context::getFinesDb();
$dataProvider = $db->CreateDataProvider();