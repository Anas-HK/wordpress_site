/**
 * Calendar Widget JavaScript
 * 
 * Handles calendar display and appointment selection
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Calendar widget object
    var CalendarWidget = {
        
        // Current calendar state
        currentDate: new Date(),
        selectedDate: null,
        availabilityData: {},
        
        // Initialize the calendar widget
        init: function() {
            this.bindEvents();
            this.renderCalendar();
            this.loadMonthAvailability();
        },
        
        // Bind event handlers
        bindEvents: function() {
            // Navigation buttons
            $('.prev-month').on('click', this.previousMonth.bind(this));
            $('.next-month').on('click', this.nextMonth.bind(this));
            
            // Calendar day clicks (delegated event)
            $('#calendar-days').on('click', '.calendar-day.available', this.selectDate.bind(this));
            
            // Time slot clicks (delegated event)
            $('#time-slots-grid').on('click', '.time-slot.available', this.selectTimeSlot.bind(this));
        },
        
        // Navigate to previous month
        previousMonth: function() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
            this.loadMonthAvailability();
        },
        
        // Navigate to next month
        nextMonth: function() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
            this.loadMonthAvailability();
        },
        
        // Render the calendar
        renderCalendar: function() {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth();
            
            // Update calendar title
            var monthNames = [
                calendarWidget.strings.january, calendarWidget.strings.february, calendarWidget.strings.march,
                calendarWidget.strings.april, calendarWidget.strings.may, calendarWidget.strings.june,
                calendarWidget.strings.july, calendarWidget.strings.august, calendarWidget.strings.september,
                calendarWidget.strings.october, calendarWidget.strings.november, calendarWidget.strings.december
            ];
            
            $('#calendar-title').text(monthNames[month] + ' ' + year);
            
            // Get first day of month and number of days
            var firstDay = new Date(year, month, 1);
            var lastDay = new Date(year, month + 1, 0);
            var daysInMonth = lastDay.getDate();
            var startingDayOfWeek = firstDay.getDay();
            
            // Clear calendar days
            var $calendarDays = $('#calendar-days');
            $calendarDays.empty();
            
            // Add empty cells for days before the first day of the month
            for (var i = 0; i < startingDayOfWeek; i++) {
                $calendarDays.append('<div class="calendar-day empty"></div>');
            }
            
            // Add days of the month
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            for (var day = 1; day <= daysInMonth; day++) {
                var currentDay = new Date(year, month, day);
                var dateString = this.formatDate(currentDay);
                var dayClass = 'calendar-day';
                
                // Add classes based on availability and date
                if (currentDay < today) {
                    dayClass += ' past';
                } else if (this.availabilityData[dateString]) {
                    var availability = this.availabilityData[dateString];
                    if (availability.available_slots > 0) {
                        dayClass += ' available';
                    } else {
                        dayClass += ' booked';
                    }
                } else {
                    dayClass += ' unavailable';
                }
                
                // Mark selected date
                if (this.selectedDate && this.selectedDate.getTime() === currentDay.getTime()) {
                    dayClass += ' selected';
                }
                
                var $dayElement = $('<div class="' + dayClass + '" data-date="' + dateString + '">' + day + '</div>');
                
                // Add availability indicator
                if (currentDay >= today && this.availabilityData[dateString]) {
                    var availability = this.availabilityData[dateString];
                    var indicator = '<span class="availability-indicator">' + availability.available_slots + '</span>';
                    $dayElement.append(indicator);
                }
                
                $calendarDays.append($dayElement);
            }
        },
        
        // Load availability data for current month
        loadMonthAvailability: function() {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth() + 1; // JavaScript months are 0-based
            
            $.ajax({
                url: calendarWidget.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_month_availability',
                    year: year,
                    month: month,
                    nonce: calendarWidget.nonce
                },
                success: function(response) {
                    if (response.success) {
                        CalendarWidget.availabilityData = response.data.availability;
                        CalendarWidget.renderCalendar();
                    }
                },
                error: function() {
                    console.error('Failed to load availability data');
                }
            });
        },
        
        // Select a date
        selectDate: function(e) {
            var $dayElement = $(e.currentTarget);
            var dateString = $dayElement.data('date');
            
            if (!dateString) return;
            
            // Parse date
            this.selectedDate = new Date(dateString + 'T00:00:00');
            
            // Update visual selection
            $('.calendar-day').removeClass('selected');
            $dayElement.addClass('selected');
            
            // Update form field
            $('#appointment_date').val(dateString);
            
            // Clear time selection
            $('#appointment_time').val('');
            $('.time-slot').removeClass('selected');
            
            // Load time slots for selected date
            this.loadTimeSlots(dateString);
            
            // Show time slots section
            $('#time-slots-display').show();
            $('#selected-date-display').text(this.formatDateForDisplay(this.selectedDate));
            
            // Update selected appointment display
            this.updateSelectedAppointmentDisplay();
        },
        
        // Load time slots for selected date
        loadTimeSlots: function(date) {
            var $timeSlotsGrid = $('#time-slots-grid');
            var $noSlotsMessage = $('#no-slots-message');
            
            // Show loading state
            $timeSlotsGrid.html('<div class="loading-slots">' + calendarWidget.strings.loadingSlots + '</div>');
            $noSlotsMessage.hide();
            
            $.ajax({
                url: calendarWidget.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_available_time_slots',
                    date: date,
                    nonce: calendarWidget.nonce
                },
                success: function(response) {
                    if (response.success) {
                        CalendarWidget.renderTimeSlots(response.data.slots);
                    } else {
                        CalendarWidget.showNoSlots();
                    }
                },
                error: function() {
                    CalendarWidget.showNoSlots();
                }
            });
        },
        
        // Render time slots
        renderTimeSlots: function(slots) {
            var $timeSlotsGrid = $('#time-slots-grid');
            var $noSlotsMessage = $('#no-slots-message');
            
            $timeSlotsGrid.empty();
            
            if (slots.length === 0) {
                this.showNoSlots();
                return;
            }
            
            $noSlotsMessage.hide();
            
            // Create time slot buttons
            $.each(slots, function(index, slot) {
                var timeFormatted = CalendarWidget.formatTime(slot);
                var $timeSlot = $('<button type="button" class="time-slot available" data-time="' + slot + '">' + timeFormatted + '</button>');
                $timeSlotsGrid.append($timeSlot);
            });
        },
        
        // Show no slots message
        showNoSlots: function() {
            $('#time-slots-grid').empty();
            $('#no-slots-message').show();
        },
        
        // Select a time slot
        selectTimeSlot: function(e) {
            var $timeSlot = $(e.currentTarget);
            var timeValue = $timeSlot.data('time');
            
            // Update visual selection
            $('.time-slot').removeClass('selected');
            $timeSlot.addClass('selected');
            
            // Update form field
            $('#appointment_time').val(timeValue);
            
            // Update selected appointment display
            this.updateSelectedAppointmentDisplay();
            
            // Scroll to booking form
            $('html, body').animate({
                scrollTop: $('#appointment-booking-form').offset().top - 100
            }, 500);
        },
        
        // Format date for API (YYYY-MM-DD)
        formatDate: function(date) {
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        },
        
        // Format date for display
        formatDateForDisplay: function(date) {
            var options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return date.toLocaleDateString(calendarWidget.locale, options);
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
        
        // Highlight date in calendar when form date changes
        highlightFormDate: function(dateString) {
            if (!dateString) return;
            
            var selectedDate = new Date(dateString + 'T00:00:00');
            
            // Check if date is in current month view
            var currentMonth = this.currentDate.getMonth();
            var currentYear = this.currentDate.getFullYear();
            
            if (selectedDate.getMonth() !== currentMonth || selectedDate.getFullYear() !== currentYear) {
                // Navigate to the month containing the selected date
                this.currentDate = new Date(selectedDate);
                this.renderCalendar();
                this.loadMonthAvailability();
            }
            
            // Update selected date
            this.selectedDate = selectedDate;
            
            // Update visual selection
            $('.calendar-day').removeClass('selected');
            $('.calendar-day[data-date="' + dateString + '"]').addClass('selected');
            
            // Load time slots
            this.loadTimeSlots(dateString);
            $('#time-slots-display').show();
            $('#selected-date-display').text(this.formatDateForDisplay(selectedDate));
        },
        
        // Reset calendar selection
        resetSelection: function() {
            this.selectedDate = null;
            $('.calendar-day').removeClass('selected');
            $('.time-slot').removeClass('selected');
            $('#time-slots-display').hide();
            $('#appointment_date').val('');
            $('#appointment_time').val('');
            $('#selected-appointment-display').hide();
        },
        
        // Update selected appointment display
        updateSelectedAppointmentDisplay: function() {
            var selectedDate = $('#appointment_date').val();
            var selectedTime = $('#appointment_time').val();
            
            if (selectedDate && selectedTime) {
                var dateObj = new Date(selectedDate + 'T00:00:00');
                var formattedDate = this.formatDateForDisplay(dateObj);
                var formattedTime = this.formatTime(selectedTime);
                
                $('#selected-date-text').text(formattedDate);
                $('#selected-time-text').text(formattedTime);
                $('#selected-appointment-display').show();
            } else {
                $('#selected-appointment-display').hide();
            }
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Only initialize on pages with calendar widget
        if ($('#appointment-calendar').length) {
            CalendarWidget.init();
            
            // Listen for form date changes to sync with calendar
            $('#appointment_date').on('change', function() {
                var dateValue = $(this).val();
                if (dateValue) {
                    CalendarWidget.highlightFormDate(dateValue);
                } else {
                    CalendarWidget.resetSelection();
                }
            });
        }
    });
    
    // Expose CalendarWidget globally for integration with booking system
    window.CalendarWidget = CalendarWidget;
    
})(jQuery);