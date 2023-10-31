<?php

include __DIR__ . '/../../vendor/autoload.php';

echo \Firebase\JWT\JWT::encode(['exp' => time() + 100], file_get_contents(__DIR__ . '/private_key.pem'), 'RS256') . PHP_EOL;