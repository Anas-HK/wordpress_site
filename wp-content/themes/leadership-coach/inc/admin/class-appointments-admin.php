<?php
/**
 * Appointments Admin Interface
 * 
 * Handles the WordPress admin interface for managing appointments
 * 
 * @package Leadership_Coach
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Leadership Coach Appointments Admin Class
 */
class Leadership_Coach_Appointments_Admin {
    
    /**
     * Appointment model instance
     * 
     * @var Leadership_Coach_Appointment
     */
    private $appointment_model;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->appointment_model = leadership_coach_get_appointment_model();
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'wp_ajax_update_appointment_status', array( $this, 'ajax_update_appointment_status' ) );
        add_action( 'wp_ajax_delete_appointment', array( $this, 'ajax_delete_appointment' ) );
        add_action( 'wp_ajax_reschedule_appointment', array( $this, 'ajax_reschedule_appointment' ) );
        add_action( 'wp_ajax_get_appointment_data', array( $this, 'ajax_get_appointment_data' ) );
        add_action( 'wp_ajax_update_appointment', array( $this, 'ajax_update_appointment' ) );
        add_action( 'wp_ajax_get_calendar_data', array( $this, 'ajax_get_calendar_data' ) );
        add_action( 'wp_ajax_get_day_appointments', array( $this, 'ajax_get_day_appointments' ) );
    }
    
    /**
     * Add admin menu pages
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Appointments', 'leadership-coach' ),
            __( 'Appointments', 'leadership-coach' ),
            'manage_options',
            'coaching-appointments',
            array( $this, 'appointments_page' ),
            'dashicons-calendar-alt',
            30
        );
        
        add_submenu_page(
            'coaching-appointments',
            __( 'All Appointments', 'leadership-coach' ),
            __( 'All Appointments', 'leadership-coach' ),
            'manage_options',
            'coaching-appointments',
            array( $this, 'appointments_page' )
        );
        
        add_submenu_page(
            'coaching-appointments',
            __( 'Calendar View', 'leadership-coach' ),
            __( 'Calendar View', 'leadership-coach' ),
            'manage_options',
            'appointments-calendar',
            array( $this, 'calendar_page' )
        );
        
        add_submenu_page(
            'coaching-appointments',
            __( 'Statistics', 'leadership-coach' ),
            __( 'Statistics', 'leadership-coach' ),
            'manage_options',
            'appointments-stats',
            array( $this, 'stats_page' )
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts( $hook ) {
        // Only load on appointment pages
        if ( strpos( $hook, 'coaching-appointments' ) === false && strpos( $hook, 'appointments-' ) === false ) {
            return;
        }
        
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'jquery-ui-style', 'https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css' );
        
        wp_enqueue_script(
            'appointments-admin',
            get_template_directory_uri() . '/inc/js/appointments-admin.js',
            array( 'jquery', 'jquery-ui-datepicker' ),
            '1.0.0',
            true
        );
        
        wp_localize_script( 'appointments-admin', 'appointmentsAdmin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'appointments_admin_nonce' ),
            'strings' => array(
                'confirmDelete' => __( 'Are you sure you want to delete this appointment?', 'leadership-coach' ),
                'confirmCancel' => __( 'Are you sure you want to cancel this appointment?', 'leadership-coach' ),
                'selectNewDate' => __( 'Select new date and time:', 'leadership-coach' ),
                'rescheduleSuccess' => __( 'Appointment rescheduled successfully.', 'leadership-coach' ),
                'statusUpdateSuccess' => __( 'Appointment status updated successfully.', 'leadership-coach' ),
                'deleteSuccess' => __( 'Appointment deleted successfully.', 'leadership-coach' ),
                'error' => __( 'An error occurred. Please try again.', 'leadership-coach' )
            )
        ) );
        
        wp_enqueue_style(
            'appointments-admin',
            get_template_directory_uri() . '/assets/css/appointments-admin.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Display appointments management page
     */
    public function appointments_page() {
        // Handle bulk actions
        if ( isset( $_POST['action'] ) && $_POST['action'] !== '-1' ) {
            $this->handle_bulk_actions();
        }
        
        // Get current page and per page settings
        $current_page = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
        $per_page = 20;
        $offset = ( $current_page - 1 ) * $per_page;
        
        // Get filter parameters
        $status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';
        $date_filter = isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '';
        
        // Get appointments
        $appointments = $this->get_filtered_appointments( $status_filter, $date_filter, $per_page, $offset );
        $total_appointments = $this->get_appointments_count( $status_filter, $date_filter );
        
        // Calculate pagination
        $total_pages = ceil( $total_appointments / $per_page );
        
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Appointments', 'leadership-coach' ); ?></h1>
            <a href="#" class="page-title-action" id="add-new-appointment"><?php esc_html_e( 'Add New', 'leadership-coach' ); ?></a>
            <hr class="wp-header-end">
            
            <!-- Filters -->
            <div class="tablenav top">
                <div class="alignleft actions">
                    <form method="get" action="">
                        <input type="hidden" name="page" value="coaching-appointments">
                        
                        <select name="status">
                            <option value=""><?php esc_html_e( 'All Statuses', 'leadership-coach' ); ?></option>
                            <option value="pending" <?php selected( $status_filter, 'pending' ); ?>><?php esc_html_e( 'Pending', 'leadership-coach' ); ?></option>
                            <option value="confirmed" <?php selected( $status_filter, 'confirmed' ); ?>><?php esc_html_e( 'Confirmed', 'leadership-coach' ); ?></option>
                            <option value="completed" <?php selected( $status_filter, 'completed' ); ?>><?php esc_html_e( 'Completed', 'leadership-coach' ); ?></option>
                            <option value="cancelled" <?php selected( $status_filter, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'leadership-coach' ); ?></option>
                            <option value="no-show" <?php selected( $status_filter, 'no-show' ); ?>><?php esc_html_e( 'No Show', 'leadership-coach' ); ?></option>
                        </select>
                        
                        <input type="date" name="date" value="<?php echo esc_attr( $date_filter ); ?>" placeholder="<?php esc_attr_e( 'Filter by date', 'leadership-coach' ); ?>">
                        
                        <input type="submit" class="button" value="<?php esc_attr_e( 'Filter', 'leadership-coach' ); ?>">
                        
                        <?php if ( $status_filter || $date_filter ) : ?>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=coaching-appointments' ) ); ?>" class="button"><?php esc_html_e( 'Clear Filters', 'leadership-coach' ); ?></a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <?php if ( $total_pages > 1 ) : ?>
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php printf( _n( '%s item', '%s items', $total_appointments, 'leadership-coach' ), number_format_i18n( $total_appointments ) ); ?></span>
                        <?php
                        echo paginate_links( array(
                            'base' => add_query_arg( 'paged', '%#%' ),
                            'format' => '',
                            'prev_text' => __( '&laquo;', 'leadership-coach' ),
                            'next_text' => __( '&raquo;', 'leadership-coach' ),
                            'total' => $total_pages,
                            'current' => $current_page
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Appointments Table -->
            <form method="post" action="">
                <?php wp_nonce_field( 'bulk_appointments_action', 'appointments_bulk_nonce' ); ?>
                
                <table class="wp-list-table widefat fixed striped appointments">
                    <thead>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <input type="checkbox" id="cb-select-all-1">
                            </td>
                            <th class="manage-column column-client"><?php esc_html_e( 'Client', 'leadership-coach' ); ?></th>
                            <th class="manage-column column-service"><?php esc_html_e( 'Service', 'leadership-coach' ); ?></th>
                            <th class="manage-column column-datetime"><?php esc_html_e( 'Date & Time', 'leadership-coach' ); ?></th>
                            <th class="manage-column column-status"><?php esc_html_e( 'Status', 'leadership-coach' ); ?></th>
                            <th class="manage-column column-actions"><?php esc_html_e( 'Actions', 'leadership-coach' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( empty( $appointments ) ) : ?>
                            <tr>
                                <td colspan="6" class="no-appointments">
                                    <?php esc_html_e( 'No appointments found.', 'leadership-coach' ); ?>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ( $appointments as $appointment ) : ?>
                                <tr data-appointment-id="<?php echo esc_attr( $appointment['id'] ); ?>">
                                    <th class="check-column">
                                        <input type="checkbox" name="appointment_ids[]" value="<?php echo esc_attr( $appointment['id'] ); ?>">
                                    </th>
                                    <td class="column-client">
                                        <strong><?php echo esc_html( $appointment['client_name'] ); ?></strong><br>
                                        <a href="mailto:<?php echo esc_attr( $appointment['client_email'] ); ?>"><?php echo esc_html( $appointment['client_email'] ); ?></a>
                                        <?php if ( ! empty( $appointment['client_phone'] ) ) : ?>
                                            <br><span class="phone"><?php echo esc_html( $appointment['client_phone'] ); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="column-service">
                                        <?php echo esc_html( $appointment['service_type'] ); ?>
                                    </td>
                                    <td class="column-datetime">
                                        <strong><?php echo esc_html( date( 'M j, Y', strtotime( $appointment['appointment_date'] ) ) ); ?></strong><br>
                                        <?php echo esc_html( date( 'g:i A', strtotime( $appointment['appointment_time'] ) ) ); ?>
                                    </td>
                                    <td class="column-status">
                                        <span class="status-badge status-<?php echo esc_attr( $appointment['status'] ); ?>">
                                            <?php echo esc_html( ucfirst( str_replace( '-', ' ', $appointment['status'] ) ) ); ?>
                                        </span>
                                    </td>
                                    <td class="column-actions">
                                        <div class="row-actions">
                                            <span class="edit">
                                                <a href="#" class="edit-appointment" data-id="<?php echo esc_attr( $appointment['id'] ); ?>"><?php esc_html_e( 'Edit', 'leadership-coach' ); ?></a> |
                                            </span>
                                            <span class="reschedule">
                                                <a href="#" class="reschedule-appointment" data-id="<?php echo esc_attr( $appointment['id'] ); ?>"><?php esc_html_e( 'Reschedule', 'leadership-coach' ); ?></a> |
                                            </span>
                                            <?php if ( $appointment['status'] !== 'cancelled' ) : ?>
                                                <span class="cancel">
                                                    <a href="#" class="cancel-appointment" data-id="<?php echo esc_attr( $appointment['id'] ); ?>"><?php esc_html_e( 'Cancel', 'leadership-coach' ); ?></a> |
                                                </span>
                                            <?php endif; ?>
                                            <span class="delete">
                                                <a href="#" class="delete-appointment" data-id="<?php echo esc_attr( $appointment['id'] ); ?>" style="color: #a00;"><?php esc_html_e( 'Delete', 'leadership-coach' ); ?></a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php if ( ! empty( $appointment['notes'] ) ) : ?>
                                    <tr class="appointment-notes">
                                        <td></td>
                                        <td colspan="5">
                                            <strong><?php esc_html_e( 'Notes:', 'leadership-coach' ); ?></strong> <?php echo esc_html( $appointment['notes'] ); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="tablenav bottom">
                    <div class="alignleft actions bulkactions">
                        <select name="action">
                            <option value="-1"><?php esc_html_e( 'Bulk Actions', 'leadership-coach' ); ?></option>
                            <option value="confirm"><?php esc_html_e( 'Confirm', 'leadership-coach' ); ?></option>
                            <option value="complete"><?php esc_html_e( 'Mark as Completed', 'leadership-coach' ); ?></option>
                            <option value="cancel"><?php esc_html_e( 'Cancel', 'leadership-coach' ); ?></option>
                            <option value="delete"><?php esc_html_e( 'Delete', 'leadership-coach' ); ?></option>
                        </select>
                        <input type="submit" class="button action" value="<?php esc_attr_e( 'Apply', 'leadership-coach' ); ?>">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Edit Appointment Modal -->
        <div id="edit-appointment-modal" class="appointment-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><?php esc_html_e( 'Edit Appointment', 'leadership-coach' ); ?></h3>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-appointment-form">
                        <input type="hidden" id="edit-appointment-id" name="appointment_id">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-client-name"><?php esc_html_e( 'Client Name', 'leadership-coach' ); ?></label>
                                <input type="text" id="edit-client-name" name="client_name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-client-email"><?php esc_html_e( 'Email', 'leadership-coach' ); ?></label>
                                <input type="email" id="edit-client-email" name="client_email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-client-phone"><?php esc_html_e( 'Phone', 'leadership-coach' ); ?></label>
                                <input type="tel" id="edit-client-phone" name="client_phone">
                            </div>
                            <div class="form-group">
                                <label for="edit-service-type"><?php esc_html_e( 'Service', 'leadership-coach' ); ?></label>
                                <input type="text" id="edit-service-type" name="service_type" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-appointment-date"><?php esc_html_e( 'Date', 'leadership-coach' ); ?></label>
                                <input type="date" id="edit-appointment-date" name="appointment_date" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-appointment-time"><?php esc_html_e( 'Time', 'leadership-coach' ); ?></label>
                                <input type="time" id="edit-appointment-time" name="appointment_time" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-status"><?php esc_html_e( 'Status', 'leadership-coach' ); ?></label>
                                <select id="edit-status" name="status" required>
                                    <option value="pending"><?php esc_html_e( 'Pending', 'leadership-coach' ); ?></option>
                                    <option value="confirmed"><?php esc_html_e( 'Confirmed', 'leadership-coach' ); ?></option>
                                    <option value="completed"><?php esc_html_e( 'Completed', 'leadership-coach' ); ?></option>
                                    <option value="cancelled"><?php esc_html_e( 'Cancelled', 'leadership-coach' ); ?></option>
                                    <option value="no-show"><?php esc_html_e( 'No Show', 'leadership-coach' ); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-notes"><?php esc_html_e( 'Notes', 'leadership-coach' ); ?></label>
                            <textarea id="edit-notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="button button-primary"><?php esc_html_e( 'Update Appointment', 'leadership-coach' ); ?></button>
                            <button type="button" class="button modal-close"><?php esc_html_e( 'Cancel', 'leadership-coach' ); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Reschedule Appointment Modal -->
        <div id="reschedule-appointment-modal" class="appointment-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><?php esc_html_e( 'Reschedule Appointment', 'leadership-coach' ); ?></h3>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="reschedule-appointment-form">
                        <input type="hidden" id="reschedule-appointment-id" name="appointment_id">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="reschedule-date"><?php esc_html_e( 'New Date', 'leadership-coach' ); ?></label>
                                <input type="date" id="reschedule-date" name="new_date" required min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                            </div>
                            <div class="form-group">
                                <label for="reschedule-time"><?php esc_html_e( 'New Time', 'leadership-coach' ); ?></label>
                                <select id="reschedule-time" name="new_time" required>
                                    <option value=""><?php esc_html_e( 'Select date first...', 'leadership-coach' ); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reschedule-reason"><?php esc_html_e( 'Reason for Rescheduling', 'leadership-coach' ); ?></label>
                            <textarea id="reschedule-reason" name="reason" rows="3" placeholder="<?php esc_attr_e( 'Optional reason for rescheduling...', 'leadership-coach' ); ?>"></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="button button-primary"><?php esc_html_e( 'Reschedule Appointment', 'leadership-coach' ); ?></button>
                            <button type="button" class="button modal-close"><?php esc_html_e( 'Cancel', 'leadership-coach' ); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Display calendar view page
     */
    public function calendar_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Appointment Calendar', 'leadership-coach' ); ?></h1>
            
            <div id="appointment-calendar-admin" class="calendar-container">
                <div class="calendar-header">
                    <button id="prev-month" class="button">&laquo; <?php esc_html_e( 'Previous', 'leadership-coach' ); ?></button>
                    <h2 id="current-month"></h2>
                    <button id="next-month" class="button"><?php esc_html_e( 'Next', 'leadership-coach' ); ?> &raquo;</button>
                </div>
                
                <div id="calendar-grid" class="calendar-grid">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
                
                <div class="calendar-legend">
                    <div class="legend-item">
                        <span class="legend-color available"></span>
                        <?php esc_html_e( 'Available', 'leadership-coach' ); ?>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color booked"></span>
                        <?php esc_html_e( 'Booked', 'leadership-coach' ); ?>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color past"></span>
                        <?php esc_html_e( 'Past Date', 'leadership-coach' ); ?>
                    </div>
                </div>
            </div>
            
            <div id="day-appointments" class="day-appointments" style="display: none;">
                <h3 id="selected-date-title"></h3>
                <div id="appointments-list"></div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Display statistics page
     */
    public function stats_page() {
        // Get date range for stats (default to current month)
        $start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : date( 'Y-m-01' );
        $end_date = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : date( 'Y-m-t' );
        
        // Get statistics
        $stats = $this->appointment_model->get_stats( $start_date, $end_date );
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Appointment Statistics', 'leadership-coach' ); ?></h1>
            
            <!-- Date Range Filter -->
            <div class="stats-filters">
                <form method="get" action="">
                    <input type="hidden" name="page" value="appointments-stats">
                    
                    <label for="start_date"><?php esc_html_e( 'From:', 'leadership-coach' ); ?></label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>">
                    
                    <label for="end_date"><?php esc_html_e( 'To:', 'leadership-coach' ); ?></label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>">
                    
                    <input type="submit" class="button" value="<?php esc_attr_e( 'Update Stats', 'leadership-coach' ); ?>">
                </form>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stats-card">
                    <h3><?php esc_html_e( 'Total Appointments', 'leadership-coach' ); ?></h3>
                    <div class="stats-number"><?php echo esc_html( $stats['total'] ); ?></div>
                </div>
                
                <div class="stats-card">
                    <h3><?php esc_html_e( 'Pending', 'leadership-coach' ); ?></h3>
                    <div class="stats-number pending"><?php echo esc_html( $stats['pending'] ); ?></div>
                </div>
                
                <div class="stats-card">
                    <h3><?php esc_html_e( 'Confirmed', 'leadership-coach' ); ?></h3>
                    <div class="stats-number confirmed"><?php echo esc_html( $stats['confirmed'] ); ?></div>
                </div>
                
                <div class="stats-card">
                    <h3><?php esc_html_e( 'Completed', 'leadership-coach' ); ?></h3>
                    <div class="stats-number completed"><?php echo esc_html( $stats['completed'] ); ?></div>
                </div>
                
                <div class="stats-card">
                    <h3><?php esc_html_e( 'Cancelled', 'leadership-coach' ); ?></h3>
                    <div class="stats-number cancelled"><?php echo esc_html( $stats['cancelled'] ); ?></div>
                </div>
                
                <div class="stats-card">
                    <h3><?php esc_html_e( 'No Shows', 'leadership-coach' ); ?></h3>
                    <div class="stats-number no-show"><?php echo esc_html( $stats['no-show'] ); ?></div>
                </div>
            </div>
            
            <!-- Completion Rate -->
            <?php
            $completion_rate = $stats['total'] > 0 ? round( ( $stats['completed'] / $stats['total'] ) * 100, 1 ) : 0;
            $show_rate = $stats['total'] > 0 ? round( ( ( $stats['completed'] + $stats['confirmed'] ) / $stats['total'] ) * 100, 1 ) : 0;
            ?>
            
            <div class="stats-summary">
                <h3><?php esc_html_e( 'Performance Summary', 'leadership-coach' ); ?></h3>
                <p><?php printf( __( 'Completion Rate: %s%%', 'leadership-coach' ), $completion_rate ); ?></p>
                <p><?php printf( __( 'Show Rate: %s%%', 'leadership-coach' ), $show_rate ); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get filtered appointments
     */
    private function get_filtered_appointments( $status = '', $date = '', $limit = 20, $offset = 0 ) {
        global $wpdb;
        
        $where_conditions = array();
        $params = array();
        
        if ( ! empty( $status ) ) {
            $where_conditions[] = "status = %s";
            $params[] = $status;
        }
        
        if ( ! empty( $date ) ) {
            $where_conditions[] = "appointment_date = %s";
            $params[] = $date;
        }
        
        $where_clause = ! empty( $where_conditions ) ? 'WHERE ' . implode( ' AND ', $where_conditions ) : '';
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'coaching_appointments';
        $sql = "SELECT * FROM {$table_name} {$where_clause} ORDER BY appointment_date DESC, appointment_time DESC LIMIT %d OFFSET %d";
        $params[] = $limit;
        $params[] = $offset;
        
        return $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
    }
    
    /**
     * Get appointments count for pagination
     */
    private function get_appointments_count( $status = '', $date = '' ) {
        global $wpdb;
        
        $where_conditions = array();
        $params = array();
        
        if ( ! empty( $status ) ) {
            $where_conditions[] = "status = %s";
            $params[] = $status;
        }
        
        if ( ! empty( $date ) ) {
            $where_conditions[] = "appointment_date = %s";
            $params[] = $date;
        }
        
        $where_clause = ! empty( $where_conditions ) ? 'WHERE ' . implode( ' AND ', $where_conditions ) : '';
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'coaching_appointments';
        $sql = "SELECT COUNT(*) FROM {$table_name} {$where_clause}";
        
        if ( ! empty( $params ) ) {
            return $wpdb->get_var( $wpdb->prepare( $sql, $params ) );
        } else {
            return $wpdb->get_var( $sql );
        }
    }
    
    /**
     * Handle bulk actions
     */
    private function handle_bulk_actions() {
        if ( ! isset( $_POST['appointments_bulk_nonce'] ) || ! wp_verify_nonce( $_POST['appointments_bulk_nonce'], 'bulk_appointments_action' ) ) {
            wp_die( __( 'Security check failed.', 'leadership-coach' ) );
        }
        
        if ( ! isset( $_POST['appointment_ids'] ) || ! is_array( $_POST['appointment_ids'] ) ) {
            return;
        }
        
        $action = sanitize_text_field( $_POST['action'] );
        $appointment_ids = array_map( 'intval', $_POST['appointment_ids'] );
        
        foreach ( $appointment_ids as $appointment_id ) {
            switch ( $action ) {
                case 'confirm':
                    $this->appointment_model->update_status( $appointment_id, 'confirmed' );
                    break;
                case 'complete':
                    $this->appointment_model->update_status( $appointment_id, 'completed' );
                    break;
                case 'cancel':
                    $this->appointment_model->update_status( $appointment_id, 'cancelled' );
                    break;
                case 'delete':
                    $this->appointment_model->delete( $appointment_id );
                    break;
            }
        }
        
        // Redirect to avoid resubmission
        wp_redirect( admin_url( 'admin.php?page=coaching-appointments&updated=1' ) );
        exit;
    }
    
    /**
     * AJAX handler for updating appointment status
     */
    public function ajax_update_appointment_status() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $appointment_id = intval( $_POST['appointment_id'] );
        $new_status = sanitize_text_field( $_POST['status'] );
        
        $result = $this->appointment_model->update_status( $appointment_id, $new_status );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        wp_send_json_success( array( 'message' => __( 'Appointment status updated successfully.', 'leadership-coach' ) ) );
    }
    
    /**
     * AJAX handler for deleting appointment
     */
    public function ajax_delete_appointment() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $appointment_id = intval( $_POST['appointment_id'] );
        
        $result = $this->appointment_model->delete( $appointment_id );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        wp_send_json_success( array( 'message' => __( 'Appointment deleted successfully.', 'leadership-coach' ) ) );
    }
    
    /**
     * AJAX handler for rescheduling appointment
     */
    public function ajax_reschedule_appointment() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $appointment_id = intval( $_POST['appointment_id'] );
        $new_date = sanitize_text_field( $_POST['new_date'] );
        $new_time = sanitize_text_field( $_POST['new_time'] );
        $reason = sanitize_textarea_field( $_POST['reason'] );
        
        // Check for conflicts
        if ( $this->appointment_model->has_conflict( $new_date, $new_time, $appointment_id ) ) {
            wp_send_json_error( array( 'message' => __( 'The selected time slot is already booked.', 'leadership-coach' ) ) );
        }
        
        // Update appointment
        $update_data = array(
            'appointment_date' => $new_date,
            'appointment_time' => $new_time
        );
        
        if ( ! empty( $reason ) ) {
            $current_appointment = $this->appointment_model->get( $appointment_id );
            $notes = $current_appointment['notes'];
            $notes .= "\n\nRescheduled: " . $reason;
            $update_data['notes'] = $notes;
        }
        
        $result = $this->appointment_model->update( $appointment_id, $update_data );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        wp_send_json_success( array( 'message' => __( 'Appointment rescheduled successfully.', 'leadership-coach' ) ) );
    }
    
    /**
     * AJAX handler for getting appointment data
     */
    public function ajax_get_appointment_data() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $appointment_id = intval( $_POST['appointment_id'] );
        $appointment = $this->appointment_model->get( $appointment_id );
        
        if ( ! $appointment ) {
            wp_send_json_error( array( 'message' => __( 'Appointment not found.', 'leadership-coach' ) ) );
        }
        
        wp_send_json_success( $appointment );
    }
    
    /**
     * AJAX handler for updating appointment
     */
    public function ajax_update_appointment() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $appointment_id = intval( $_POST['appointment_id'] );
        
        $update_data = array(
            'client_name' => sanitize_text_field( $_POST['client_name'] ),
            'client_email' => sanitize_email( $_POST['client_email'] ),
            'client_phone' => sanitize_text_field( $_POST['client_phone'] ),
            'service_type' => sanitize_text_field( $_POST['service_type'] ),
            'appointment_date' => sanitize_text_field( $_POST['appointment_date'] ),
            'appointment_time' => sanitize_text_field( $_POST['appointment_time'] ),
            'status' => sanitize_text_field( $_POST['status'] ),
            'notes' => sanitize_textarea_field( $_POST['notes'] )
        );
        
        $result = $this->appointment_model->update( $appointment_id, $update_data );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        wp_send_json_success( array( 'message' => __( 'Appointment updated successfully.', 'leadership-coach' ) ) );
    }
    
    /**
     * AJAX handler for getting calendar data
     */
    public function ajax_get_calendar_data() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $year = intval( $_POST['year'] );
        $month = intval( $_POST['month'] );
        
        // Get first and last day of the month
        $start_date = sprintf( '%04d-%02d-01', $year, $month );
        $end_date = date( 'Y-m-t', strtotime( $start_date ) );
        
        // Get appointments for the month
        $appointments = $this->appointment_model->get_by_date_range( $start_date, $end_date );
        
        // Group appointments by date
        $calendar_data = array();
        foreach ( $appointments as $appointment ) {
            $date = $appointment['appointment_date'];
            if ( ! isset( $calendar_data[$date] ) ) {
                $calendar_data[$date] = array(
                    'count' => 0,
                    'appointments' => array()
                );
            }
            $calendar_data[$date]['count']++;
            $calendar_data[$date]['appointments'][] = $appointment;
        }
        
        wp_send_json_success( $calendar_data );
    }
    
    /**
     * AJAX handler for getting day appointments
     */
    public function ajax_get_day_appointments() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'appointments_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'leadership-coach' ) ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'leadership-coach' ) ) );
        }
        
        $date = sanitize_text_field( $_POST['date'] );
        $appointments = $this->appointment_model->get_by_date_range( $date, $date );
        
        // Sort by time
        usort( $appointments, function( $a, $b ) {
            return strcmp( $a['appointment_time'], $b['appointment_time'] );
        } );
        
        wp_send_json_success( $appointments );
    }
}

// Initialize the admin class
new Leadership_Coach_Appointments_Admin();