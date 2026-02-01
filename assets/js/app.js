/**
 * FitMe Gym
 */

function toggleKebab(btn) {
    var menu = btn.nextElementSibling;
    var open = menu.classList.toggle('open');
    if (open) {
        document.addEventListener('click', function close(e) {
            if (!menu.contains(e.target) && e.target !== btn) {
                menu.classList.remove('open');
                document.removeEventListener('click', close);
            }
        });
    }
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
