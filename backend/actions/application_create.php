<?php
require_once dirname(__DIR__) . '/bootstrap.php';
$user = Auth::requireUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/gruz/frontend/application_create.php');

$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');
$weight = trim($_POST['weight'] ?? '');
$dims = trim($_POST['dimensions'] ?? '');
$from = trim($_POST['address_from'] ?? '');
$to = trim($_POST['address_to'] ?? '');
$cargo = $_POST['cargo_type'] ?? '';

$errors = [];
if ($e = Validator::date($date)) $errors['date'] = $e;
if ($e = Validator::time($time)) $errors['time'] = $e;
if ($e = Validator::weight($weight)) $errors['weight'] = $e;
if ($dims === '') $errors['dimensions'] = 'Укажите габариты';
if ($from === '') $errors['address_from'] = 'Укажите адрес отправления';
if ($to === '') $errors['address_to'] = 'Укажите адрес доставки';
if (!in_array($cargo, Application::CARGO_TYPES, true)) $errors['cargo_type'] = 'Выберите тип груза';

$_SESSION['old'] = compact('date', 'time', 'weight', 'dims', 'from', 'to', 'cargo');
if ($errors) {
    $_SESSION['errors'] = $errors;
    redirect('/gruz/frontend/application_create.php');
}

[$dd, $mm, $yy] = explode('.', $date);
Application::create((int)$user['id'], [
    'date' => "$yy-$mm-$dd",
    'time' => $time,
    'weight' => (int)$weight,
    'dimensions' => $dims,
    'address_from' => $from,
    'address_to' => $to,
    'cargo_type' => $cargo,
]);

unset($_SESSION['old']);
flash_set('success', 'Заявка отправлена!');
redirect('/gruz/frontend/applications.php');