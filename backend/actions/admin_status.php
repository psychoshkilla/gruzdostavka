<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Проверяем, что зашел именно админ. Если нет — выкинет на логин.
Auth::requireAdmin();

// Если попытались зайти просто по ссылке (GET), а не через форму — выкидываем в админку
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/gruz/frontend/admin.php');
}

$appId = (int)($_POST['application_id'] ?? 0);
$status = $_POST['status'] ?? '';
$reason = trim($_POST['cancel_reason'] ?? '');

// Проверяем, что статус действительно существует (защита от взлома)
$allowed = ['Новая', 'В работе', 'Выполнено', 'Отменена'];
if (!in_array($status, $allowed, true)) {
    flash_set('error', 'Недопустимый статус');
    redirect('/gruz/frontend/admin.php');
}

// Ищем заявку в базе
$app = Application::find($appId);
if (!$app) {
    flash_set('error', 'Заявка не найдена');
    redirect('/gruz/frontend/admin.php');
}

// ГЛАВНОЕ ТРЕБОВАНИЕ: если статус "Отменена", причина ОБЯЗАТЕЛЬНА
if ($status === 'Отменена' && $reason === '') {
    flash_set('error', 'Для отмены заявки обязательно укажите причину!');
    redirect('/gruz/frontend/admin.php');
}

// Обновляем статус в базе данных
Application::updateStatus($appId, $status, $reason ?: null);

// Показываем зеленое всплывающее уведомление об успехе
flash_set('success', "Статус заявки #$appId успешно изменён на «$status»");

// Возвращаем админа обратно в панель
redirect('/gruz/frontend/admin.php');