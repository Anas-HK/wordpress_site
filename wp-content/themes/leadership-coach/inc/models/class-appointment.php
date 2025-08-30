<?php
/**
 * Coaching Appointment Model Class
 * 
 * Handles all database operations for coaching appointments
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Leadership Coach Appointment Model
 */
class Leadership_Coach_Appointment {
    
    /**
     * Database table name
     * 
     * @var string
     */
    private $table_name;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'coaching_appointments';
    }
    
    /**
     * Create a new appointment
     * 
     * @param array $data Appointment data
     * @return int|WP_Error Appointment ID on success, WP_Error on failure
     */
    public function create( $data ) {
        global $wpdb;
        
        $defaults = array(
            'client_name' => '',
            'client_email' => '',
            'client_phone' => '',
            'appointment_date' => '',
            'appointment_time' => '',
            'service_type' => '',
            'status' => 'pending',
            'google_calendar_event_id' => '',
            'notes' => ''
        );
        
        $data = wp_parse_args( $data, $defaults );
        
        // Validate required fields
        if ( empty( $data['client_name'] ) || empty( $data['client_email'] ) || 
             empty( $data['appointment_date'] ) || empty( $data['appointment_time'] ) ) {
            return new WP_Error( 'missing_required_fields', __( 'Missing required appointment fields', 'leadership-coach' ) );
        }
        
        // Validate email
        if ( ! is_email( $data['client_email'] ) ) {
            return new WP_Error( 'invalid_email', __( 'Invalid email address', 'leadership-coach' ) );
        }
        
        // Validate date format
        if ( ! $this->validate_date( $data['appointment_date'] ) ) {
            return new WP_Error( 'invalid_date', __( 'Invalid appointment date format', 'leadership-coach' ) );
        }
        
        // Validate time format
        if ( ! $this->validate_time( $data['appointment_time'] ) ) {
            return new WP_Error( 'invalid_time', __( 'Invalid appointment time format', 'leadership-coach' ) );
        }
        
        // Check for conflicts
        if ( $this->has_conflict( $data['appointment_date'], $data['appointment_time'] ) ) {
            return new WP_Error( 'appointment_conflict', __( 'This time slot is already booked', 'leadership-coach' ) );
        }
        
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'client_name' => sanitize_text_field( $data['client_name'] ),
                'client_email' => sanitize_email( $data['client_email'] ),
                'client_phone' => sanitize_text_field( $data['client_phone'] ),
                'appointment_date' => sanitize_text_field( $data['appointment_date'] ),
                'appointment_time' => sanitize_text_field( $data['appointment_time'] ),
                'service_type' => sanitize_text_field( $data['service_type'] ),
                'status' => sanitize_text_field( $data['status'] ),
                'google_calendar_event_id' => sanitize_text_field( $data['google_calendar_event_id'] ),
                'notes' => sanitize_textarea_field( $data['notes'] )
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );
        
        if ( $result === false ) {
            return new WP_Error( 'db_insert_error', __( 'Failed to create appointment', 'leadership-coach' ) );
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Get appointment by ID
     * 
     * @param int $id Appointment ID
     * @return array|null Appointment data or null if not found
     */
    public function get( $id ) {
        global $wpdb;
        
        $appointment = $wpdb->get_row( 
            $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %d", $id ),
            ARRAY_A 
        );
        
        return $appointment;
    }
    
    /**
     * Update appointment
     * 
     * @param int $id Appointment ID
     * @param array $data Data to update
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function update( $id, $data ) {
        global $wpdb;
        
        // Remove id from data if present
        unset( $data['id'] );
        
        // Validate email if provided
        if ( isset( $data['client_email'] ) && ! is_email( $data['client_email'] ) ) {
            return new WP_Error( 'invalid_email', __( 'Invalid email address', 'leadership-coach' ) );
        }
        
        // Validate date if provided
        if ( isset( $data['appointment_date'] ) && ! $this->validate_date( $data['appointment_date'] ) ) {
            return new WP_Error( 'invalid_date', __( 'Invalid appointment date format', 'leadership-coach' ) );
        }
        
        // Validate time if provided
        if ( isset( $data['appointment_time'] ) && ! $this->validate_time( $data['appointment_time'] ) ) {
            return new WP_Error( 'invalid_time', __( 'Invalid appointment time format', 'leadership-coach' ) );
        }
        
        // Check for conflicts if date/time is being updated
        if ( ( isset( $data['appointment_date'] ) || isset( $data['appointment_time'] ) ) ) {
            $current = $this->get( $id );
            $check_date = isset( $data['appointment_date'] ) ? $data['appointment_date'] : $current['appointment_date'];
            $check_time = isset( $data['appointment_time'] ) ? $data['appointment_time'] : $current['appointment_time'];
            
            if ( $this->has_conflict( $check_date, $check_time, $id ) ) {
                return new WP_Error( 'appointment_conflict', __( 'This time slot is already booked', 'leadership-coach' ) );
            }
        }
        
        // Sanitize data
        $sanitized_data = array();
        $format = array();
        
        foreach ( $data as $key => $value ) {
            switch ( $key ) {
                case 'client_name':
                case 'client_phone':
                case 'appointment_date':
                case 'appointment_time':
                case 'service_type':
                case 'status':
                case 'google_calendar_event_id':
                    $sanitized_data[$key] = sanitize_text_field( $value );
                    $format[] = '%s';
                    break;
                case 'client_email':
                    $sanitized_data[$key] = sanitize_email( $value );
                    $format[] = '%s';
                    break;
                case 'notes':
                    $sanitized_data[$key] = sanitize_textarea_field( $value );
                    $format[] = '%s';
                    break;
            }
        }
        
        if ( empty( $sanitized_data ) ) {
            return new WP_Error( 'no_data', __( 'No valid data to update', 'leadership-coach' ) );
        }
        
        $result = $wpdb->update(
            $this->table_name,
            $sanitized_data,
            array( 'id' => $id ),
            $format,
            array( '%d' )
        );
        
        if ( $result === false ) {
            return new WP_Error( 'db_update_error', __( 'Failed to update appointment', 'leadership-coach' ) );
        }
        
        return true;
    }
    
    /**
     * Delete appointment
     * 
     * @param int $id Appointment ID
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function delete( $id ) {
        global $wpdb;
        
        $result = $wpdb->delete(
            $this->table_name,
            array( 'id' => $id ),
            array( '%d' )
        );
        
        if ( $result === false ) {
            return new WP_Error( 'db_delete_error', __( 'Failed to delete appointment', 'leadership-coach' ) );
        }
        
        return true;
    }
    
    /**
     * Get appointments by date range
     * 
     * @param string $start_date Start date (Y-m-d format)
     * @param string $end_date End date (Y-m-d format)
     * @param string $status Optional status filter
     * @return array Array of appointments
     */
    public function get_by_date_range( $start_date, $end_date, $status = '' ) {
        global $wpdb;
        
        $sql = "SELECT * FROM {$this->table_name} WHERE appointment_date BETWEEN %s AND %s";
        $params = array( $start_date, $end_date );
        
        if ( ! empty( $status ) ) {
            $sql .= " AND status = %s";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY appointment_date ASC, appointment_time ASC";
        
        return $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
    }
    
    /**
     * Get appointments by client email
     * 
     * @param string $client_email Client email address
     * @return array Array of appointments
     */
    public function get_by_client( $client_email ) {
        global $wpdb;
        
        return $wpdb->get_results( 
            $wpdb->prepare( 
                "SELECT * FROM {$this->table_name} WHERE client_email = %s ORDER BY appointment_date DESC, appointment_time DESC", 
                $client_email 
            ),
            ARRAY_A 
        );
    }
    
    /**
     * Get appointments by status
     * 
     * @param string $status Appointment status
     * @return array Array of appointments
     */
    public function get_by_status( $status ) {
        global $wpdb;
        
        return $wpdb->get_results( 
            $wpdb->prepare( 
                "SELECT * FROM {$this->table_name} WHERE status = %s ORDER BY appointment_date ASC, appointment_time ASC", 
                $status 
            ),
            ARRAY_A 
        );
    }
    
    /**
     * Check for appointment conflicts
     * 
     * @param string $date Appointment date
     * @param string $time Appointment time
     * @param int $exclude_id Optional appointment ID to exclude from conflict check
     * @return bool True if conflict exists, false otherwise
     */
    public function has_conflict( $date, $time, $exclude_id = null ) {
        global $wpdb;
        
        $sql = "SELECT COUNT(*) FROM {$this->table_name} WHERE appointment_date = %s AND appointment_time = %s AND status != 'cancelled'";
        $params = array( $date, $time );
        
        if ( $exclude_id ) {
            $sql .= " AND id != %d";
            $params[] = $exclude_id;
        }
        
        $count = $wpdb->get_var( $wpdb->prepare( $sql, $params ) );
        
        return $count > 0;
    }
    

    
    /**
     * Update appointment status
     * 
     * @param int $id Appointment ID
     * @param string $status New status
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function update_status( $id, $status ) {
        $valid_statuses = array( 'pending', 'confirmed', 'completed', 'cancelled', 'no-show' );
        
        if ( ! in_array( $status, $valid_statuses ) ) {
            return new WP_Error( 'invalid_status', __( 'Invalid appointment status', 'leadership-coach' ) );
        }
        
        return $this->update( $id, array( 'status' => $status ) );
    }
    
    /**
     * Get appointment statistics
     * 
     * @param string $start_date Optional start date filter
     * @param string $end_date Optional end date filter
     * @return array Statistics array
     */
    public function get_stats( $start_date = null, $end_date = null ) {
        global $wpdb;
        
        $where_clause = '';
        $params = array();
        
        if ( $start_date && $end_date ) {
            $where_clause = "WHERE appointment_date BETWEEN %s AND %s";
            $params = array( $start_date, $end_date );
        }
        
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM {$this->table_name} 
                {$where_clause}
                GROUP BY status";
        
        if ( ! empty( $params ) ) {
            $results = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
        } else {
            $results = $wpdb->get_results( $sql, ARRAY_A );
        }
        
        $stats = array(
            'total' => 0,
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'no-show' => 0
        );
        
        foreach ( $results as $row ) {
            $stats[$row['status']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }
        
        return $stats;
    }
    
    /**
     * Validate date format (YYYY-MM-DD)
     * 
     * @param string $date Date string to validate
     * @return bool True if valid, false otherwise
     */
    private function validate_date( $date ) {
        $d = DateTime::createFromFormat( 'Y-m-d', $date );
        return $d && $d->format( 'Y-m-d' ) === $date;
    }
    
    /**
     * Validate time format (HH:MM:SS or HH:MM)
     * 
     * @param string $time Time string to validate
     * @return bool True if valid, false otherwise
     */
    private function validate_time( $time ) {
        $t = DateTime::createFromFormat( 'H:i:s', $time );
        if ( ! $t ) {
            // Try HH:MM format
            $t = DateTime::createFromFormat( 'H:i', $time );
            return $t && $t->format( 'H:i' ) === $time;
        }
        return $t && $t->format( 'H:i:s' ) === $time;
    }
    
    /**
     * Cancel appointment with reason
     * 
     * @param int $id Appointment ID
     * @param string $reason Cancellation reason
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function cancel_appointment( $id, $reason = '' ) {
        $result = $this->update_status( $id, 'cancelled' );
        
        if ( is_wp_error( $result ) ) {
            return $result;
        }
        
        // Add cancellation reason to notes if provided
        if ( ! empty( $reason ) ) {
            $appointment = $this->get( $id );
            $notes = $appointment['notes'];
            $notes .= "\n\nCancelled: " . $reason;
            $this->update( $id, array( 'notes' => $notes ) );
        }
        
        return true;
    }
    
    /**
     * Reschedule appointment
     * 
     * @param int $id Appointment ID
     * @param string $new_date New appointment date
     * @param string $new_time New appointment time
     * @param string $reason Rescheduling reason
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function reschedule_appointment( $id, $new_date, $new_time, $reason = '' ) {
        // Check for conflicts
        if ( $this->has_conflict( $new_date, $new_time, $id ) ) {
            return new WP_Error( 'appointment_conflict', __( 'The selected time slot is already booked.', 'leadership-coach' ) );
        }
        
        // Update appointment
        $update_data = array(
            'appointment_date' => $new_date,
            'appointment_time' => $new_time
        );
        
        // Add rescheduling reason to notes if provided
        if ( ! empty( $reason ) ) {
            $appointment = $this->get( $id );
            $notes = $appointment['notes'];
            $notes .= "\n\nRescheduled: " . $reason;
            $update_data['notes'] = $notes;
        }
        
        return $this->update( $id, $update_data );
    }
    
    /**
     * Get appointments for a specific month
     * 
     * @param int $year Year
     * @param int $month Month (1-12)
     * @return array Array of appointments
     */
    public function get_appointments_by_month( $year, $month ) {
        $start_date = sprintf( '%04d-%02d-01', $year, $month );
        $end_date = date( 'Y-m-t', strtotime( $start_date ) );
        
        return $this->get_by_date_range( $start_date, $end_date );
    }
    
    /**
     * Get upcoming appointments
     * 
     * @param int $limit Number of appointments to retrieve
     * @param string $status Optional status filter
     * @return array Array of upcoming appointments
     */
    public function get_upcoming_appointments( $limit = 10, $status = '' ) {
        global $wpdb;
        
        $sql = "SELECT * FROM {$this->table_name} WHERE appointment_date >= %s";
        $params = array( date( 'Y-m-d' ) );
        
        if ( ! empty( $status ) ) {
            $sql .= " AND status = %s";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY appointment_date ASC, appointment_time ASC LIMIT %d";
        $params[] = $limit;
        
        return $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
    }
    
    /**
     * Check if appointment can be cancelled
     * 
     * @param int $id Appointment ID
     * @return bool True if can be cancelled, false otherwise
     */
    public function can_cancel_appointment( $id ) {
        $appointment = $this->get( $id );
        
        if ( ! $appointment ) {
            return false;
        }
        
        // Can't cancel if already cancelled or completed
        if ( in_array( $appointment['status'], array( 'cancelled', 'completed' ) ) ) {
            return false;
        }
        
        // Can't cancel if appointment is in the past
        $appointment_datetime = strtotime( $appointment['appointment_date'] . ' ' . $appointment['appointment_time'] );
        if ( $appointment_datetime < time() ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if appointment can be rescheduled
     * 
     * @param int $id Appointment ID
     * @return bool True if can be rescheduled, false otherwise
     */
    public function can_reschedule_appointment( $id ) {
        $appointment = $this->get( $id );
        
        if ( ! $appointment ) {
            return false;
        }
        
        // Can't reschedule if cancelled or completed
        if ( in_array( $appointment['status'], array( 'cancelled', 'completed' ) ) ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get business hours configuration
     * 
     * @return array Business hours configuration
     */
    public function get_business_hours() {
        // This can be made configurable through WordPress options
        return array(
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration' => 60, // minutes
            'working_days' => array( 1, 2, 3, 4, 5 ), // Monday to Friday
            'break_times' => array(
                array( 'start' => '12:00:00', 'end' => '13:00:00' ) // Lunch break
            )
        );
    }
    
    /**
     * Generate time slots based on business hours
     * 
     * @param string $date Date to generate slots for
     * @return array Array of available time slots
     */
    public function generate_time_slots( $date ) {
        $business_hours = $this->get_business_hours();
        $slots = array();
        
        $start_time = strtotime( $business_hours['start_time'] );
        $end_time = strtotime( $business_hours['end_time'] );
        $slot_duration = $business_hours['slot_duration'] * 60; // Convert to seconds
        
        $current_time = $start_time;
        
        while ( $current_time < $end_time ) {
            $slot_time = date( 'H:i:s', $current_time );
            
            // Check if slot is during break time
            $is_break = false;
            foreach ( $business_hours['break_times'] as $break ) {
                $break_start = strtotime( $break['start'] );
                $break_end = strtotime( $break['end'] );
                
                if ( $current_time >= $break_start && $current_time < $break_end ) {
                    $is_break = true;
                    break;
                }
            }
            
            if ( ! $is_break ) {
                $slots[] = $slot_time;
            }
            
            $current_time += $slot_duration;
        }
        
        return $slots;
    }
    
    /**
     * Get available slots with enhanced business logic
     * 
     * @param string $date Date in Y-m-d format
     * @return array Array of available time slots
     */
    // public function get_available_slots( $date ) {
    //     // Check if date is a working day
    //     $business_hours = $this->get_business_hours();
    //     $day_of_week = date( 'N', strtotime( $date ) ); // 1 = Monday, 7 = Sunday
        
    //     if ( ! in_array( $day_of_week, $business_hours['working_days'] ) ) {
    //         return array(); // No slots on non-working days
    //     }
        
    //     // Generate all possible time slots
    //     $all_slots = $this->generate_time_slots( $date );
        
    //     // Get booked slots for the date
    //     global $wpdb;
    //     $booked_slots = $wpdb->get_col( 
    //         $wpdb->prepare( 
    //             "SELECT appointment_time FROM {$this->table_name} WHERE appointment_date = %s AND status != 'cancelled'", 
    //             $date 
    //         )
    //     );
        
    //     // Remove booked slots
    //     $available_slots = array_diff( $all_slots, $booked_slots );
        
    //     // Remove past slots if date is today
    //     if ( $date === date( 'Y-m-d' ) ) {
    //         $current_time = date( 'H:i:s' );
    //         $available_slots = array_filter( $available_slots, function( $slot ) use ( $current_time ) {
    //             return $slot > $current_time;
    //         } );
    //     }
        
    //     return array_values( $available_slots );
    // }
}