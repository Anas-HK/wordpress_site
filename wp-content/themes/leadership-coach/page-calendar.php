<?php
/**
 * Template Name: Calendar & Booking
 * 
 * The template for displaying the appointment booking calendar page
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    
                    <?php the_content(); ?>
                    
                    <div class="booking-system-container">
                        
                        <!-- Booking Form -->
                        <div class="booking-form-section">
                            <h2><?php esc_html_e( 'Book Your Appointment', 'leadership-coach' ); ?></h2>
                            
                            <form id="appointment-booking-form" class="appointment-form" method="post" action="">
                                
                                <?php wp_nonce_field( 'book_appointment', 'appointment_nonce' ); ?>
                                
                                <!-- Service Selection -->
                                <div class="form-group">
                                    <label for="service_type"><?php esc_html_e( 'Select Service', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                    <select id="service_type" name="service_type" required>
                                        <option value=""><?php esc_html_e( 'Choose a service...', 'leadership-coach' ); ?></option>
                                        <?php
                                        // Get available services
                                        $services = get_posts( array(
                                            'post_type' => 'coaching_service',
                                            'post_status' => 'publish',
                                            'numberposts' => -1,
                                            'meta_query' => array(
                                                array(
                                                    'key' => '_service_is_bookable',
                                                    'value' => '1',
                                                    'compare' => '='
                                                )
                                            )
                                        ) );
                                        
                                        foreach ( $services as $service ) {
                                            $price = get_post_meta( $service->ID, '_service_price', true );
                                            $duration = get_post_meta( $service->ID, '_service_duration', true );
                                            $service_info = $service->post_title;
                                            if ( $price ) {
                                                $service_info .= ' - ' . esc_html( $price );
                                            }
                                            if ( $duration ) {
                                                $service_info .= ' (' . esc_html( $duration ) . ')';
                                            }
                                            echo '<option value="' . esc_attr( $service->post_title ) . '">' . esc_html( $service_info ) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <!-- Hidden Date/Time Fields (populated by calendar widget) -->
                                <input type="hidden" id="appointment_date" name="appointment_date" required>
                                <input type="hidden" id="appointment_time" name="appointment_time" required>
                                
                                <!-- Selected Appointment Display -->
                                <div class="form-group selected-appointment-display" id="selected-appointment-display" style="display: none;">
                                    <label><?php esc_html_e( 'Selected Appointment', 'leadership-coach' ); ?></label>
                                    <div class="selected-appointment-info">
                                        <div class="selected-date-time">
                                            <span id="selected-date-text"></span> at <span id="selected-time-text"></span>
                                        </div>
                                        <button type="button" class="change-selection-btn" onclick="window.CalendarWidget && window.CalendarWidget.resetSelection()">
                                            <?php esc_html_e( 'Change Selection', 'leadership-coach' ); ?>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Client Information -->
                                <fieldset class="client-info-fieldset">
                                    <legend><?php esc_html_e( 'Your Information', 'leadership-coach' ); ?></legend>
                                    
                                    <div class="form-group">
                                        <label for="client_name"><?php esc_html_e( 'Full Name', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                        <input type="text" id="client_name" name="client_name" required maxlength="100">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="client_email"><?php esc_html_e( 'Email Address', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                        <input type="email" id="client_email" name="client_email" required maxlength="100">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="client_phone"><?php esc_html_e( 'Phone Number', 'leadership-coach' ); ?></label>
                                        <input type="tel" id="client_phone" name="client_phone" maxlength="20">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="appointment_notes"><?php esc_html_e( 'Additional Notes', 'leadership-coach' ); ?></label>
                                        <textarea id="appointment_notes" name="appointment_notes" rows="4" placeholder="<?php esc_attr_e( 'Any specific topics you\'d like to discuss or questions you have...', 'leadership-coach' ); ?>"></textarea>
                                    </div>
                                    
                                </fieldset>
                                
                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary booking-submit-btn">
                                        <span class="btn-text"><?php esc_html_e( 'Book Appointment', 'leadership-coach' ); ?></span>
                                        <span class="btn-loading" style="display: none;"><?php esc_html_e( 'Booking...', 'leadership-coach' ); ?></span>
                                    </button>
                                </div>
                                
                            </form>
                            
                            <!-- Form Messages -->
                            <div id="booking-messages" class="form-messages" style="display: none;">
                                <div class="message-content"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Calendar Display -->
                        <div class="calendar-display-section">
                            <h3><?php esc_html_e( 'Select Date & Time', 'leadership-coach' ); ?></h3>
                            
                            <!-- Calendar Widget -->
                            <div id="appointment-calendar" class="calendar-widget">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav prev-month" aria-label="<?php esc_attr_e( 'Previous month', 'leadership-coach' ); ?>">
                                        <span class="screen-reader-text"><?php esc_html_e( 'Previous month', 'leadership-coach' ); ?></span>
                                        &#8249;
                                    </button>
                                    <h4 class="calendar-title" id="calendar-title"></h4>
                                    <button type="button" class="calendar-nav next-month" aria-label="<?php esc_attr_e( 'Next month', 'leadership-coach' ); ?>">
                                        <span class="screen-reader-text"><?php esc_html_e( 'Next month', 'leadership-coach' ); ?></span>
                                        &#8250;
                                    </button>
                                </div>
                                
                                <div class="calendar-grid">
                                    <div class="calendar-weekdays">
                                        <div class="weekday"><?php esc_html_e( 'Sun', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Mon', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Tue', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Wed', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Thu', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Fri', 'leadership-coach' ); ?></div>
                                        <div class="weekday"><?php esc_html_e( 'Sat', 'leadership-coach' ); ?></div>
                                    </div>
                                    <div class="calendar-days" id="calendar-days">
                                        <!-- Calendar days will be populated by JavaScript -->
                                    </div>
                                </div>
                                
                                <div class="calendar-legend">
                                    <div class="legend-item">
                                        <span class="legend-color available"></span>
                                        <span class="legend-text"><?php esc_html_e( 'Available', 'leadership-coach' ); ?></span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color booked"></span>
                                        <span class="legend-text"><?php esc_html_e( 'Fully Booked', 'leadership-coach' ); ?></span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color unavailable"></span>
                                        <span class="legend-text"><?php esc_html_e( 'Unavailable', 'leadership-coach' ); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time Slots Display -->
                            <div id="time-slots-display" class="time-slots-section" style="display: none;">
                                <h4 class="selected-date-title"><?php esc_html_e( 'Available Times for', 'leadership-coach' ); ?> <span id="selected-date-display"></span></h4>
                                <div id="time-slots-grid" class="time-slots-grid">
                                    <!-- Time slots will be populated by JavaScript -->
                                </div>
                                <div id="no-slots-message" class="no-slots-message" style="display: none;">
                                    <p><?php esc_html_e( 'No available time slots for this date. Please select another date.', 'leadership-coach' ); ?></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>

            </article>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>