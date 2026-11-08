<?php
require_once dirname(__DIR__) . '/backend/bootstrap.php';
$errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
$old = $_SESSION['old'] ?? []; unset($_SESSION['old']);
$success = flash_get('success');
include __DIR__ . '/partials/header.php';
?>
<section class="card narrow">
    <h1>Регистрация</h1>
    <?php if ($success): ?><div class="flash success"><?= e($success) ?></div><?php endif; ?>
    <form method="post" action="/gruz/backend/actions/register.php" novalidate>
        <label>Логин
            <input name="login" value="<?= e($old['login'] ?? '') ?>" required>
            <?php if (!empty($errors['login'])): ?><span class="err"><?= e($errors['login']) ?></span><?php endif; ?>
        </label>
        <label>Пароль
            <input type="password" name="password" required>
            <?php if (!empty($errors['password'])): ?><span class="err"><?= e($errors['password']) ?></span><?php endif; ?>
        </label>
        <label>ФИО
            <input name="full_name" value="<?= e($old['fullName'] ?? '') ?>" required>
            <?php if (!empty($errors['full_name'])): ?><span class="err"><?= e($errors['full_name']) ?></span><?php endif; ?>
        </label>
        <label>Телефон
            <input name="phone" value="<?= e($old['phone'] ?? '') ?>" placeholder="+7(999)-123-45-67" required>
            <?php if (!empty($errors['phone'])): ?><span class="err"><?= e($errors['phone']) ?></span><?php endif; ?>
        </label>
        <label>Email
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
            <?php if (!empty($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
        </label>
        <button class="btn" type="submit">Создать аккаунт</button>
    </form>
    <p class="muted">Уже есть аккаунт? <a href="/gruz/frontend/login.php">Войти</a></p>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>