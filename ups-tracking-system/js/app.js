/**
 * UPS Tracking System — Main JavaScript
 */
import './bootstrap';

// -- Sidebar Toggle (Mobile & Desktop) --
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth <= 991.98) {
                sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('show');
                sidebarToggle.classList.toggle('is-expanded');
            } else {
                sidebar.classList.toggle('collapsed');
                const mainContent = document.querySelector('.main-content');
                if (mainContent) mainContent.classList.toggle('expanded');
                sidebarToggle.classList.toggle('is-collapsed');
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // -- Audit Log Value Toggle --
    document.querySelectorAll('.audit-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            if (target) {
                target.classList.toggle('expanded');
                btn.textContent = target.classList.contains('expanded') ? 'Hide Details' : 'View Details';
            }
        });
    });

    // -- Fade-in animation on stat cards --
    document.querySelectorAll('.fade-in-up').forEach((el, i) => {
        el.style.animationDelay = `${i * 0.1}s`;
    });

    // -- Delete confirmation --
    document.querySelectorAll('.btn-delete-confirm').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
