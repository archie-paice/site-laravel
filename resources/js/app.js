// Render any <time data-local datetime="..."> element in the viewer's own
// timezone and locale. The server-rendered content acts as a fallback.
function localizeTimes() {
    document.querySelectorAll('time[data-local]').forEach((el) => {
        const date = new Date(el.getAttribute('datetime'));
        if (!isNaN(date)) {
            el.textContent = date.toLocaleString(undefined, { dateStyle: 'short', timeStyle: 'short' });
        }
    });
}

document.addEventListener('DOMContentLoaded', localizeTimes);
