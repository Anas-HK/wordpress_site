<?php
/**
 * Template Name: Simple Calendar Booking
 * 
 * Simple two-column layout with calendar and form
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <div class="simple-booking-wrapper">
            
            <?php
            // Display booking messages
            if (isset($_GET['booking'])) {
                if ($_GET['booking'] === 'success') {
                    echo '<div class="booking-message success">';
                    echo '<p>' . esc_html__('Thank you! Your appointment request has been submitted. We will contact you shortly to confirm your booking.', 'leadership-coach') . '</p>';
                    echo '</div>';
                } elseif ($_GET['booking'] === 'error') {
                    echo '<div class="booking-message error">';
                    echo '<p>' . esc_html__('Sorry, there was an error processing your booking. Please try again or contact us directly.', 'leadership-coach') . '</p>';
                    echo '</div>';
                }
            }
            ?>
            
            <div class="simple-booking-layout">
                
                <!-- Left Side - Meeting Info & Calendar -->
                <div class="booking-left">
                    <div class="meeting-info">
                        <h2><?php esc_html_e('30 Minute Meeting', 'leadership-coach'); ?></h2>
                        <div class="meeting-duration">
                            <span class="duration-icon">⏱</span>
                            <span><?php esc_html_e('30 min', 'leadership-coach'); ?></span>
                        </div>
                        <div class="meeting-details">
                            <span class="details-icon">💻</span>
                            <span><?php esc_html_e('Web conferencing details provided upon confirmation.', 'leadership-coach'); ?></span>
                        </div>
                    </div>
                    
                    <!-- Simple Calendar -->
                    <div class="simple-calendar">
                        <div class="calendar-header">
                            <button type="button" class="calendar-nav prev-month">‹</button>
                            <h3 class="calendar-title" id="calendar-title">September 2025</h3>
                            <button type="button" class="calendar-nav next-month">›</button>
                        </div>
                        
                        <div class="calendar-weekdays">
                            <div class="weekday">MON</div>
                            <div class="weekday">TUE</div>
                            <div class="weekday">WED</div>
                            <div class="weekday">THU</div>
                            <div class="weekday">FRI</div>
                            <div class="weekday">SAT</div>
                            <div class="weekday">SUN</div>
                        </div>
                        
                        <div class="calendar-days" id="calendar-days">
                            <!-- Calendar days will be populated by JavaScript -->
                        </div>
                        
                        <div class="timezone-info">
                            <span class="timezone-icon">🌍</span>
                            <span><?php esc_html_e('Pakistan, Maldives Time (6:27am)', 'leadership-coach'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Booking Form -->
                <div class="booking-right">
                    <h3><?php esc_html_e('Enter Details', 'leadership-coach'); ?></h3>
                    
                    <form id="simple-booking-form" class="simple-booking-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <?php wp_nonce_field('book_appointment', 'appointment_nonce'); ?>
                        <input type="hidden" name="action" value="book_appointment">
                        <input type="hidden" id="selected_date" name="appointment_date" required>
                        <input type="hidden" id="selected_time" name="appointment_time" required>
                        
                        <div class="form-group">
                            <label for="client_name"><?php esc_html_e('Name *', 'leadership-coach'); ?></label>
                            <input type="text" id="client_name" name="client_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="client_email"><?php esc_html_e('Email *', 'leadership-coach'); ?></label>
                            <input type="email" id="client_email" name="client_email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="meeting_purpose"><?php esc_html_e('Please share anything that will help prepare for our meeting.', 'leadership-coach'); ?></label>
                            <textarea id="meeting_purpose" name="appointment_notes" rows="4" placeholder="<?php esc_attr_e('What would you like to discuss?', 'leadership-coach'); ?>"></textarea>
                        </div>
                        
                        <div class="selected-datetime" id="selected-datetime" style="display: none;">
                            <p><strong><?php esc_html_e('Selected:', 'leadership-coach'); ?></strong> <span id="datetime-display"></span></p>
                        </div>
                        
                        <button type="submit" class="schedule-btn" id="schedule-btn" disabled>
                            <?php esc_html_e('Schedule Event', 'leadership-coach'); ?>
                        </button>
                    </form>
                </div>
                
            </div>
        </div>
        
    </main>
</div>

<script>
// Simple Calendar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const calendarDays = document.getElementById('calendar-days');
    const calendarTitle = document.getElementById('calendar-title');
    const selectedDateInput = document.getElementById('selected_date');
    const selectedTimeInput = document.getElementById('selected_time');
    const datetimeDisplay = document.getElementById('datetime-display');
    const selectedDatetime = document.getElementById('selected-datetime');
    const scheduleBtn = document.getElementById('schedule-btn');
    
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;
    
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    const availableTimes = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        calendarTitle.textContent = `${months[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Monday = 0
        
        calendarDays.innerHTML = '';
        
        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            calendarDays.innerHTML += '<div class="calendar-day empty"></div>';
        }
        
        // Add days of the month
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let day = 1; day <= daysInMonth; day++) {
            const currentDay = new Date(year, month, day);
            const isAvailable = currentDay >= today && currentDay.getDay() !== 0 && currentDay.getDay() !== 6;
            const isSelected = selectedDate && selectedDate.getTime() === currentDay.getTime();
            
            const dayClass = `calendar-day ${isAvailable ? 'available' : 'unavailable'} ${isSelected ? 'selected' : ''}`;
            
            calendarDays.innerHTML += `<div class="${dayClass}" data-date="${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}">${day}</div>`;
        }
        
        // Add click events to available days
        document.querySelectorAll('.calendar-day.available').forEach(day => {
            day.addEventListener('click', function() {
                selectDate(this.dataset.date);
            });
        });
    }
    
    function selectDate(dateString) {
        selectedDate = new Date(dateString + 'T00:00:00');
        selectedDateInput.value = dateString;
        
        // Update visual selection
        document.querySelectorAll('.calendar-day').forEach(day => day.classList.remove('selected'));
        document.querySelector(`[data-date="${dateString}"]`).classList.add('selected');
        
        // Show time selection
        showTimeSelection();
        updateDisplay();
    }
    
    function showTimeSelection() {
        // Remove existing time selection
        const existingTimes = document.querySelector('.time-selection');
        if (existingTimes) existingTimes.remove();
        
        // Create time selection
        const timeSelection = document.createElement('div');
        timeSelection.className = 'time-selection';
        timeSelection.innerHTML = '<h4>Available Times</h4>';
        
        const timesGrid = document.createElement('div');
        timesGrid.className = 'times-grid';
        
        availableTimes.forEach(time => {
            const timeBtn = document.createElement('button');
            timeBtn.type = 'button';
            timeBtn.className = 'time-btn';
            timeBtn.textContent = formatTime(time);
            timeBtn.dataset.time = time;
            
            timeBtn.addEventListener('click', function() {
                selectTime(this.dataset.time);
            });
            
            timesGrid.appendChild(timeBtn);
        });
        
        timeSelection.appendChild(timesGrid);
        document.querySelector('.booking-left').appendChild(timeSelection);
    }
    
    function selectTime(time) {
        selectedTime = time;
        selectedTimeInput.value = time;
        
        // Update visual selection
        document.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('selected'));
        document.querySelector(`[data-time="${time}"]`).classList.add('selected');
        
        updateDisplay();
    }
    
    function formatTime(time24) {
        const [hours, minutes] = time24.split(':');
        const hour12 = hours % 12 || 12;
        const ampm = hours >= 12 ? 'PM' : 'AM';
        return `${hour12}:${minutes} ${ampm}`;
    }
    
    function updateDisplay() {
        if (selectedDate && selectedTime) {
            const dateStr = selectedDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const timeStr = formatTime(selectedTime);
            
            datetimeDisplay.textContent = `${dateStr} at ${timeStr}`;
            selectedDatetime.style.display = 'block';
            scheduleBtn.disabled = false;
            scheduleBtn.classList.add('enabled');
        } else {
            selectedDatetime.style.display = 'none';
            scheduleBtn.disabled = true;
            scheduleBtn.classList.remove('enabled');
        }
    }
    
    // Navigation events
    document.querySelector('.prev-month').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    document.querySelector('.next-month').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    // Initialize calendar
    renderCalendar();
});
</script>

<?php get_footer(); ?>