import './bootstrap';
import { initNotificationSystem } from './components/notification';
import { initModuleHeaderAnim } from './components/module_header';
import { initModuleScripts } from './components/module_scripts';

// Initialize when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize notification system
    initNotificationSystem();

    // Initialize module header animation
    // initModuleHeaderAnim();
    
    // Initialize module scripts
    initModuleScripts();

    // let sideBar = document.getElementById('sidebar');
    // let sideBarToggleBtn = document.getElementById('sidebarToggle');
    // let sideBarState = sessionStorage.getItem('sidebarState') || 'expanded';

    // // Apply saved sidebar state on page load
    // if (sideBarState === 'collapsed') {
    //     document.body.classList.add('sidebar-collapsed');
    // }

    // // Toggle sidebar
    // sideBarToggleBtn.addEventListener('click', () => {
    //     sideBar.classList.toggle('collapsed');
    //     sideBarState = sideBarState === 'expanded' ? 'collapsed' : 'expanded';
    //     sessionStorage.setItem('sidebarState', sideBarState);
    // });
});