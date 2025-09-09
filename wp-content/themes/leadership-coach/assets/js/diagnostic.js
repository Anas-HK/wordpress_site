// Diagnostic script to identify rendering issues
(function() {
    console.log('=== DIAGNOSTIC START ===');
    
    // Check if DOM is properly loaded
    console.log('Document ready state:', document.readyState);
    console.log('Body element exists:', !!document.body);
    
    // Check for any hidden or display:none elements
    const bodyStyles = window.getComputedStyle(document.body);
    console.log('Body display:', bodyStyles.display);
    console.log('Body visibility:', bodyStyles.visibility);
    console.log('Body opacity:', bodyStyles.opacity);
    
    // Check for overlays or modals
    const overlays = document.querySelectorAll('.modal, .overlay, .cover-modal, .menu-modal');
    console.log('Found overlays:', overlays.length);
    overlays.forEach((el, i) => {
        const styles = window.getComputedStyle(el);
        console.log(`Overlay ${i}:`, el.className, 'display:', styles.display, 'visibility:', styles.visibility);
    });
    
    // Check main content areas
    const mainContent = document.querySelector('#primary, .content-area, main');
    if (mainContent) {
        const mainStyles = window.getComputedStyle(mainContent);
        console.log('Main content display:', mainStyles.display);
        console.log('Main content visibility:', mainStyles.visibility);
    }
    
    // Check for CSS classes that might hide content
    const bodyClasses = document.body.className;
    console.log('Body classes:', bodyClasses);
    
    // Check for any inline styles
    console.log('Body inline styles:', document.body.getAttribute('style'));
    console.log('HTML inline styles:', document.documentElement.getAttribute('style'));
    
    // Listen for any errors
    window.addEventListener('error', function(e) {
        console.error('JavaScript error:', e.message, 'at', e.filename, ':', e.lineno);
    });
    
// Log after load and pageshow too
function logState(tag){
    try{
        const main = document.querySelector('#primary, .content-area, main');
        const rect = main ? main.getBoundingClientRect() : null;
        console.log(`[DIAG ${tag}] body classes:`, document.body.className);
        console.log(`[DIAG ${tag}] html style:`, document.documentElement.getAttribute('style'));
        console.log(`[DIAG ${tag}] main rect:`, rect ? `${rect.width}x${rect.height}` : 'no main');
    }catch(e){console.warn('diag err', e);}
}
window.addEventListener('load', function(){ logState('load'); });
window.addEventListener('pageshow', function(e){ logState('pageshow '+(e.persisted?'persisted':'fresh')); });
setTimeout(function(){ logState('t+500'); }, 500);

console.log('=== DIAGNOSTIC END ===');
})();
