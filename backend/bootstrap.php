<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('FRONTEND_PATH', ROOT_PATH . '/frontend');
define('BACKEND_PATH', ROOT_PATH . '/backend');

$config = require BACKEND_PATH . '/config.php';

require_once BACKEND_PATH . '/classes/Database.php';
require_once BACKEND_PATH . '/classes/Validator.php';
require_once BACKEND_PATH . '/classes/Auth.php';
require_once BACKEND_PATH . '/classes/User.php';
require_once BACKEND_PATH . '/classes/Application.php';
require_once BACKEND_PATH . '/classes/Review.php';

Database::init($config['db']);

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function flash_set(string $key, string $msg): void {
    $_SESSION['flash'][$key] = $msg;
}

function flash_get(string $key): ?string {
    if (!empty($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}