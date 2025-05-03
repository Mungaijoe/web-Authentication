document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            let isValid = true;
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            
            // Reset previous error messages
            clearErrors();
            
            // Validate username
            if (username.value.trim() === '') {
                displayError(username, 'Username is required');
                isValid = false;
            }
            
            // Validate password
            if (password.value.trim() === '') {
                displayError(password, 'Password is required');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
    
    // Registration form validation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            let isValid = true;
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            // Reset previous error messages
            clearErrors();
            
            // Validate username
            if (username.value.trim() === '') {
                displayError(username, 'Username is required');
                isValid = false;
            } else if (username.value.length < 3) {
                displayError(username, 'Username must be at least 3 characters');
                isValid = false;
            }
            
            // Validate email
            if (email.value.trim() === '') {
                displayError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                displayError(email, 'Please enter a valid email address');
                isValid = false;
            }
            
            // Validate password
            if (password.value.trim() === '') {
                displayError(password, 'Password is required');
                isValid = false;
            } else if (password.value.length < 8) {
                displayError(password, 'Password must be at least 8 characters');
                isValid = false;
            }
            
            // Validate confirm password
            if (confirmPassword.value.trim() === '') {
                displayError(confirmPassword, 'Please confirm your password');
                isValid = false;
            } else if (password.value !== confirmPassword.value) {
                displayError(confirmPassword, 'Passwords do not match');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
    
    // Form submission validation
    const submissionForm = document.getElementById('submission-form');
    if (submissionForm) {
        submissionForm.addEventListener('submit', function(event) {
            let isValid = true;
            const title = document.getElementById('title');
            const description = document.getElementById('description');
            
            // Reset previous error messages
            clearErrors();
            
            // Validate title
            if (title.value.trim() === '') {
                displayError(title, 'Title is required');
                isValid = false;
            }
            
            // Validate description
            if (description.value.trim() === '') {
                displayError(description, 'Description is required');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
    
    // Helper functions
    function displayError(element, message) {
        element.classList.add('is-invalid');
        const errorElement = document.createElement('span');
        errorElement.className = 'invalid-feedback';
        errorElement.textContent = message;
        element.parentNode.appendChild(errorElement);
    }
    
    function clearErrors() {
        const invalidInputs = document.querySelectorAll('.is-invalid');
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        
        invalidInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        errorMessages.forEach(message => {
            message.remove();
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
