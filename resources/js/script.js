document.addEventListener('DOMContentLoaded', function () {
    const langSwitcher = document.getElementById('language-switcher');

    if (langSwitcher) {
        langSwitcher.addEventListener('change', function () {
            const selectedLang = this.value;
            window.location.href = `/lang/${selectedLang}`;
        });
    }
});
