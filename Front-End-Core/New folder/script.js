// Back to Top Button
const backToTopButton = document.createElement('a');
backToTopButton.href = '#';
backToTopButton.className = 'back-to-top';
backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
document.body.appendChild(backToTopButton);

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.classList.add('visible');
    } else {
        backToTopButton.classList.remove('visible');
    }
});

backToTopButton.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Loading Spinner
const loadingSpinner = document.createElement('div');
loadingSpinner.className = 'loading-spinner';
loadingSpinner.innerHTML = '<div class="spinner"></div>';
document.body.appendChild(loadingSpinner);

function showLoading() {
    loadingSpinner.classList.add('visible');
}

function hideLoading() {
    loadingSpinner.classList.remove('visible');
}



// Form Validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            // Add error message if not exists
            let errorMessage = input.nextElementSibling;
            if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                input.parentNode.insertBefore(errorMessage, input.nextSibling);
            }
            errorMessage.textContent = 'This field is required';
        } else {
            input.classList.remove('error');
            const errorMessage = input.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.remove();
            }
        }
    });
    
    return isValid;
}

// Add form validation to all forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!validateForm(form)) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        }
    });
});

// Smooth Scroll for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading spinner to form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
        showLoading();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navContainer = document.querySelector('.nav-container');
    const navLinks = document.querySelectorAll('.nav-links a');

    // Toggle mobile menu
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        navContainer.classList.toggle('active');
        this.setAttribute('aria-expanded', 
            this.classList.contains('active') ? 'true' : 'false'
        );
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = navContainer.contains(event.target) || 
                            hamburger.contains(event.target);
        
        if (!isClickInside && navContainer.classList.contains('active')) {
            hamburger.classList.remove('active');
            navContainer.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });

    // Close mobile menu when clicking on a link
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navContainer.classList.contains('active')) {
                hamburger.classList.remove('active');
                navContainer.classList.remove('active');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && navContainer.classList.contains('active')) {
            hamburger.classList.remove('active');
            navContainer.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });
});