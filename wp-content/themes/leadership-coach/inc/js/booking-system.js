/**
 * Booking System JavaScript
 * 
 * Handles appointment booking form interactions and AJAX requests
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Booking system object
    var BookingSystem = {
        
        // Initialize the booking system
        init: function() {
            this.bindEvents();
            this.setupFormValidation();
        },
        
        // Bind event handlers
        bindEvents: function() {
            // Date change handler
            $('#appointment_date').on('change', this.handleDateChange.bind(this));
            
            // Form submission handler
            $('#appointment-booking-form').on('submit', this.handleFormSubmit.bind(this));
            
            // Service selection handler
            $('#service_type').on('change', this.handleServiceChange.bind(this));
        },
        
        // Handle date selection change
        handleDateChange: function(e) {
            var selectedDate = $(e.target).val();
            
            if (selectedDate) {
                this.loadAvailableTimeSlots(selectedDate);
                
                // Sync with calendar widget if available
                if (window.CalendarWidget) {
                    window.CalendarWidget.highlightFormDate(selectedDate);
                }
            } else {
                this.clearTimeSlots();
                
                // Reset calendar selection if available
                if (window.CalendarWidget) {
                    window.CalendarWidget.resetSelection();
                }
            }
        },
        
        // Handle service selection change
        handleServiceChange: function(e) {
            var selectedService = $(e.target).val();
            // Future enhancement: could filter available times based on service duration
        },
        
        // Load available time slots for selected date
        loadAvailableTimeSlots: function(date) {
            var $timeSelect = $('#appointment_time');
            var $loadingIndicator = $('#time-loading');
            
            // Show loading indicator
            $loadingIndicator.show();
            $timeSelect.prop('disabled', true);
            
            // Clear existing options
            $timeSelect.html('<option value="">' + bookingSystem.strings.loading + '</option>');
            
            // AJAX request to get available slots
            $.ajax({
                url: bookingSystem.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_available_time_slots',
                    date: date,
                    nonce: bookingSystem.nonce
                },
                success: function(response) {
                    if (response.success) {
                        BookingSystem.populateTimeSlots(response.data.slots);
                    } else {
                        BookingSystem.showError(response.data.message || bookingSystem.strings.errorLoadingSlots);
                    }
                },
                error: function() {
                    BookingSystem.showError(bookingSystem.strings.errorLoadingSlots);
                },
                complete: function() {
                    $loadingIndicator.hide();
                    $timeSelect.prop('disabled', false);
                }
            });
        },
        
        // Populate time slots dropdown
        populateTimeSlots: function(slots) {
            var $timeSelect = $('#appointment_time');
            
            // Clear existing options
            $timeSelect.empty();
            
            if (slots.length === 0) {
                $timeSelect.html('<option value="">' + bookingSystem.strings.noSlotsAvailable + '</option>');
                return;
            }
            
            // Add default option
            $timeSelect.append('<option value="">' + bookingSystem.strings.selectTime + '</option>');
            
            // Add available slots
            $.each(slots, function(index, slot) {
                var timeFormatted = BookingSystem.formatTime(slot);
                $timeSelect.append('<option value="' + slot + '">' + timeFormatted + '</option>');
            });
        },
        
        // Clear time slots
        clearTimeSlots: function() {
            $('#appointment_time').html('<option value="">' + bookingSystem.strings.selectDateFirst + '</option>');
        },
        
        // Format time for display (convert 24h to 12h format)
        formatTime: function(time24) {
            var timeParts = time24.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = timeParts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12; // 0 should be 12
            
            return hours + ':' + minutes + ' ' + ampm;
        },
        
        // Handle form submission
        handleFormSubmit: function(e) {
            e.preventDefault();
            
            var $form = $(e.target);
            var $submitBtn = $form.find('.booking-submit-btn');
            var $btnText = $submitBtn.find('.btn-text');
            var $btnLoading = $submitBtn.find('.btn-loading');
            
            // Validate form
            if (!this.validateForm($form)) {
                return false;
            }
            
            // Show loading state
            $submitBtn.prop('disabled', true);
            $btnText.hide();
            $btnLoading.show();
            
            // Prepare form data
            var formData = $form.serialize();
            formData += '&action=book_appointment';
            
            // Submit booking request
            $.ajax({
                url: bookingSystem.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        BookingSystem.showSuccess(response.data.message);
                        BookingSystem.resetForm($form);
                        // Refresh available slots
                        var selectedDate = $('#appointment_date').val();
                        if (selectedDate) {
                            BookingSystem.loadAvailableTimeSlots(selectedDate);
                        }
                    } else {
                        BookingSystem.showError(response.data.message || bookingSystem.strings.bookingError);
                    }
                },
                error: function() {
                    BookingSystem.showError(bookingSystem.strings.bookingError);
                },
                complete: function() {
                    // Reset button state
                    $submitBtn.prop('disabled', false);
                    $btnText.show();
                    $btnLoading.hide();
                }
            });
        },
        
        // Setup form validation
        setupFormValidation: function() {
            // Real-time validation for required fields
            $('#appointment-booking-form input[required], #appointment-booking-form select[required]').on('blur', function() {
                BookingSystem.validateField($(this));
            });
            
            // Email validation
            $('#client_email').on('blur', function() {
                BookingSystem.validateEmail($(this));
            });
            
            // Phone validation
            $('#client_phone').on('input', function() {
                BookingSystem.formatPhoneNumber($(this));
            });
        },
        
        // Validate individual field
        validateField: function($field) {
            var isValid = true;
            var value = $field.val().trim();
            
            // Remove existing error styling
            $field.removeClass('error');
            $field.next('.field-error').remove();
            
            // Check if required field is empty
            if ($field.prop('required') && !value) {
                isValid = false;
                this.showFieldError($field, bookingSystem.strings.fieldRequired);
            }
            
            return isValid;
        },
        
        // Validate email field
        validateEmail: function($field) {
            var email = $field.val().trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            $field.removeClass('error');
            $field.next('.field-error').remove();
            
            if (email && !emailRegex.test(email)) {
                this.showFieldError($field, bookingSystem.strings.invalidEmail);
                return false;
            }
            
            return true;
        },
        
        // Format phone number input
        formatPhoneNumber: function($field) {
            var value = $field.val().replace(/\D/g, '');
            var formattedValue = '';
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    formattedValue = value;
                } else if (value.length <= 6) {
                    formattedValue = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    formattedValue = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            
            $field.val(formattedValue);
        },
        
        // Show field error
        showFieldError: function($field, message) {
            $field.addClass('error');
            $field.after('<div class="field-error">' + message + '</div>');
        },
        
        // Validate entire form
        validateForm: function($form) {
            var isValid = true;
            
            // Clear previous error messages
            $('.form-messages').hide();
            
            // Validate visible required fields
            $form.find('input[required]:visible, select[required]:visible, textarea[required]:visible').each(function() {
                if (!BookingSystem.validateField($(this))) {
                    isValid = false;
                }
            });
            
            // Validate hidden date/time fields
            var selectedDate = $('#appointment_date').val();
            var selectedTime = $('#appointment_time').val();
            
            if (!selectedDate) {
                this.showError(bookingSystem.strings.selectDateFirst || 'Please select a date from the calendar.');
                isValid = false;
            }
            
            if (!selectedTime) {
                this.showError(bookingSystem.strings.selectTimeFirst || 'Please select a time slot.');
                isValid = false;
            }
            
            // Validate email
            if (!this.validateEmail($('#client_email'))) {
                isValid = false;
            }
            
            // Check if date is in the future
            if (selectedDate) {
                var appointmentDate = new Date(selectedDate);
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (appointmentDate <= today) {
                    this.showError(bookingSystem.strings.dateInFuture);
                    isValid = false;
                }
            }
            
            return isValid;
        },
        
        // Show success message
        showSuccess: function(message) {
            var $messages = $('#booking-messages');
            $messages.removeClass('error').addClass('success');
            $messages.find('.message-content').html(message);
            $messages.show();
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $messages.offset().top - 100
            }, 500);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $messages.fadeOut();
            }, 5000);
            
            // Refresh calendar if available
            if (window.CalendarWidget) {
                window.CalendarWidget.loadMonthAvailability();
                window.CalendarWidget.resetSelection();
            }
        },
        
        // Show error message
        showError: function(message) {
            var $messages = $('#booking-messages');
            $messages.removeClass('success').addClass('error');
            $messages.find('.message-content').html(message);
            $messages.show();
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $messages.offset().top - 100
            }, 500);
        },
        
        // Reset form
        resetForm: function($form) {
            $form[0].reset();
            $form.find('.error').removeClass('error');
            $form.find('.field-error').remove();
            this.clearTimeSlots();
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Only initialize on pages with booking form
        if ($('#appointment-booking-form').length) {
            BookingSystem.init();
        }
    });
    
})(jQuery);