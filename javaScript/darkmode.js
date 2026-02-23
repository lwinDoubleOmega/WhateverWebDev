// Dark Mode Toggle Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply the saved theme on page load
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }
    
    // Create and insert the toggle button in the nav if it doesn't exist
    const nav = document.querySelector('nav');
    if (nav && !document.querySelector('.theme-toggle')) {
        const toggleButton = document.createElement('button');
        toggleButton.className = 'theme-toggle';
        toggleButton.setAttribute('aria-label', 'Toggle dark mode');
        toggleButton.innerHTML = currentTheme === 'dark' 
            ? '<i class="fas fa-sun"></i>' 
            : '<i class="fas fa-moon"></i>';
        nav.appendChild(toggleButton);
        
        toggleButton.addEventListener('click', toggleTheme);
    }
});

function toggleTheme() {
    const body = document.body;
    const toggleButton = document.querySelector('.theme-toggle');
    
    // Toggle the dark-mode class
    body.classList.toggle('dark-mode');
    
    // Determine current theme and save to localStorage
    const newTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    localStorage.setItem('theme', newTheme);
    
    // Update button icon with animation
    if (toggleButton) {
        toggleButton.classList.add('icon-spin');
        toggleButton.innerHTML = newTheme === 'dark' 
            ? '<i class="fas fa-sun"></i>' 
            : '<i class="fas fa-moon"></i>';
        
        // Remove animation class after it completes
        setTimeout(() => {
            toggleButton.classList.remove('icon-spin');
        }, 600);
    }
}
