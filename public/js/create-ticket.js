document.getElementById('priority-select').addEventListener('change', function () {
    const option    = this.options[this.selectedIndex];
    const days      = parseInt(option.dataset.days || 0);
    const color     = option.dataset.color || '#f3f4f6';
    const textColor = option.dataset.text  || '#374151';
    const dateInput = document.getElementById('due-date-input');
    const badge     = document.getElementById('due-date-badge');

    if (!this.value) {
        dateInput.value = '';
        badge.style.display = 'none';
        return;
    }

    const today = new Date();
    today.setDate(today.getDate() + days);
    const yyyy = today.getFullYear();
    const mm   = String(today.getMonth() + 1).padStart(2, '0');
    const dd   = String(today.getDate()).padStart(2, '0');
    dateInput.value = `${yyyy}-${mm}-${dd}`;

    badge.textContent = `+${days} days`;
    badge.style.cssText = `display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:${color};color:${textColor};`;
});

window.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('priority-select');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});