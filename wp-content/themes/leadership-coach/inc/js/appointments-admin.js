/**
 * Appointments Admin JavaScript
 * 
 * Handles admin interface interactions for appointment management
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    var AppointmentsAdmin = {
        
        // Initialize admin functionality
        init: function() {
            this.bindEvents();
            this.initModals();
            this.initCalendar();
        },
        
        // Bind event handlers
        bindEvents: function() {
            // Status update handlers
            $(document).on('click', '.edit-appointment', this.handleEditAppointment.bind(this));
            $(document).on('click', '.reschedule-appointment', this.handleRescheduleAppointment.bind(this));
            $(document).on('click', '.cancel-appointment', this.handleCancelAppointment.bind(this));
            $(document).on('click', '.delete-appointment', this.handleDeleteAppointment.bind(this));
            
            // Modal handlers
            $(document).on('click', '.modal-close', this.closeModal.bind(this));
            $(document).on('click', '.appointment-modal', function(e) {
                if (e.target === this) {
                    AppointmentsAdmin.closeModal();
                }
            });
            
            // Form handlers
            $(document).on('submit', '#edit-appointment-form', this.handleEditSubmit.bind(this));
            $(document).on('submit', '#reschedule-appointment-form', this.handleRescheduleSubmit.bind(this));
            
            // Date change handler for reschedule form
            $(document).on('change', '#reschedule-date', this.handleRescheduleDateChange.bind(this));
            
            // Select all checkbox
            $(document).on('change', '#cb-select-all-1', function() {
                $('input[name="appointment_ids[]"]').prop('checked', this.checked);
            });
            
            // Calendar navigation
            $(document).on('click', '#prev-month', this.handlePrevMonth.bind(this));
            $(document).on('click', '#next-month', this.handleNextMonth.bind(this));
            $(document).on('click', '.calendar-day', this.handleDayClick.bind(this));
        },
        
        // Initialize modals
        initModals: function() {
            // Create modal overlay if it doesn't exist
            if (!$('.modal-overlay').length) {
                $('body').append('<div class="modal-overlay"></div>');
            }
        },
        
        // Initialize calendar
        initCalendar: function() {
            if ($('#appointment-calendar-admin').length) {
                this.currentDate = new Date();
                this.renderCalendar();
            }
        },
        
        // Handle edit appointment
        handleEditAppointment: function(e) {
            e.preventDefault();
            
            var appointmentId = $(e.target).data('id');
            this.loadAppointmentData(appointmentId, function(data) {
                AppointmentsAdmin.populateEditForm(data);
                AppointmentsAdmin.showModal('#edit-appointment-modal');
            });
        },
        
        // Handle reschedule appointment
        handleRescheduleAppointment: function(e) {
            e.preventDefault();
            
            var appointmentId = $(e.target).data('id');
            $('#reschedule-appointment-id').val(appointmentId);
            this.showModal('#reschedule-appointment-modal');
        },
        
        // Handle cancel appointment
        handleCancelAppointment: function(e) {
            e.preventDefault();
            
            if (!confirm(appointmentsAdmin.strings.confirmCancel)) {
                return;
            }
            
            var appointmentId = $(e.target).data('id');
            this.updateAppointmentStatus(appointmentId, 'cancelled');
        },
        
        // Handle delete appointment
        handleDeleteAppointment: function(e) {
            e.preventDefault();
            
            if (!confirm(appointmentsAdmin.strings.confirmDelete)) {
                return;
            }
            
            var appointmentId = $(e.target).data('id');
            this.deleteAppointment(appointmentId);
        },
        
        // Handle edit form submission
        handleEditSubmit: function(e) {
            e.preventDefault();
            
            var $form = $(e.target);
            var formData = $form.serialize();
            formData += '&action=update_appointment&nonce=' + appointmentsAdmin.nonce;
            
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.showNotice(response.data.message, 'success');
                        AppointmentsAdmin.closeModal();
                        location.reload(); // Refresh the page to show updated data
                    } else {
                        AppointmentsAdmin.showNotice(response.data.message, 'error');
                    }
                },
                error: function() {
                    AppointmentsAdmin.showNotice(appointmentsAdmin.strings.error, 'error');
                }
            });
        },
        
        // Handle reschedule form submission
        handleRescheduleSubmit: function(e) {
            e.preventDefault();
            
            var $form = $(e.target);
            var formData = $form.serialize();
            formData += '&action=reschedule_appointment&nonce=' + appointmentsAdmin.nonce;
            
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.showNotice(response.data.message, 'success');
                        AppointmentsAdmin.closeModal();
                        location.reload(); // Refresh the page to show updated data
                    } else {
                        AppointmentsAdmin.showNotice(response.data.message, 'error');
                    }
                },
                error: function() {
                    AppointmentsAdmin.showNotice(appointmentsAdmin.strings.error, 'error');
                }
            });
        },
        
        // Handle reschedule date change
        handleRescheduleDateChange: function(e) {
            var selectedDate = $(e.target).val();
            
            if (selectedDate) {
                this.loadAvailableTimeSlotsAdmin(selectedDate, '#reschedule-time');
            }
        },
        
        // Load appointment data
        loadAppointmentData: function(appointmentId, callback) {
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_appointment_data',
                    appointment_id: appointmentId,
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success && callback) {
                        callback(response.data);
                    }
                },
                error: function() {
                    AppointmentsAdmin.showNotice(appointmentsAdmin.strings.error, 'error');
                }
            });
        },
        
        // Populate edit form with appointment data
        populateEditForm: function(data) {
            $('#edit-appointment-id').val(data.id);
            $('#edit-client-name').val(data.client_name);
            $('#edit-client-email').val(data.client_email);
            $('#edit-client-phone').val(data.client_phone);
            $('#edit-service-type').val(data.service_type);
            $('#edit-appointment-date').val(data.appointment_date);
            $('#edit-appointment-time').val(data.appointment_time);
            $('#edit-status').val(data.status);
            $('#edit-notes').val(data.notes);
        },
        
        // Update appointment status
        updateAppointmentStatus: function(appointmentId, status) {
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'update_appointment_status',
                    appointment_id: appointmentId,
                    status: status,
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.showNotice(response.data.message, 'success');
                        location.reload(); // Refresh the page to show updated data
                    } else {
                        AppointmentsAdmin.showNotice(response.data.message, 'error');
                    }
                },
                error: function() {
                    AppointmentsAdmin.showNotice(appointmentsAdmin.strings.error, 'error');
                }
            });
        },
        
        // Delete appointment
        deleteAppointment: function(appointmentId) {
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'delete_appointment',
                    appointment_id: appointmentId,
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.showNotice(response.data.message, 'success');
                        $('tr[data-appointment-id="' + appointmentId + '"]').fadeOut(function() {
                            $(this).remove();
                        });
                    } else {
                        AppointmentsAdmin.showNotice(response.data.message, 'error');
                    }
                },
                error: function() {
                    AppointmentsAdmin.showNotice(appointmentsAdmin.strings.error, 'error');
                }
            });
        },
        
        // Load available time slots for admin
        loadAvailableTimeSlotsAdmin: function(date, targetSelector) {
            var $timeSelect = $(targetSelector);
            
            $timeSelect.html('<option value="">Loading...</option>').prop('disabled', true);
            
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_available_time_slots',
                    date: date,
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.populateTimeSlots(response.data.slots, targetSelector);
                    } else {
                        $timeSelect.html('<option value="">No slots available</option>');
                    }
                },
                error: function() {
                    $timeSelect.html('<option value="">Error loading slots</option>');
                },
                complete: function() {
                    $timeSelect.prop('disabled', false);
                }
            });
        },
        
        // Populate time slots dropdown
        populateTimeSlots: function(slots, targetSelector) {
            var $timeSelect = $(targetSelector);
            
            $timeSelect.empty();
            
            if (slots.length === 0) {
                $timeSelect.html('<option value="">No slots available</option>');
                return;
            }
            
            $timeSelect.append('<option value="">Select time...</option>');
            
            $.each(slots, function(index, slot) {
                var timeFormatted = AppointmentsAdmin.formatTime(slot);
                $timeSelect.append('<option value="' + slot + '">' + timeFormatted + '</option>');
            });
        },
        
        // Format time for display
        formatTime: function(time24) {
            var timeParts = time24.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = timeParts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12;
            
            return hours + ':' + minutes + ' ' + ampm;
        },
        
        // Show modal
        showModal: function(modalSelector) {
            $('.modal-overlay').show();
            $(modalSelector).show();
            $('body').addClass('modal-open');
        },
        
        // Close modal
        closeModal: function() {
            $('.appointment-modal').hide();
            $('.modal-overlay').hide();
            $('body').removeClass('modal-open');
        },
        
        // Show admin notice
        showNotice: function(message, type) {
            var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
            var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
            
            $('.wrap h1').after($notice);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },
        
        // Calendar functionality
        renderCalendar: function() {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth();
            
            $('#current-month').text(this.getMonthName(month) + ' ' + year);
            
            this.loadCalendarData(year, month);
        },
        
        // Handle previous month
        handlePrevMonth: function(e) {
            e.preventDefault();
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
        },
        
        // Handle next month
        handleNextMonth: function(e) {
            e.preventDefault();
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
        },
        
        // Handle day click
        handleDayClick: function(e) {
            var $day = $(e.target);
            var date = $day.data('date');
            
            if (date) {
                this.loadDayAppointments(date);
            }
        },
        
        // Load calendar data
        loadCalendarData: function(year, month) {
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_calendar_data',
                    year: year,
                    month: month + 1, // JavaScript months are 0-based
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.renderCalendarGrid(response.data, year, month);
                    }
                }
            });
        },
        
        // Render calendar grid
        renderCalendarGrid: function(data, year, month) {
            var $grid = $('#calendar-grid');
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var today = new Date();
            
            $grid.empty();
            
            // Add day headers
            var dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            $.each(dayHeaders, function(index, day) {
                $grid.append('<div class="calendar-header-day">' + day + '</div>');
            });
            
            // Add empty cells for days before month starts
            for (var i = 0; i < firstDay; i++) {
                $grid.append('<div class="calendar-day empty"></div>');
            }
            
            // Add days of the month
            for (var day = 1; day <= daysInMonth; day++) {
                var date = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                var dayData = data[date] || { count: 0, appointments: [] };
                var isPast = new Date(year, month, day) < today;
                
                var classes = ['calendar-day'];
                if (isPast) classes.push('past');
                if (dayData.count > 0) classes.push('has-appointments');
                
                var $dayElement = $('<div class="' + classes.join(' ') + '" data-date="' + date + '">' +
                    '<span class="day-number">' + day + '</span>' +
                    (dayData.count > 0 ? '<span class="appointment-count">' + dayData.count + '</span>' : '') +
                    '</div>');
                
                $grid.append($dayElement);
            }
        },
        
        // Load day appointments
        loadDayAppointments: function(date) {
            $.ajax({
                url: appointmentsAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_day_appointments',
                    date: date,
                    nonce: appointmentsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AppointmentsAdmin.displayDayAppointments(date, response.data);
                    }
                }
            });
        },
        
        // Display day appointments
        displayDayAppointments: function(date, appointments) {
            var $container = $('#day-appointments');
            var $title = $('#selected-date-title');
            var $list = $('#appointments-list');
            
            $title.text('Appointments for ' + this.formatDate(date));
            $list.empty();
            
            if (appointments.length === 0) {
                $list.html('<p>No appointments scheduled for this date.</p>');
            } else {
                $.each(appointments, function(index, appointment) {
                    var $item = $('<div class="appointment-item">' +
                        '<div class="appointment-time">' + AppointmentsAdmin.formatTime(appointment.appointment_time) + '</div>' +
                        '<div class="appointment-client">' + appointment.client_name + '</div>' +
                        '<div class="appointment-service">' + appointment.service_type + '</div>' +
                        '<div class="appointment-status status-' + appointment.status + '">' + appointment.status + '</div>' +
                        '</div>');
                    $list.append($item);
                });
            }
            
            $container.show();
        },
        
        // Get month name
        getMonthName: function(month) {
            var months = ['January', 'February', 'March', 'April', 'May', 'June',
                         'July', 'August', 'September', 'October', 'November', 'December'];
            return months[month];
        },
        
        // Format date for display
        formatDate: function(dateString) {
            var date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        AppointmentsAdmin.init();
    });
    
})(jQuery);