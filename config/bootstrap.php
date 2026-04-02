<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (
    (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV']))
    && file_exists(dirname(__DIR__).'/.env')
) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}