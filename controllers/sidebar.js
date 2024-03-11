// Function to toggle sidebar collapse
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('mainContent').classList.toggle('content-collapsed');

    // Store the sidebar collapse state in local storage
    var isCollapsed = document.getElementById('sidebar').classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

// Check if the sidebar state is stored in local storage and apply it
window.addEventListener('DOMContentLoaded', function () {
    var isCollapsed = localStorage.getItem('sidebarCollapsed');
    if (isCollapsed === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
        document.getElementById('mainContent').classList.add('content-collapsed');
    }
});

// Add event listener to the toggle button
document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
