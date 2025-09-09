/**
 * Fix for content disappearing issue
 * Forces visibility of main content areas
 */
(function() {
    'use strict';
    
    function forceVisibility() {
        if (!document || !document.body || !document.documentElement) {
            // Try again when DOM is ready
            if (document && document.addEventListener) {
                document.addEventListener('DOMContentLoaded', forceVisibility, { once: true });
            }
            return;
        }
        console.log('[FIX] Forcing content visibility...');
        
        // Remove any problematic classes from body
        const problematicClasses = [
            'showing-modal', 'hiding-modal', 'showing-menu-modal',
'showing-main-menu-modal', 'showing-search-modal',
            'showing-mob-search-modal', 'modal-open', 'has-modal',
            // Additional classes sometimes left by libraries/dialogs
            'dialog-body','dialog-buttons-body','dialog-container','dialog-buttons-container'
        ];
        
        problematicClasses.forEach(className => {
            if (document.body.classList.contains(className)) {
                console.log('[FIX] Removing class:', className);
                document.body.classList.remove(className);
            }
        });
        
        // Force main content areas to be visible
        const contentSelectors = [
            '#primary',
            '.content-area',
            '#main',
            '.site-main',
            'main',
            '.site-content'
        ];
        
        contentSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                if (el) {
                    // Remove any inline styles that might hide content
                    el.style.removeProperty('display');
                    el.style.removeProperty('visibility');
                    el.style.removeProperty('opacity');
                    el.style.removeProperty('height');
                    el.style.removeProperty('overflow');
                    
                    // Force visibility
                    el.style.setProperty('display', 'block', 'important');
                    el.style.setProperty('visibility', 'visible', 'important');
                    el.style.setProperty('opacity', '1', 'important');
                }
            });
        });

        // If main is suspiciously short, try to unhide its children
        const main = document.querySelector('#primary, .content-area, #main, .site-main, main, .site-content');
        if (main) {
            const rect = main.getBoundingClientRect();
            if (rect && rect.height < 150) {
                main.querySelectorAll('section, div, article').forEach(node => {
                    node.style.removeProperty('display');
                    node.style.removeProperty('visibility');
                    node.style.setProperty('display', 'block', 'important');
                    node.style.setProperty('visibility', 'visible', 'important');
                    node.style.removeProperty('height');
                    node.style.removeProperty('overflow');
                });
            }
        }
        
        // Clear any problematic body/html styles
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('overflow-y');
        document.body.style.removeProperty('position');
        document.body.style.removeProperty('width');
        document.body.style.removeProperty('height');
        
        document.documentElement.style.removeProperty('overflow');
        document.documentElement.style.removeProperty('overflow-y');
        document.documentElement.style.removeProperty('position');
        document.documentElement.style.removeProperty('width');
        document.documentElement.style.removeProperty('height');
        
        // Find and disable any modal overlays
        const overlays = document.querySelectorAll(
            '.modal-overlay, .menu-modal, .cover-modal, .main-menu-modal, .search-modal'
        );
        overlays.forEach(overlay => {
            overlay.style.setProperty('display', 'none', 'important');
        });
        
        console.log('[FIX] Visibility fix applied');
    }
    
    // Run immediately (guarded)
    try { forceVisibility(); } catch(e) { /* ignore */ }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', forceVisibility);
    }
    
    // Run after a short delay to catch late-loading scripts
    setTimeout(forceVisibility, 100);
    setTimeout(forceVisibility, 500);
    setTimeout(forceVisibility, 1000);
    
    // Run on window load
    window.addEventListener('load', forceVisibility);
    
    // Run on visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            forceVisibility();
        }
    });
    
    // Monitor for changes that might hide content
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            let needsFix = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes') {
                    if (mutation.attributeName === 'class' || mutation.attributeName === 'style') {
                        needsFix = true;
                    }
                }
            });
            
            if (needsFix) {
                forceVisibility();
            }
        });
        
        // Observe body for class and style changes
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['class', 'style']
        });
    }
})();
