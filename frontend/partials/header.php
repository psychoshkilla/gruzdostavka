<?php
if (!function_exists('e')) { require_once dirname(__DIR__, 2) . '/backend/bootstrap.php'; }
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ГрузДоставка</title>
<link rel="stylesheet" href="/gruz/frontend/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <a href="/gruz/index.php" class="brand">🚚 ГрузДоставка</a>
    <nav>
        <?php if ($currentUser): ?>
            <a href="/gruz/frontend/applications.php">Мои заявки</a>
            <a href="/gruz/frontend/application_create.php">+ Заявка</a>
            <?php if ($currentUser['role'] === 'admin'): ?>
                <a href="/gruz/frontend/admin.php">Админ</a>
            <?php endif; ?>
            <a href="/gruz/backend/actions/logout.php" class="btn-ghost">Выйти</a>
        <?php else: ?>
            <a href="/gruz/frontend/login.php">Войти</a>
            <a href="/gruz/frontend/register.php" class="btn">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">