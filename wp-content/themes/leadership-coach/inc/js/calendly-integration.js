/**
 * Calendly Integration JavaScript
 * 
 * Enhanced Calendly integration with better error handling and user experience
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Calendly Integration Class
    class CalendlyIntegration {
        constructor() {
            this.init();
        }

        init() {
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.setupCalendly();
                });
            } else {
                this.setupCalendly();
            }
        }

        setupCalendly() {
            const widget = document.getElementById('calendly-widget');
            if (!widget) return;

            // Add loading state
            widget.classList.add('loading');
            
            // Create and load Calendly script
            this.loadCalendlyScript()
                .then(() => {
                    this.initializeWidget(widget);
                })
                .catch((error) => {
                    this.handleError(widget, error);
                });
        }

        loadCalendlyScript() {
            return new Promise((resolve, reject) => {
                // Check if Calendly is already loaded
                if (window.Calendly) {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://assets.calendly.com/assets/external/widget.js';
                script.async = true;
                
                script.onload = () => {
                    console.log('Calendly widget loaded successfully');
                    resolve();
                };
                
                script.onerror = () => {
                    const error = new Error('Failed to load Calendly widget script');
                    console.error(error);
                    reject(error);
                };
                
                // Set timeout for script loading
                setTimeout(() => {
                    if (!window.Calendly) {
                        reject(new Error('Calendly script loading timeout'));
                    }
                }, 10000); // 10 second timeout
                
                document.head.appendChild(script);
            });
        }

        initializeWidget(widget) {
            try {
                // Remove loading state
                widget.classList.remove('loading');
                
                const url = widget.getAttribute('data-url');
                if (!url) {
                    throw new Error('No Calendly URL provided');
                }

                // Initialize Calendly widget with options
                window.Calendly.initInlineWidget({
                    url: url,
                    parentElement: widget,
                    prefill: this.getPrefillData(),
                    utm: this.getUtmData()
                });

                // Set up event listeners
                this.setupEventListeners(widget);
                
            } catch (error) {
                this.handleError(widget, error);
            }
        }

        getPrefillData() {
            // You can customize prefill data here based on user data or URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            return {
                name: urlParams.get('name') || '',
                email: urlParams.get('email') || '',
                customAnswers: {
                    a1: urlParams.get('company') || ''
                }
            };
        }

        getUtmData() {
            const urlParams = new URLSearchParams(window.location.search);
            return {
                utmCampaign: urlParams.get('utm_campaign') || '',
                utmSource: urlParams.get('utm_source') || '',
                utmMedium: urlParams.get('utm_medium') || '',
                utmContent: urlParams.get('utm_content') || '',
                utmTerm: urlParams.get('utm_term') || ''
            };
        }

        setupEventListeners(widget) {
            // Listen for Calendly events
            window.addEventListener('message', (e) => {
                if (e.data.event && e.data.event.indexOf('calendly') === 0) {
                    this.handleCalendlyEvent(e.data.event, e.data.payload, widget);
                }
            });

            // Add retry button functionality
            this.setupRetryButton();
        }

        handleCalendlyEvent(event, payload, widget) {
            console.log('Calendly event:', event, payload);
            
            switch(event) {
                case 'calendly.event_scheduled':
                    this.showSuccessMessage(widget, payload);
                    this.trackConversion('event_scheduled', payload);
                    break;
                    
                case 'calendly.profile_page_viewed':
                    this.trackEvent('profile_viewed');
                    break;
                    
                case 'calendly.date_and_time_selected':
                    this.trackEvent('time_selected');
                    break;
                    
                case 'calendly.event_type_viewed':
                    this.trackEvent('event_type_viewed');
                    break;
            }
        }

        showSuccessMessage(widget, payload) {
            // Remove any existing success messages
            const existingMessages = document.querySelectorAll('.booking-message.success');
            existingMessages.forEach(msg => msg.remove());

            // Create success message
            const successMessage = document.createElement('div');
            successMessage.className = 'booking-message success';
            successMessage.innerHTML = `
                <p>🎉 Your appointment has been scheduled successfully! You will receive a confirmation email shortly.</p>
                ${payload && payload.event && payload.event.uri ? 
                    `<p><a href="${payload.event.uri}" target="_blank" rel="noopener">Add to Calendar</a></p>` : ''
                }
            `;
            
            // Insert before the widget
            widget.parentNode.insertBefore(successMessage, widget);
            
            // Scroll to success message
            successMessage.scrollIntoView({ 
                behavior: 'smooth',
                block: 'center'
            });

            // Auto-hide after 10 seconds
            setTimeout(() => {
                if (successMessage.parentNode) {
                    successMessage.remove();
                }
            }, 10000);
        }

        handleError(widget, error) {
            console.error('Calendly integration error:', error);
            
            widget.classList.remove('loading');
            
            const errorMessage = document.createElement('div');
            errorMessage.className = 'calendly-error';
            errorMessage.innerHTML = `
                <p>We're having trouble loading the scheduling system. Please try one of the options below:</p>
                <div style="margin-top: 1rem;">
                    <button class="btn-primary retry-calendly" style="margin-right: 1rem;">Try Again</button>
                    <a href="https://calendly.com/laraibsshaikh10/30min" target="_blank" rel="noopener" class="btn-secondary">
                        Open Calendly Directly ↗
                    </a>
                </div>
            `;
            
            widget.innerHTML = '';
            widget.appendChild(errorMessage);
            
            // Track error
            this.trackError(error);
        }

        setupRetryButton() {
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('retry-calendly')) {
                    e.preventDefault();
                    const widget = document.getElementById('calendly-widget');
                    if (widget) {
                        widget.innerHTML = '';
                        this.setupCalendly();
                    }
                }
            });
        }

        trackEvent(eventName, data = {}) {
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: 'calendly',
                    ...data
                });
            }

            // Facebook Pixel
            if (typeof fbq !== 'undefined') {
                fbq('track', 'CustomEvent', {
                    event_name: eventName,
                    ...data
                });
            }

            console.log('Tracked event:', eventName, data);
        }

        trackConversion(eventName, data = {}) {
            // Track conversion events with additional data
            this.trackEvent(eventName, data);
            
            // Additional conversion tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'conversion', {
                    'send_to': 'AW-XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXX', // Replace with your conversion ID
                    'value': 1.0,
                    'currency': 'USD'
                });
            }
        }

        trackError(error) {
            // Track errors for debugging
            if (typeof gtag !== 'undefined') {
                gtag('event', 'exception', {
                    description: error.message,
                    fatal: false
                });
            }

            console.log('Tracked error:', error.message);
        }
    }

    // Initialize Calendly Integration
    new CalendlyIntegration();

})(jQuery);
