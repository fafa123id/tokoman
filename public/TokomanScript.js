var z = document.querySelector('#mainApp');
var a = document.createElement('div');
function showLoadingModal() {
    a.className = 'fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex justify-center items-center';
    a.innerHTML = `<p class="text-white text-lg">Loading...</p>`;
    z.appendChild(a);
}

function hideLoadingModal() {
    if (z.contains(a)) {
        z.removeChild(a);
    }
}
document.querySelectorAll('form').forEach(function (a) {
    a.addEventListener('submit', function (event) {
        if (!a.getAttribute('target') === '_blank') 
            showLoadingModal();
    });
});
window.onload = function () {
    hideLoadingModal();
}
window.onpopstate = function () {
    showLoadingModal();
}
window.addEventListener('beforeunload', function (event) {
    showLoadingModal();
});
window.addEventListener('popstate', function (event) {
    showLoadingModal();
});
document.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function (event) {
        if (!a.getAttribute('target') === '_blank') 
            showLoadingModal();
    });
});
