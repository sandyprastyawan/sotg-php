(function() {
    const TIMEOUT_SECONDS = 30;
    const REDIRECT_URL    = 'http://localhost/SanitaryOnTheGoRJ/public/dashboard.php';

    let timer;
    let countdown;
    let sisaWaktu = TIMEOUT_SECONDS;

    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        z-index: 9999;
        display: none;
    `;
    document.body.appendChild(overlay);

    function resetTimer() {
        clearTimeout(timer);
        clearInterval(countdown);
        sisaWaktu = TIMEOUT_SECONDS;
        overlay.style.display = 'none';

        timer = setTimeout(function() {
            window.location.href = REDIRECT_URL;
        }, TIMEOUT_SECONDS * 1000);

        countdown = setInterval(function() {
            sisaWaktu--;
            if (sisaWaktu <= 10) {
                overlay.style.display = 'block';
                overlay.innerHTML = `⏱ Kembali ke halaman utama dalam <b>${sisaWaktu}</b> detik...`;
            }
            if (sisaWaktu <= 0) clearInterval(countdown);
        }, 1000);
    }

    const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart', 'click'];
    events.forEach(e => document.addEventListener(e, resetTimer, true));

    resetTimer();
})();