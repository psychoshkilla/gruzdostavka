<?php
require_once dirname(__DIR__) . '/backend/bootstrap.php';
$errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
$old = $_SESSION['old'] ?? []; unset($_SESSION['old']);
$success = flash_get('success');
include __DIR__ . '/partials/header.php';
?>
<section class="card narrow">
    <h1>Авторизация</h1>
    <?php if ($success): ?><div class="flash success"><?= e($success) ?></div><?php endif; ?>
    <?php if (!empty($errors['login'])): ?><div class="flash error"><?= e($errors['login']) ?></div><?php endif; ?>
    <form method="post" action="/gruz/backend/actions/login.php">
        <label>Логин
            <input name="login" value="<?= e($old['login'] ?? '') ?>" required>
        </label>
        <label>Пароль
            <input type="password" name="password" required>
        </label>
        <button class="btn" type="submit">Войти</button>
    </form>
    <p class="muted">Ещё нет аккаунта? <a href="/gruz/frontend/register.php">Зарегистрироваться</a></p>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>