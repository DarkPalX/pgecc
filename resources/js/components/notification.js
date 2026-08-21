export function initNotificationSystem() {
    console.log('Initializing notification system');
    
    const container = document.getElementById('notification-container');
    if (!container) return;

    function initToastLifecycle(toast) {
        if (toast.hasAttribute('data-initialized')) return;
        toast.setAttribute('data-initialized', 'true');

        const duration = parseInt(toast.getAttribute('data-duration')) || 5000;
        let dismissTimer;
        let timeRemaining = duration;
        let startTime = Date.now();

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        function startTimeout() {
            startTime = Date.now();
            dismissTimer = setTimeout(() => {
                removeToast(toast);
            }, timeRemaining);
        }

        function pauseTimeout() {
            clearTimeout(dismissTimer);
            timeRemaining -= (Date.now() - startTime);
            if (timeRemaining < 1000) timeRemaining = 1000;
        }

        toast.addEventListener('mouseenter', pauseTimeout);
        toast.addEventListener('mouseleave', startTimeout);

        const closeBtn = toast.querySelector('.close-notification');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => removeToast(toast));
        }

        startTimeout();
    }

    function removeToast(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');

        toast.addEventListener('transitionend', (e) => {
            if (e.propertyName === 'max-height') {
                toast.remove();
            }
        });
    }

    // Initialize existing ones
    container.querySelectorAll('[x-init-toast]').forEach(initToastLifecycle);

    // Watch for new ones
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const target = node.hasAttribute('x-init-toast') ? node : node.querySelector('[x-init-toast]');
                    if (target) initToastLifecycle(target);
                }
            });
        });
    });

    observer.observe(container, { childList: true, subtree: true });
}