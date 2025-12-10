/**
 * Dropdown functionality with vanilla JavaScript
 * Replaces Alpine.js dropdown behavior
 */

document.addEventListener('DOMContentLoaded', function() {
    initDropdowns();
    initMobileMenu();
});

/**
 * Initialize all dropdown menus
 */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('[data-dropdown]');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const content = dropdown.querySelector('[data-dropdown-content]');

        if (!trigger || !content) return;

        // Toggle dropdown on trigger click
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(dropdown, content);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                closeDropdown(content);
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDropdown(content);
            }
        });

        // Close dropdown when clicking inside content (for links)
        content.addEventListener('click', () => {
            closeDropdown(content);
        });
    });
}

/**
 * Toggle dropdown visibility
 */
function toggleDropdown(dropdown, content) {
    const isOpen = content.style.display !== 'none';

    // Close all other dropdowns first
    closeAllDropdowns();

    if (isOpen) {
        closeDropdown(content);
    } else {
        openDropdown(content);
    }
}

/**
 * Open dropdown with transition
 */
function openDropdown(content) {
    content.style.display = 'block';
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95)';

    // Trigger reflow
    content.offsetHeight;

    // Add transition
    content.style.transition = 'opacity 200ms ease-out, transform 200ms ease-out';
    content.style.opacity = '1';
    content.style.transform = 'scale(1)';
}

/**
 * Close dropdown with transition
 */
function closeDropdown(content) {
    if (content.style.display === 'none') return;

    content.style.transition = 'opacity 75ms ease-in, transform 75ms ease-in';
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95)';

    setTimeout(() => {
        content.style.display = 'none';
    }, 75);
}

/**
 * Close all dropdowns
 */
function closeAllDropdowns() {
    const dropdowns = document.querySelectorAll('[data-dropdown-content]');
    dropdowns.forEach(dropdown => {
        closeDropdown(dropdown);
    });
}

/**
 * Initialize mobile menu (hamburger)
 */
function initMobileMenu() {
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const hamburgerOpen = document.querySelector('[data-hamburger-open]');
    const hamburgerClose = document.querySelector('[data-hamburger-close]');

    if (!mobileMenuButton || !mobileMenu) return;

    mobileMenuButton.addEventListener('click', () => {
        toggleMobileMenu(mobileMenu, hamburgerOpen, hamburgerClose);
    });
}

/**
 * Toggle mobile menu visibility
 */
function toggleMobileMenu(menu, iconOpen, iconClose) {
    const isHidden = menu.classList.contains('hidden');

    if (isHidden) {
        // Show menu
        menu.classList.remove('hidden');
        menu.classList.add('block');

        // Toggle icons
        if (iconOpen && iconClose) {
            iconOpen.classList.add('hidden');
            iconOpen.classList.remove('inline-flex');
            iconClose.classList.remove('hidden');
            iconClose.classList.add('inline-flex');
        }
    } else {
        // Hide menu
        menu.classList.add('hidden');
        menu.classList.remove('block');

        // Toggle icons
        if (iconOpen && iconClose) {
            iconOpen.classList.remove('hidden');
            iconOpen.classList.add('inline-flex');
            iconClose.classList.add('hidden');
            iconClose.classList.remove('inline-flex');
        }
    }
}
