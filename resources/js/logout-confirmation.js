/**
 * Logout Confirmation Modal
 * Prevents accidental logout with a vanilla JavaScript confirmation dialog
 */

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('logoutConfirmationModal');
    const confirmBtn = document.getElementById('logoutConfirmBtn');
    const cancelBtn = document.getElementById('logoutCancelBtn');
    const overlay = document.getElementById('logoutModalOverlay');

    let logoutFormToSubmit = null;
    const submitHandlers = new WeakMap();

    // Guard: Exit if required elements don't exist
    if (!modal || !confirmBtn || !cancelBtn || !overlay) {
        console.error('Logout confirmation modal elements not found');
        return;
    }

    const logoutForms = document.querySelectorAll('form[data-logout-form]');

    logoutForms.forEach((form) => {
        const handler = (e) => {
            e.preventDefault();
            logoutFormToSubmit = form;
            showModal();
        };
        submitHandlers.set(form, handler);
        form.addEventListener('submit', handler);
    });

    function showModal() {
        modal.classList.add('active');
        overlay.classList.add('active');
        confirmBtn.focus();
    }

    function hideModal() {
        modal.classList.remove('active');
        overlay.classList.remove('active');
        logoutFormToSubmit = null;
    }

    confirmBtn.addEventListener('click', () => {
        if (logoutFormToSubmit) {
            const handler = submitHandlers.get(logoutFormToSubmit);
            if (handler) {
                logoutFormToSubmit.removeEventListener('submit', handler);
            }
            logoutFormToSubmit.submit();
        }
    });

    cancelBtn.addEventListener('click', hideModal);
    overlay.addEventListener('click', hideModal);

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            hideModal();
        }
    });
});

