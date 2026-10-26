import './bootstrap';

/**
 * Frontend Navigation and Shell Interactions
 * SMP Muhammadiyah 7 Medan — SIAKAD Public Portal
 */

document.addEventListener('DOMContentLoaded', () => {
    initDesktopDropdowns();
    initMobileNav();
    initMobileAccordions();
    initBackToTop();
    initHeroSlideshow();
});

/**
 * 1. Desktop Navigation Dropdown Menus
 * Supports hover, click, and full keyboard navigation (Tab, Enter, Space, Escape)
 */
function initDesktopDropdowns() {
    const containers = document.querySelectorAll('.dropdown-container');

    containers.forEach((container) => {
        const toggle = container.querySelector('.dropdown-toggle');
        const menu = container.querySelector('.dropdown-menu');
        const chevron = container.querySelector('.dropdown-chevron');
        if (!toggle || !menu) return;

        let closeTimeout = null;

        function openMenu() {
            clearTimeout(closeTimeout);
            // Close other open desktop dropdowns first
            containers.forEach((other) => {
                if (other !== container) {
                    closeMenuEl(other.querySelector('.dropdown-toggle'), other.querySelector('.dropdown-menu'), other.querySelector('.dropdown-chevron'));
                }
            });

            menu.classList.remove('opacity-0', 'pointer-events-none', 'invisible', '-translate-y-2');
            menu.classList.add('opacity-100', 'pointer-events-auto', 'visible', 'translate-y-0');
            toggle.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.classList.add('rotate-180');
        }

        function closeMenu() {
            closeTimeout = setTimeout(() => {
                closeMenuEl(toggle, menu, chevron);
            }, 120);
        }

        // Mouse hover
        container.addEventListener('mouseenter', openMenu);
        container.addEventListener('mouseleave', closeMenu);

        // Click on toggle button
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                closeMenuEl(toggle, menu, chevron);
            } else {
                openMenu();
            }
        });

        // Keyboard navigation
        container.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMenuEl(toggle, menu, chevron);
                toggle.focus();
            }
        });

        // Close when focus leaves the container
        container.addEventListener('focusout', (e) => {
            if (!container.contains(e.relatedTarget)) {
                closeMenuEl(toggle, menu, chevron);
            }
        });
    });

    function closeMenuEl(toggle, menu, chevron) {
        if (!menu || !toggle) return;
        menu.classList.add('opacity-0', 'pointer-events-none', 'invisible', '-translate-y-2');
        menu.classList.remove('opacity-100', 'pointer-events-auto', 'visible', 'translate-y-0');
        toggle.setAttribute('aria-expanded', 'false');
        if (chevron) chevron.classList.remove('rotate-180');
    }
}

/**
 * 2. Mobile Navigation Drawer & Backdrop
 * Responsive drawer with smooth slide-in, scroll lock, backdrop click, and focus trap
 */
function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-nav-toggle');
    const closeBtn = document.getElementById('mobile-nav-drawer-close');
    const drawer = document.getElementById('mobile-nav-drawer');
    const backdrop = document.getElementById('mobile-nav-backdrop');
    const hamburgerIcon = document.getElementById('icon-hamburger');
    const closeIcon = document.getElementById('icon-close');

    if (!toggleBtn || !drawer || !backdrop) return;

    function openDrawer() {
        drawer.classList.remove('translate-x-full');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');

        toggleBtn.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');

        if (hamburgerIcon && closeIcon) {
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        }

        // Focus close button in drawer for screen readers
        setTimeout(() => {
            if (closeBtn) closeBtn.focus();
        }, 100);
    }

    function closeDrawer() {
        drawer.classList.add('translate-x-full');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');

        toggleBtn.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');

        if (hamburgerIcon && closeIcon) {
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }

        toggleBtn.focus();
    }

    // Toggle button click
    toggleBtn.addEventListener('click', () => {
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
        if (isExpanded) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    // Close button click
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }

    // Backdrop click
    backdrop.addEventListener('click', closeDrawer);

    // Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && toggleBtn.getAttribute('aria-expanded') === 'true') {
            closeDrawer();
        }
    });

    // Close drawer when any navigation link is clicked
    const navLinks = drawer.querySelectorAll('a');
    navLinks.forEach((link) => {
        link.addEventListener('click', () => {
            closeDrawer();
        });
    });

    // Accessibility: Simple Focus Trap inside drawer
    drawer.addEventListener('keydown', (e) => {
        if (e.key !== 'Tab') return;

        const focusables = drawer.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusables.length === 0) return;

        const firstElement = focusables[0];
        const lastElement = focusables[focusables.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    });
}

/**
 * 3. Mobile Navigation Accordions
 * Allows collapsible submenus inside mobile drawer
 */
function initMobileAccordions() {
    const accordions = document.querySelectorAll('.mobile-accordion');

    accordions.forEach((acc) => {
        const btn = acc.querySelector('.mobile-accordion-btn');
        const content = acc.querySelector('.mobile-accordion-content');
        const icon = acc.querySelector('.mobile-accordion-icon');
        if (!btn || !content) return;

        btn.addEventListener('click', () => {
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                content.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                if (icon) icon.classList.remove('rotate-180');
            } else {
                content.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
                if (icon) icon.classList.add('rotate-180');
            }
        });
    });
}

/**
 * 4. Back to Top Button
 * Smooth scrolling to top
 */
function initBackToTop() {
    const btn = document.getElementById('back-to-top-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * 5. Hero Slideshow
 * Automatic slide carousel with manual navigation, keyboard support, and pause on hover
 */
function initHeroSlideshow() {
    const track = document.getElementById('hero-slides-track');
    if (!track) return;

    const slides = track.querySelectorAll('.hero-slide');
    if (slides.length <= 1) return;

    const prevBtn = document.getElementById('hero-prev');
    const nextBtn = document.getElementById('hero-next');
    const dotsContainer = document.getElementById('hero-dots');
    const dots = dotsContainer ? dotsContainer.querySelectorAll('.hero-dot') : [];

    let currentIndex = 0;
    let timer = null;
    const intervalTime = 7000; // 7 detik per slide agar pengunjung leluasa menikmati foto dan membaca konten teks

    function goToSlide(index) {
        currentIndex = (index + slides.length) % slides.length;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Update dots
        dots.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.setAttribute('aria-selected', 'true');
                dot.classList.remove('w-2', 'bg-white/50');
                dot.classList.add('w-6', 'bg-white');
            } else {
                dot.setAttribute('aria-selected', 'false');
                dot.classList.remove('w-6', 'bg-white');
                dot.classList.add('w-2', 'bg-white/50');
            }
        });
    }

    function startAutoSlide() {
        stopAutoSlide();
        timer = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, intervalTime);
    }

    function stopAutoSlide() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
            startAutoSlide();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
            startAutoSlide();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToSlide(idx);
            startAutoSlide();
        });
    });

    // Pause on hover / focus
    const container = document.getElementById('hero-slideshow');
    if (container) {
        container.addEventListener('mouseenter', stopAutoSlide);
        container.addEventListener('mouseleave', startAutoSlide);
        container.addEventListener('focusin', stopAutoSlide);
        container.addEventListener('focusout', startAutoSlide);
    }

    // Start auto slideshow
    startAutoSlide();
}
