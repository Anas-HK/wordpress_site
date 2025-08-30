/**
 * Contact Form Enhancement and Validation
 * 
 * Provides client-side validation, AJAX submission, and enhanced UX
 * for the contact form on the Contact page.
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');
        if (!contactForm) return;

        const submitButton = contactForm.querySelector('.contact-submit');
        const formGroups = contactForm.querySelectorAll('.form-group');

        // Form validation rules
        const validationRules = {
            contact_name: {
                required: true,
                minLength: 2,
                pattern: /^[a-zA-Z\s'-]+$/,
                message: 'Please enter a valid name (letters, spaces, hyphens, and apostrophes only)'
            },
            contact_email: {
                required: true,
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                message: 'Please enter a valid email address'
            },
            contact_phone: {
                required: false,
                pattern: /^[\+]?[1-9][\d]{0,15}$/,
                message: 'Please enter a valid phone number'
            },
            contact_message: {
                required: true,
                minLength: 10,
                maxLength: 1000,
                message: 'Message must be between 10 and 1000 characters'
            }
        };

        /**
         * Validate individual field
         */
        function validateField(field) {
            const fieldName = field.name;
            const fieldValue = field.value.trim();
            const rules = validationRules[fieldName];
            
            if (!rules) return true;

            // Remove existing error styling
            field.classList.remove('error');
            const existingError = field.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }

            // Check required fields
            if (rules.required && !fieldValue) {
                showFieldError(field, 'This field is required');
                return false;
            }

            // Skip other validations if field is empty and not required
            if (!fieldValue && !rules.required) {
                return true;
            }

            // Check minimum length
            if (rules.minLength && fieldValue.length < rules.minLength) {
                showFieldError(field, `Minimum ${rules.minLength} characters required`);
                return false;
            }

            // Check maximum length
            if (rules.maxLength && fieldValue.length > rules.maxLength) {
                showFieldError(field, `Maximum ${rules.maxLength} characters allowed`);
                return false;
            }

            // Check pattern
            if (rules.pattern && !rules.pattern.test(fieldValue)) {
                showFieldError(field, rules.message);
                return false;
            }

            return true;
        }

        /**
         * Show field error
         */
        function showFieldError(field, message) {
            field.classList.add('error');
            
            const errorElement = document.createElement('span');
            errorElement.className = 'error-message';
            errorElement.textContent = message;
            
            field.parentNode.appendChild(errorElement);
        }

        /**
         * Validate entire form
         */
        function validateForm() {
            let isValid = true;
            const fields = contactForm.querySelectorAll('input[required], textarea[required], input[name="contact_phone"]');
            
            fields.forEach(function(field) {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            return isValid;
        }

        /**
         * Show loading state
         */
        function showLoadingState() {
            submitButton.classList.add('loading');
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
        }

        /**
         * Hide loading state
         */
        function hideLoadingState() {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
            submitButton.textContent = 'Send Message';
        }

        /**
         * Show form message
         */
        function showFormMessage(type, message) {
            // Remove existing messages
            const existingMessages = contactForm.querySelectorAll('.contact-message');
            existingMessages.forEach(function(msg) {
                msg.remove();
            });

            // Create new message
            const messageElement = document.createElement('div');
            messageElement.className = `contact-message ${type}`;
            messageElement.innerHTML = `<p>${message}</p>`;

            // Insert before form
            contactForm.parentNode.insertBefore(messageElement, contactForm);

            // Scroll to message
            messageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(function() {
                    messageElement.style.opacity = '0';
                    setTimeout(function() {
                        if (messageElement.parentNode) {
                            messageElement.remove();
                        }
                    }, 300);
                }, 5000);
            }
        }

        /**
         * Handle form submission
         */
        function handleFormSubmission(event) {
            event.preventDefault();

            // Validate form
            if (!validateForm()) {
                showFormMessage('error', 'Please correct the errors above and try again.');
                return;
            }

            // Check honeypot
            const honeypot = contactForm.querySelector('input[name="website"]');
            if (honeypot && honeypot.value) {
                showFormMessage('error', 'Spam detected. Please try again.');
                return;
            }

            showLoadingState();

            // Prepare form data
            const formData = new FormData(contactForm);

            // Submit form via fetch API
            fetch(contactForm.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok');
            })
            .then(function(data) {
                hideLoadingState();
                
                // Check if response contains success indicator
                if (data.includes('contact=success') || data.includes('success')) {
                    showFormMessage('success', 'Thank you for your message! We will get back to you soon.');
                    contactForm.reset();
                } else {
                    showFormMessage('error', 'Sorry, there was an error sending your message. Please try again.');
                }
            })
            .catch(function(error) {
                hideLoadingState();
                console.error('Form submission error:', error);
                showFormMessage('error', 'Sorry, there was an error sending your message. Please try again.');
            });
        }

        // Add real-time validation
        const formFields = contactForm.querySelectorAll('input, textarea, select');
        formFields.forEach(function(field) {
            // Validate on blur
            field.addEventListener('blur', function() {
                validateField(this);
            });

            // Clear errors on input
            field.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                    const errorMessage = this.parentNode.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            });
        });

        // Handle form submission
        contactForm.addEventListener('submit', handleFormSubmission);

        // Character counter for message field
        const messageField = contactForm.querySelector('#contact_message');
        if (messageField) {
            const maxLength = validationRules.contact_message.maxLength;
            
            // Create character counter
            const counterElement = document.createElement('div');
            counterElement.className = 'character-counter';
            counterElement.style.cssText = 'font-size: 0.85em; color: #666; text-align: right; margin-top: 5px;';
            
            messageField.parentNode.appendChild(counterElement);
            
            function updateCounter() {
                const currentLength = messageField.value.length;
                counterElement.textContent = `${currentLength}/${maxLength} characters`;
                
                if (currentLength > maxLength * 0.9) {
                    counterElement.style.color = '#e53e3e';
                } else if (currentLength > maxLength * 0.8) {
                    counterElement.style.color = '#ff9800';
                } else {
                    counterElement.style.color = '#666';
                }
            }
            
            messageField.addEventListener('input', updateCounter);
            updateCounter(); // Initial count
        }

        // Enhanced accessibility
        const requiredFields = contactForm.querySelectorAll('[required]');
        requiredFields.forEach(function(field) {
            field.setAttribute('aria-required', 'true');
        });

        // Auto-focus first field on page load (if no hash in URL)
        if (!window.location.hash) {
            const firstField = contactForm.querySelector('input[type="text"], input[type="email"]');
            if (firstField) {
                setTimeout(function() {
                    firstField.focus();
                }, 100);
            }
        }

        console.log('Contact form enhancement initialized');
    });

})();