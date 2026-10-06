document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    const sidebarToggle = document.getElementById(
        'sidebar-toggle'
    );

    const sidebarOverlay = document.getElementById(
        'sidebar-overlay'
    );

    const navigationLinks = document.querySelectorAll(
        '.sidebar .navigation-link'
    );

    function openSidebar() {
        body.classList.add('sidebar-open');

        if (sidebarToggle) {
            sidebarToggle.setAttribute(
                'aria-expanded',
                'true'
            );
        }
    }

    function closeSidebar() {
        body.classList.remove('sidebar-open');

        if (sidebarToggle) {
            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    }

    function toggleSidebar() {
        if (body.classList.contains('sidebar-open')) {
            closeSidebar();
            return;
        }

        openSidebar();
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener(
            'click',
            toggleSidebar
        );
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener(
            'click',
            closeSidebar
        );
    }

    navigationLinks.forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 900) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape'
            && body.classList.contains('sidebar-open')
        ) {
            closeSidebar();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            closeSidebar();
        }
    });
});
