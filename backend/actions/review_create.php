<?php
require_once dirname(__DIR__) . '/bootstrap.php';
$user = Auth::requireUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/gruz/frontend/applications.php');

$appId = (int)($_POST['application_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$text = trim($_POST['text'] ?? '');

$app = Application::find($appId);
if (!$app || $app['user_id'] != $user['id'] || $app['status'] !== 'Выполнено') {
    flash_set('error', 'Отзыв можно оставить только для выполненной заявки');
    redirect('/gruz/frontend/applications.php');
}
if ($rating < 1 || $rating > 5 || $text === '' || Review::existsFor($appId)) {
    flash_set('error', 'Проверьте оценку и текст отзыва');
    redirect('/gruz/frontend/applications.php');
}

Review::create($appId, $rating, $text);
flash_set('success', 'Спасибо за отзыв!');
redirect('/gruz/frontend/applications.php');