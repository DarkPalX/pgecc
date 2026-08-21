export function initModuleHeaderAnim() {
    const header = document.querySelector('.module-header');
    if (!header) return;

    // Create a "sentinel" element or just watch the header's parent
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            console.log('Header intersection ratio:', entry.intersectionRatio);
            
            // When the header is NO LONGER intersecting the top of the viewport
            if (!entry.isIntersecting) {
                header.classList.add('fixed', 'top-0', 'left-0', 'shadow-md', 'bg-white/90');
            } else {
                header.classList.remove('fixed', 'top-0', 'left-0', 'shadow-md', 'bg-white/90');
            }
        });
    }, { 
        threshold: [1], // Trigger when 100% of the element is visible/hidden
        rootMargin: '-1px 0px 0px 0px' // Trigger exactly at the top edge
    });

    observer.observe(header);
}