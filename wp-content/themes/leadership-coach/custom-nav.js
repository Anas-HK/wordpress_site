// Custom Navigation Injection
document.addEventListener('DOMContentLoaded', function() {
    // Create the navigation HTML
    const navHTML = `
        <nav class="custom-simple-nav">
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="${window.location.origin}">Home</a></li>
                    <li><a href="${window.location.origin}/about">About</a></li>
                    <li><a href="${window.location.origin}/services">Services</a></li>
                    <li><a href="${window.location.origin}/calendar">Calendar</a></li>
                    <li><a href="${window.location.origin}/contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    `;
    
    // Find the header and inject navigation after it
    const header = document.querySelector('.site-header');
    if (header) {
        header.insertAdjacentHTML('afterend', navHTML);
    } else {
        // Fallback: inject at the beginning of body
        document.body.insertAdjacentHTML('afterbegin', navHTML);
    }
    
    // Add active state for current page
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.custom-simple-nav .nav-links a');
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (currentPath === linkPath || (currentPath === '/' && linkPath === '/')) {
            link.style.color = 'var(--primary-purple, #9B5DE5)';
            link.style.background = 'rgba(155, 93, 229, 0.1)';
        }
    });
});