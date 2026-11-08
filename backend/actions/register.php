<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../frontend/register.php');

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$fullName = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

$errors = [];
if ($e = Validator::login($login)) $errors['login'] = $e;
if ($e = Validator::password($password)) $errors['password'] = $e;
if ($e = Validator::fullName($fullName)) $errors['full_name'] = $e;
if ($e = Validator::phone($phone)) $errors['phone'] = $e;
if ($e = Validator::email($email)) $errors['email'] = $e;

if (User::findByLogin($login)) $errors['login'] = 'Такой логин уже занят';

$_SESSION['old'] = compact('login', 'fullName', 'phone', 'email');

if ($errors) {
    $_SESSION['errors'] = $errors;
    redirect('../frontend/register.php');
}

User::create([
    'login' => $login,
    'password' => $password,
    'full_name' => $fullName,
    'phone' => $phone,
    'email' => $email,
]);

unset($_SESSION['old']);
flash_set('success', 'Аккаунт создан! Войдите.');
redirect('/gruz/frontend/login.php');