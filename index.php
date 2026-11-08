<?php
require_once __DIR__ . '/backend/bootstrap.php';

// Слайдер — 4 картинки (положи файлы в frontend/assets/img/)
$slides = [
    ['src' => '/gruz/frontend/assets/img/slide1.jpg', 'alt' => 'Грузовик на трассе'],
    ['src' => '/gruz/frontend/assets/img/slide2.jpg', 'alt' => 'Склад'],
    ['src' => '/gruz/frontend/assets/img/slide3.jpg', 'alt' => 'Погрузка'],
    ['src' => '/gruz/frontend/assets/img/slide4.jpg', 'alt' => 'Доставка до двери'],
];

$currentUser = Auth::user();
include __DIR__ . '/frontend/partials/header.php';
?>
<section class="hero">
    <div class="slider" id="slider">
        <?php foreach ($slides as $i => $s): ?>
            <img class="slide <?= $i === 0 ? 'active' : '' ?>"
                 src="<?= e($s['src']) ?>" alt="<?= e($s['alt']) ?>">
        <?php endforeach; ?>
        <button class="slider-btn prev" type="button" aria-label="Назад">‹</button>
        <button class="slider-btn next" type="button" aria-label="Вперёд">›</button>
    </div>
    <div class="hero-text">
        <h1>ГрузДоставка</h1>
        <p>Перевозки по городу и области. Оформите заявку — приедем вовремя.</p>
        <?php if ($currentUser): ?>
            <a class="btn" href="/gruz/frontend/application_create.php">Создать заявку</a>
        <?php else: ?>
            <a class="btn" href="/gruz/frontend/register.php">Зарегистрироваться</a>
            <a class="btn-ghost" href="/gruz/frontend/login.php">Войти</a>
        <?php endif; ?>
    </div>
</section>

<section class="features">
    <div class="feature"><h3>Свой склад</h3><p>Хранение и перегрузка.</p></div>
    <div class="feature"><h3>Доставка до двери</h3><p>Привезём точно по адресу.</p></div>
    <div class="feature"><h3>Рефрижераторы</h3><p>Скоропортящиеся грузы.</p></div>
</section>
<?php include __DIR__ . '/frontend/partials/footer.php'; ?>