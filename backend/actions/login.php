<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../frontend/login.php');

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$user = User::findByLogin($login);

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['errors'] = ['login' => 'Неверный логин или пароль'];
    $_SESSION['old'] = compact('login');
    redirect('../frontend/login.php');
}

Auth::login((int)$user['id']);
if ($user['role'] === 'admin') redirect('/gruz/frontend/admin.php');
redirect('/gruz/frontend/applications.php');