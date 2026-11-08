<?php
require_once dirname(__DIR__) . '/backend/bootstrap.php';
$user = Auth::requireUser();
$apps = Application::byUser((int)$user['id']);
$success = flash_get('success');
$error = flash_get('error');
include __DIR__ . '/partials/header.php';
?>
<section class="card">
    <h1>Мои заявки</h1>
    <?php if ($success): ?><div class="flash success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>

    <?php if (!$apps): ?>
        <p class="muted">У вас пока нет заявок. <a href="/gruz/frontend/application_create.php">Создать первую</a>.</p>
    <?php endif; ?>

    <div class="apps-list">
    <?php foreach ($apps as $a): ?>
        <div class="app-card">
            <div class="app-head">
                <strong>#<?= (int)$a['id'] ?></strong>
                <span class="status status-<?= e(mb_strtolower(str_replace(' ', '_', $a['status']))) ?>">
                    <?= e($a['status']) ?>
                </span>
            </div>
            <div class="app-body">
                <p><b>Когда:</b> <?= e(date('d.m.Y', strtotime($a['transport_date']))) ?> в <?= e(substr($a['transport_time'], 0, 5)) ?></p>
                <p><b>Откуда:</b> <?= e($a['address_from']) ?></p>
                <p><b>Куда:</b> <?= e($a['address_to']) ?></p>
                <p><b>Груз:</b> <?= e($a['cargo_type']) ?> · <?= (int)$a['weight_kg'] ?> кг · <?= e($a['dimensions']) ?> см</p>
                <?php if ($a['status'] === 'Отменена' && $a['cancel_reason']): ?>
                    <p class="cancel-reason"><b>Причина отмены:</b> <?= e($a['cancel_reason']) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($a['status'] === 'Выполнено'): ?>
                <?php if ($a['review_id']): ?>
                    <div class="review-done">
                        ⭐ Ваш отзыв: <?= (int)$a['rating'] ?>/5 — <?= e($a['review_text']) ?>
                    </div>
                <?php else: ?>
                    <details class="review-form">
                        <summary>Оставить отзыв</summary>
                        <form method="post" action="/gruz/backend/actions/review_create.php">
                            <input type="hidden" name="application_id" value="<?= (int)$a['id'] ?>">
                            <label>Оценка
                                <select name="rating" required>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </label>
                            <label>Комментарий
                                <textarea name="text" rows="3" required></textarea>
                            </label>
                            <button class="btn" type="submit">Отправить</button>
                        </form>
                    </details>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>