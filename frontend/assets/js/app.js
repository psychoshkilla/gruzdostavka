document.addEventListener('DOMContentLoaded', () => {
    initSlider();
    initCancelReasonToggle();
    initDateClientValidation();
});

function initSlider() {
    const slider = document.getElementById('slider');
    if (!slider) return;
    const slides = Array.from(slider.querySelectorAll('.slide'));
    if (slides.length < 2) return;
    let idx = slides.findIndex(s => s.classList.contains('active'));
    if (idx < 0) idx = 0;

    const show = (i) => {
        slides.forEach((s, k) => s.classList.toggle('active', k === i));
    };

    let timer = setInterval(() => { idx = (idx + 1) % slides.length; show(idx); }, 3000);

    const reset = () => { clearInterval(timer); timer = setInterval(() => { idx = (idx + 1) % slides.length; show(idx); }, 3000); };

    slider.querySelector('.next')?.addEventListener('click', () => { idx = (idx + 1) % slides.length; show(idx); reset(); });
    slider.querySelector('.prev')?.addEventListener('click', () => { idx = (idx - 1 + slides.length) % slides.length; show(idx); reset(); });
}

function initCancelReasonToggle() {
    document.querySelectorAll('.status-form').forEach(form => {
        const select = form.querySelector('.status-select');
        const box = form.querySelector('.cancel-box');
        if (!select || !box) return;
        const toggle = () => {
            const on = select.value === 'Отменена';
            box.style.display = on ? 'block' : 'none';
            box.querySelector('input').required = on;
        };
        select.addEventListener('change', toggle);
        toggle();
    });
}

function initDateClientValidation() {
    const f = document.getElementById('dateField');
    if (!f) return;
    f.addEventListener('blur', () => {
        const v = f.value.trim();
        const m = /^(\d{2})\.(\d{2})\.(\d{4})$/.exec(v);
        if (!m) return;
        const d = new Date(+m[3], +m[2] - 1, +m[1]);
        const today = new Date(); today.setHours(0,0,0,0);
        f.classList.toggle('error', d < today);
    });
}