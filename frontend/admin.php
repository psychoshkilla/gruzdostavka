<?php
require_once dirname(__DIR__) . '/backend/bootstrap.php';
Auth::requireAdmin();

$status = $_GET['status'] ?? '';
$cargo  = $_GET['cargo'] ?? '';
$sort   = $_GET['sort'] ?? 'asc';
if (!in_array($sort, ['asc', 'desc'], true)) $sort = 'asc';
if ($status !== '' && !in_array($status, ['Новая','В работе','Выполнено','Отменена'], true)) $status = '';
if ($cargo !== '' && !in_array($cargo, Application::CARGO_TYPES, true)) $cargo = '';

$all = Application::findAll($status ?: null, $cargo ?: null, $sort);

// Пагинация по 5
$perPage = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = count($all);
$pages = max(1, (int)ceil($total / $perPage));
if ($page > $pages) $page = $pages;
$apps = array_slice($all, ($page - 1) * $perPage, $perPage);

$success = flash_get('success');
$error = flash_get('error');

$qs = function(array $overrides = []) use ($status, $cargo, $sort, $page) {
    $p = array_merge(['status' => $status, 'cargo' => $cargo, 'sort' => $sort, 'page' => $page], $overrides);
    $p = array_filter($p, fn($v) => $v !== '' && $v !== null);
    return '?' . http_build_query($p);
};

include __DIR__ . '/partials/header.php';
?>
<section class="card wide">
    <h1>Панель администратора</h1>
    <?php if ($success): ?><div class="flash success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>

    <form class="filters" method="get" action="/gruz/frontend/admin.php">
        <label>Статус
            <select name="status" onchange="this.form.submit()">
                <option value="">Все</option>
                <?php foreach (['Новая','В работе','Выполнено','Отменена'] as $s): ?>
                    <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Тип груза
            <select name="cargo" onchange="this.form.submit()">
                <option value="">Все</option>
                <?php foreach (Application::CARGO_TYPES as $t): ?>
                    <option value="<?= e($t) ?>" <?= $cargo === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Сортировка по дате
            <select name="sort" onchange="this.form.submit()">
                <option value="asc"  <?= $sort === 'asc' ? 'selected' : '' ?>>По возрастанию</option>
                <option value="desc" <?= $sort === 'desc' ? 'selected' : '' ?>>По убыванию</option>
            </select>
        </label>
    </form>

    <p class="muted">Найдено: <?= $total ?> · Страница <?= $page ?> из <?= $pages ?></p>

    <?php if (!$apps): ?><p class="muted">Заявок не найдено.</p><?php endif; ?>

    <div class="apps-list">
    <?php foreach ($apps as $a): ?>
        <div class="app-card" id="app-<?= (int)$a['id'] ?>">
            <div class="app-head">
                <strong>#<?= (int)$a['id'] ?></strong>
                <span class="status status-<?= e(mb_strtolower(str_replace(' ', '_', $a['status']))) ?>">
                    <?= e($a['status']) ?>
                </span>
            </div>
            <div class="app-body">
                <p><b>Пользователь:</b> <?= e($a['full_name']) ?> · <?= e($a['phone']) ?> · <?= e($a['email']) ?></p>
                <p><b>Когда:</b> <?= e(date('d.m.Y', strtotime($a['transport_date']))) ?> в <?= e(substr($a['transport_time'], 0, 5)) ?></p>
                <p><b>Маршрут:</b> <?= e($a['address_from']) ?> → <?= e($a['address_to']) ?></p>
                <p><b>Груз:</b> <?= e($a['cargo_type']) ?> · <?= (int)$a['weight_kg'] ?> кг · <?= e($a['dimensions']) ?> см</p>
                <?php if ($a['status'] === 'Отменена' && $a['cancel_reason']): ?>
                    <p class="cancel-reason"><b>Причина отмены:</b> <?= e($a['cancel_reason']) ?></p>
                <?php endif; ?>
            </div>

            <form class="status-form" method="post" action="/gruz/backend/actions/admin_status.php">
                <input type="hidden" name="application_id" value="<?= (int)$a['id'] ?>">
                <label>Новый статус
                    <select name="status" class="status-select">
                        <?php foreach (['Новая','В работе','Выполнено','Отменена'] as $s): ?>
                            <option value="<?= e($s) ?>" <?= $a['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="cancel-box" style="display:none">Причина отмены
                    <input name="cancel_reason" maxlength="500" value="<?= e($a['cancel_reason'] ?? '') ?>">
                </label>
                <button class="btn small" type="submit">Применить</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= e($qs(['page' => $page - 1])) ?>">← Назад</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= e($qs(['page' => $i])) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $pages): ?>
                <a href="<?= e($qs(['page' => $page + 1])) ?>">Вперёд →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>