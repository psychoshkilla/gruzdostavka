<?php
require_once dirname(__DIR__) . '/backend/bootstrap.php';
Auth::requireUser();
$errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
$old = $_SESSION['old'] ?? []; unset($_SESSION['old']);
include __DIR__ . '/partials/header.php';
?>
<section class="card">
    <h1>Новая заявка</h1>
    <form method="post" action="/gruz/backend/actions/application_create.php" novalidate>
        <label>Дата (ДД.ММ.ГГГГ)
            <input name="date" id="dateField" placeholder="25.12.2026" value="<?= e($old['date'] ?? '') ?>" required>
            <?php if (!empty($errors['date'])): ?><span class="err"><?= e($errors['date']) ?></span><?php endif; ?>
        </label>
        <label>Время (ЧЧ:ММ)
            <input name="time" placeholder="14:30" value="<?= e($old['time'] ?? '') ?>" required>
            <?php if (!empty($errors['time'])): ?><span class="err"><?= e($errors['time']) ?></span><?php endif; ?>
        </label>
        <label>Вес, кг (1–20000)
            <input name="weight" type="number" min="1" max="20000" value="<?= e($old['weight'] ?? '') ?>" required>
            <?php if (!empty($errors['weight'])): ?><span class="err"><?= e($errors['weight']) ?></span><?php endif; ?>
        </label>
        <label>Габариты (Д×Ш×В, см)
            <input name="dimensions" placeholder="100×50×60" value="<?= e($old['dims'] ?? '') ?>" required>
            <?php if (!empty($errors['dimensions'])): ?><span class="err"><?= e($errors['dimensions']) ?></span><?php endif; ?>
        </label>
        <label>Адрес отправления
            <input name="address_from" value="<?= e($old['from'] ?? '') ?>" required>
            <?php if (!empty($errors['address_from'])): ?><span class="err"><?= e($errors['address_from']) ?></span><?php endif; ?>
        </label>
        <label>Адрес доставки
            <input name="address_to" value="<?= e($old['to'] ?? '') ?>" required>
            <?php if (!empty($errors['address_to'])): ?><span class="err"><?= e($errors['address_to']) ?></span><?php endif; ?>
        </label>
        <label>Тип груза
            <select name="cargo_type" required>
                <option value="">— выберите —</option>
                <?php foreach (Application::CARGO_TYPES as $t): ?>
                    <option value="<?= e($t) ?>" <?= ($old['cargo'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['cargo_type'])): ?><span class="err"><?= e($errors['cargo_type']) ?></span><?php endif; ?>
        </label>
        <button class="btn" type="submit">Отправить заявку</button>
    </form>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>