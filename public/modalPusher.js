function createModal(params) {
    const modal = document.createElement('div');
    modal.setAttribute('id', 'myModal');
    modal.classList.add('fixed', 'inset-0', 'bg-gray-600', 'bg-opacity-50', 'overflow-y-auto', 'h-100', 'w-full', 'flex', 'items-center', 'justify-center');

    // Membuat modal content
    const modalContent = document.createElement('div');
    modalContent.classList.add('bg-white', 'p-5', 'rounded-lg', 'shadow-lg', 'max-w-sm');

    // Membuat modal header
    const modalHeader = document.createElement('div');
    modalHeader.classList.add('modal-header', 'flex', 'justify-between', 'items-center', 'pb-3');

    const title = document.createElement('p');
    title.classList.add('text-2xl', 'font-bold');
    title.textContent = 'Alert';

    const closeButton = document.createElement('div');
    closeButton.classList.add('modal-close', 'cursor-pointer', 'z-50');
    closeButton.innerHTML = '<span class="text-black text-2xl"></span>';

    // Menambahkan title dan closeButton ke modalHeader
    modalHeader.appendChild(title);
    modalHeader.appendChild(closeButton);

    // Membuat modal body
    const modalBody = document.createElement('div');
    modalBody.classList.add('modal-body');

    const messageP = document.createElement('p');
    messageP.setAttribute('id', 'modalMessage');
    messageP.textContent = params != null ? params : 'Your message here...';

    // Menambahkan messageP ke modalBody
    modalBody.appendChild(messageP);

    // Membuat modal footer
    const modalFooter = document.createElement('div');
    modalFooter.classList.add('modal-footer', 'flex', 'justify-end', 'pt-2');

    const closeBtn = document.createElement('button');
    closeBtn.classList.add('px-4', 'bg-transparent', 'p-3', 'rounded-lg', 'text-indigo-500', 'hover:bg-gray-100', 'hover:text-indigo-400', 'mr-2');
    closeBtn.textContent = 'Close';

    // Menambahkan closeBtn ke modalFooter
    modalFooter.appendChild(closeBtn);

    // Menambahkan modalHeader, modalBody, dan modalFooter ke modalContent
    modalContent.appendChild(modalHeader);
    modalContent.appendChild(modalBody);
    modalContent.appendChild(modalFooter);

    // Menambahkan modalContent ke modal
    modal.appendChild(modalContent);

    // Menambahkan modal ke mainApp
    const mainApp = document.getElementById('mainApp');
    mainApp.appendChild(modal);

    // Event listeners untuk menutup modal
    closeButton.addEventListener('click', function () {
        modal.classList.add('hidden');
        setTimeout(function () {
            window.location.reload(true);
            modal.remove();
        }, 500);
    });

    closeBtn.addEventListener('click', function () {
        modal.classList.add('hidden');
        setTimeout(function () {
            window.location.reload(true);
            modal.remove();
        }, 500);
    });

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
            setTimeout(function () {
                window.location.reload(true);
                modal.remove();
            }, 500);
        }
    };
}