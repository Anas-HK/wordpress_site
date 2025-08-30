<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * After setup theme hook
 */
function leadership_coach_theme_setup()
{
    /*
     * Make chile theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain('leadership-coach', get_stylesheet_directory() . '/languages');

    // Add support for padding control
    add_theme_support('custom-spacing');

    // Add support for border
    add_theme_support('border');

    // Add support for link color
    add_theme_support('link-color');

    // Add support for custom line height
    add_theme_support('custom-line-height');
}
add_action('after_setup_theme', 'leadership_coach_theme_setup', 100);

function leadership_coach_styles()
{
    $my_theme = wp_get_theme();
    $version  = $my_theme['Version'];

    wp_enqueue_style('coachpress-lite', get_template_directory_uri()  . '/style.css', array('animate'));
    wp_enqueue_style('leadership-coach', get_stylesheet_directory_uri() . '/style.css', array('coachpress-lite'), $version);

    // Enqueue custom coaching website styles
    wp_enqueue_style('leadership-coach-custom', get_stylesheet_directory_uri() . '/assets/css/custom-styles.css', array('leadership-coach'), $version);

    // Enqueue responsive styles
    wp_enqueue_style('leadership-coach-responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array('leadership-coach-custom'), $version);

    // Enqueue Google Fonts for enhanced typography
    wp_enqueue_style('leadership-coach-fonts', leadership_coach_fonts_url(), array(), $version);
}
add_action('wp_enqueue_scripts', 'leadership_coach_styles', 10);

/**
 * Enqueue contact form JavaScript
 */
function leadership_coach_contact_scripts()
{
    // Only load on contact page
    // We no longer enqueue the AJAX contact form script; the page uses a mailto compose flow.
    // Keeping this function for future extensibility.
    return;
}
add_action('wp_enqueue_scripts', 'leadership_coach_contact_scripts', 15);

function leadership_coach_customize_script()
{
    $my_theme = wp_get_theme();
    $version  = $my_theme['Version'];

    wp_enqueue_script('coachpress-lite-customize', get_stylesheet_directory_uri() . '/inc/js/customize.js', array('jquery', 'customize-controls'), $version, true);
}
add_action('customize_controls_enqueue_scripts', 'leadership_coach_customize_script');

//Remove a function from the parent theme
function leadership_coach_remove_parent_filters()
{
    remove_action('customize_register', 'coachpress_lite_customizer_theme_info');
    remove_action('customize_register', 'coachpress_lite_customize_register_appearance');
    remove_action('wp_head', 'coachpress_lite_dynamic_css', 99);
}
add_action('init', 'leadership_coach_remove_parent_filters');

function leadership_coach_overide_values($wp_customize)
{
    if (coachpress_lite_is_wheel_of_life_activated()) {
        $wp_customize->get_setting('wheeloflife_color')->default = '#eef9f5';
    }
}
add_action('customize_register', 'leadership_coach_overide_values', 999);

function leadership_coach_customizer_register($wp_customize)
{

    $wp_customize->add_section(
        'theme_info',
        array(
            'title'    => __('Information Links', 'leadership-coach'),
            'priority' => 6,
        )
    );

    /** Important Links */
    $wp_customize->add_setting(
        'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $theme_info = '<p>';
    $theme_info .= sprintf(__('Demo Link: %1$sClick here.%2$s', 'leadership-coach'),  '<a href="' . esc_url('https://blossomthemes.com/theme-demo/?theme=leadership-coach') . '" target="_blank">', '</a>');
    $theme_info .= '</p><p>';
    $theme_info .= sprintf(__('Documentation Link: %1$sClick here.%2$s', 'leadership-coach'),  '<a href="' . esc_url('https://docs.blossomthemes.com/leadership-coach/') . '" target="_blank">', '</a>');
    $theme_info .= '</p>';

    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control(
            $wp_customize,
            'theme_info_theme',
            array(
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );

    /** Header Layout Settings */
    $wp_customize->add_section(
        'header_layout_settings',
        array(
            'title'    => __('Header Layout', 'leadership-coach'),
            'priority' => 10,
            'panel'    => 'layout_settings',
        )
    );

    /** Header layout */
    $wp_customize->add_setting(
        'header_layout',
        array(
            'default'           => 'two',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'header_layout',
            array(
                'section'     => 'header_layout_settings',
                'label'       => __('Header Layout', 'leadership-coach'),
                'description' => __('Choose the layout of the header for your site.', 'leadership-coach'),
                'choices'     => array(
                    'one'   => get_stylesheet_directory_uri() . '/images/header/one.jpg',
                    'two'  => get_stylesheet_directory_uri() . '/images/header/two.jpg',
                )
            )
        )
    );

    /** Static Banner Layout Settings */
    $wp_customize->add_section(
        'cta_static_banner_layout_settings',
        array(
            'title'    => __('CTA Static Banner Layout', 'leadership-coach'),
            'priority' => 30,
            'panel'    => 'layout_settings',
        )
    );

    $wp_customize->add_setting(
        'cta_static_banner_layout',
        array(
            'default'           => 'three',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'cta_static_banner_layout',
            array(
                'section'     => 'cta_static_banner_layout_settings',
                'label'       => __('CTA Static Banner Layout', 'leadership-coach'),
                'description' => __('Choose the layout of the cta static banner for your site.', 'leadership-coach'),
                'choices'     => array(
                    'one'   => get_stylesheet_directory_uri() . '/images/static_banner/cta_one.jpg',
                    'three' => get_stylesheet_directory_uri() . '/images/static_banner/cta_three.jpg',
                )
            )
        )
    );

    $wp_customize->add_panel(
        'appearance_settings',
        array(
            'title'       => __('Appearance Settings', 'leadership-coach'),
            'priority'    => 25,
            'capability'  => 'edit_theme_options',
            'description' => __('Change color and body background.', 'leadership-coach'),
        )
    );

    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __('Typography', 'leadership-coach'),
            'priority' => 20,
            'panel'    => 'appearance_settings',
        )
    );

    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default' => array(
                'font-family' => 'Lato',
                'variant'     => 'regular',
            ),
            'sanitize_callback' => array('CoachPress_Lite_Fonts', 'sanitize_typography')
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Typography_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __('Primary Font', 'leadership-coach'),
                'description' => __('Primary font of the site.', 'leadership-coach'),
                'section'     => 'typography_settings',
                'priority'    => 5,
            )
        )
    );

    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'Lora',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __('Secondary Font', 'leadership-coach'),
                'description' => __('Secondary font of the site.', 'leadership-coach'),
                'section'     => 'typography_settings',
                'choices'     => coachpress_lite_get_all_fonts(),
                'priority'    => 5,
            )
        )
    );

    /** Tertiary Font */
    $wp_customize->add_setting(
        'tertiary_font',
        array(
            'default'           => 'Great Vibes',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'tertiary_font',
            array(
                'label'       => __('Tertiary Font', 'leadership-coach'),
                'section'     => 'typography_settings',
                'choices'     => coachpress_lite_get_all_fonts(),
            )
        )
    );

    /** Font Size*/
    $wp_customize->add_setting(
        'font_size',
        array(
            'default'           => 18,
            'sanitize_callback' => 'coachpress_lite_sanitize_number_absint'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Slider_Control(
            $wp_customize,
            'font_size',
            array(
                'section'     => 'typography_settings',
                'label'       => __('Font Size', 'leadership-coach'),
                'description' => __('Change the font size of your site.', 'leadership-coach'),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 50,
                    'step'  => 1,
                )
            )
        )
    );

    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control(
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __('Load Google Fonts Locally', 'leadership-coach'),
                'description'   => __('Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'leadership-coach')
            )
        )
    );

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control(
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __('Preload Local Fonts', 'leadership-coach'),
                'description'   => __('Preloading Google fonts will speed up your website speed.', 'leadership-coach'),
                'active_callback' => 'coachpress_lite_ed_localgoogle_fonts'
            )
        )
    );

    ob_start(); ?>

    <span style="margin-bottom: 5px;display: block;"><?php esc_html_e('Click the button to reset the local fonts cache', 'leadership-coach'); ?></span>

    <input type="button" class="button button-primary leadership-coach-flush-local-fonts-button" name="leadership-coach-flush-local-fonts-button" value="<?php esc_attr_e('Flush Local Font Files', 'leadership-coach'); ?>" />
<?php
    $leadership_coach_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __('Flush Local Fonts Cache', 'leadership-coach'),
            'section'       => 'typography_settings',
            'description'   => $leadership_coach_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'coachpress_lite_ed_localgoogle_fonts'
        )
    );

    /** Enhanced Color Palette Section */
    $wp_customize->add_section(
        'enhanced_color_palette',
        array(
            'title'    => __('Enhanced Color Palette', 'leadership-coach'),
            'priority' => 5,
            'panel'    => 'appearance_settings',
        )
    );

    /** Primary Purple Color */
    $wp_customize->add_setting(
        'primary_purple',
        array(
            'default'           => '#9B5DE5',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_purple',
            array(
                'label'    => __('Primary Purple', 'leadership-coach'),
                'section'  => 'enhanced_color_palette',
                'priority' => 5,
            )
        )
    );

    /** Secondary Lilac Color */
    $wp_customize->add_setting(
        'secondary_lilac',
        array(
            'default'           => '#CBA6F7',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'secondary_lilac',
            array(
                'label'    => __('Secondary Lilac', 'leadership-coach'),
                'section'  => 'enhanced_color_palette',
                'priority' => 10,
            )
        )
    );

    /** Accent Pink Color */
    $wp_customize->add_setting(
        'accent_pink',
        array(
            'default'           => '#F6C6EA',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'accent_pink',
            array(
                'label'    => __('Accent Pink', 'leadership-coach'),
                'section'  => 'enhanced_color_palette',
                'priority' => 15,
            )
        )
    );

    /** Light Background Color */
    $wp_customize->add_setting(
        'light_background',
        array(
            'default'           => '#F8F6FA',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'light_background',
            array(
                'label'    => __('Light Background', 'leadership-coach'),
                'section'  => 'enhanced_color_palette',
                'priority' => 20,
            )
        )
    );

    /** Dark Text Color */
    $wp_customize->add_setting(
        'dark_text',
        array(
            'default'           => '#2E2C38',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dark_text',
            array(
                'label'    => __('Dark Text', 'leadership-coach'),
                'section'  => 'enhanced_color_palette',
                'priority' => 25,
            )
        )
    );

    /** Enhanced Typography Section */
    $wp_customize->add_section(
        'enhanced_typography',
        array(
            'title'    => __('Enhanced Typography', 'leadership-coach'),
            'priority' => 25,
            'panel'    => 'appearance_settings',
        )
    );

    /** Heading Font Selection */
    $wp_customize->add_setting(
        'heading_font',
        array(
            'default'           => 'Nunito',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'heading_font',
        array(
            'label'    => __('Heading Font', 'leadership-coach'),
            'section'  => 'enhanced_typography',
            'type'     => 'select',
            'choices'  => array(
                'Nunito'  => 'Nunito',
                'Poppins' => 'Poppins',
            ),
            'priority' => 5,
        )
    );

    /** Body Font Selection */
    $wp_customize->add_setting(
        'body_font',
        array(
            'default'           => 'Lora',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'body_font',
        array(
            'label'    => __('Body Font', 'leadership-coach'),
            'section'  => 'enhanced_typography',
            'type'     => 'select',
            'choices'  => array(
                'Lora'      => 'Lora',
                'Open Sans' => 'Open Sans',
            ),
            'priority' => 10,
        )
    );

    /** Move Background Image section to appearance panel */
    $wp_customize->get_section('colors')->panel              = 'appearance_settings';
    $wp_customize->get_section('colors')->priority           = 30;
    $wp_customize->get_section('background_image')->panel    = 'appearance_settings';
    $wp_customize->get_section('background_image')->priority = 35;

    $wp_customize->add_setting(
        'header_contact_button',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'header_contact_button',
        array(
            'label'         => __('Header Contact Button', 'leadership-coach'),
            'description'   => __('This button shows only on header layout 2.', 'leadership-coach'),
            'section'       => 'header_settings',
            'type'          => 'text',
        )
    );

    $wp_customize->add_setting(
        'header_contact_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'header_contact_url',
        array(
            'label'     => __('Header Contact Button', 'leadership-coach'),
            'section'   => 'header_settings',
            'type'      => 'url',
        )
    );
}

add_action('customize_register', 'leadership_coach_customizer_register', 40);

/**
 * Add custom meta boxes for About Me page
 */
function leadership_coach_add_about_meta_boxes()
{
    add_meta_box(
        'coach-credentials',
        __('Coach Credentials', 'leadership-coach'),
        'leadership_coach_credentials_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'coach-experience',
        __('Professional Experience', 'leadership-coach'),
        'leadership_coach_experience_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'coach-specializations',
        __('Areas of Specialization', 'leadership-coach'),
        'leadership_coach_specializations_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'coach-philosophy',
        __('Coaching Philosophy', 'leadership-coach'),
        'leadership_coach_philosophy_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'leadership_coach_add_about_meta_boxes');

/**
 * Credentials meta box callback
 */
function leadership_coach_credentials_callback($post)
{
    wp_nonce_field('leadership_coach_save_about_meta', 'leadership_coach_about_nonce');
    $value = get_post_meta($post->ID, '_coach_credentials', true);
    echo '<textarea name="coach_credentials" rows="5" cols="50" style="width:100%;">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('Enter your coaching credentials and certifications.', 'leadership-coach') . '</p>';
}

/**
 * Experience meta box callback
 */
function leadership_coach_experience_callback($post)
{
    $value = get_post_meta($post->ID, '_coach_experience', true);
    echo '<textarea name="coach_experience" rows="5" cols="50" style="width:100%;">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('Describe your professional experience and background.', 'leadership-coach') . '</p>';
}

/**
 * Specializations meta box callback
 */
function leadership_coach_specializations_callback($post)
{
    $value = get_post_meta($post->ID, '_coach_specializations', true);
    echo '<textarea name="coach_specializations" rows="5" cols="50" style="width:100%;">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('List your areas of specialization and expertise.', 'leadership-coach') . '</p>';
}

/**
 * Philosophy meta box callback
 */
function leadership_coach_philosophy_callback($post)
{
    $value = get_post_meta($post->ID, '_coach_philosophy', true);
    echo '<textarea name="coach_philosophy" rows="5" cols="50" style="width:100%;">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('Share your coaching philosophy and approach.', 'leadership-coach') . '</p>';
}

/**
 * Save About Me custom fields
 */
function leadership_coach_save_about_meta($post_id)
{
    // Check if nonce is valid
    if (! isset($_POST['leadership_coach_about_nonce']) || ! wp_verify_nonce($_POST['leadership_coach_about_nonce'], 'leadership_coach_save_about_meta')) {
        return;
    }

    // Check if user has permissions to save data
    if (! current_user_can('edit_page', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Save credentials
    if (isset($_POST['coach_credentials'])) {
        update_post_meta($post_id, '_coach_credentials', sanitize_textarea_field($_POST['coach_credentials']));
    }

    // Save experience
    if (isset($_POST['coach_experience'])) {
        update_post_meta($post_id, '_coach_experience', sanitize_textarea_field($_POST['coach_experience']));
    }

    // Save specializations
    if (isset($_POST['coach_specializations'])) {
        update_post_meta($post_id, '_coach_specializations', sanitize_textarea_field($_POST['coach_specializations']));
    }

    // Save philosophy
    if (isset($_POST['coach_philosophy'])) {
        update_post_meta($post_id, '_coach_philosophy', sanitize_textarea_field($_POST['coach_philosophy']));
    }
}
add_action('save_post', 'leadership_coach_save_about_meta');

/**
 * Create appointments database table on theme activation
 */
function leadership_coach_create_appointments_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'coaching_appointments';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        client_name varchar(100) NOT NULL,
        client_email varchar(100) NOT NULL,
        client_phone varchar(20) DEFAULT '',
        appointment_date date NOT NULL,
        appointment_time time NOT NULL,
        service_type varchar(100) DEFAULT '',
        status varchar(20) DEFAULT 'pending',
        google_calendar_event_id varchar(255) DEFAULT '',
        notes text DEFAULT '',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY appointment_date (appointment_date),
        KEY client_email (client_email),
        KEY status (status)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Store database version for future updates
    add_option('leadership_coach_appointments_db_version', '1.0');
}

/**
 * Hook into theme activation
 */
function leadership_coach_theme_activation()
{
    leadership_coach_create_appointments_table();

    // Flush rewrite rules to ensure custom post types work
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'leadership_coach_theme_activation');

/**
 * Include appointment model
 */
require_once get_stylesheet_directory() . '/inc/models/class-appointment.php';

/**
 * Initialize appointment model instance
 */
function leadership_coach_get_appointment_model()
{
    static $appointment_model = null;

    if (null === $appointment_model) {
        $appointment_model = new Leadership_Coach_Appointment();
    }

    return $appointment_model;
}

/**
 * Enqueue booking system scripts and styles
 */
function leadership_coach_enqueue_booking_scripts()
{
    // Only load on simple calendar or booking pages (avoid Calendly page to prevent conflicts)
    if (is_page_template('page-calendar-simple.php') || is_page('booking')) {
        $my_theme = wp_get_theme();
        $version  = $my_theme['Version'];

        // Enqueue calendar styles
        wp_enqueue_style(
            'leadership-coach-calendar-styles',
            get_stylesheet_directory_uri() . '/assets/css/calendar-styles.css',
            array('leadership-coach'),
            $version
        );

        // Enqueue booking system CSS
        wp_enqueue_style(
            'leadership-coach-booking-system',
            get_stylesheet_directory_uri() . '/assets/css/booking-system.css',
            array('leadership-coach-calendar-styles'),
            $version
        );

        // Enqueue calendar widget JavaScript
        wp_enqueue_script(
            'leadership-coach-calendar-widget',
            get_stylesheet_directory_uri() . '/inc/js/calendar-widget.js',
            array('jquery'),
            $version,
            true
        );

        // Enqueue booking system JavaScript
        wp_enqueue_script(
            'leadership-coach-booking-system',
            get_stylesheet_directory_uri() . '/inc/js/booking-system.js',
            array('jquery', 'leadership-coach-calendar-widget'),
            $version,
            true
        );

        // Localize calendar widget script
        wp_localize_script('leadership-coach-calendar-widget', 'calendarWidget', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('calendar_widget_nonce'),
            'locale' => get_locale(),
            'strings' => array(
                'january' => __('January', 'leadership-coach'),
                'february' => __('February', 'leadership-coach'),
                'march' => __('March', 'leadership-coach'),
                'april' => __('April', 'leadership-coach'),
                'may' => __('May', 'leadership-coach'),
                'june' => __('June', 'leadership-coach'),
                'july' => __('July', 'leadership-coach'),
                'august' => __('August', 'leadership-coach'),
                'september' => __('September', 'leadership-coach'),
                'october' => __('October', 'leadership-coach'),
                'november' => __('November', 'leadership-coach'),
                'december' => __('December', 'leadership-coach'),
                'loadingSlots' => __('Loading available time slots...', 'leadership-coach'),
                'noSlotsAvailable' => __('No time slots available for this date', 'leadership-coach'),
                'selectDate' => __('Please select a date', 'leadership-coach')
            )
        ));

        // Localize booking system script
        wp_localize_script('leadership-coach-booking-system', 'bookingSystem', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('booking_system_nonce'),
            'strings' => array(
                'loading' => __('Loading...', 'leadership-coach'),
                'selectTime' => __('Select a time...', 'leadership-coach'),
                'selectDateFirst' => __('Select date first...', 'leadership-coach'),
                'noSlotsAvailable' => __('No time slots available for this date', 'leadership-coach'),
                'errorLoadingSlots' => __('Error loading available time slots. Please try again.', 'leadership-coach'),
                'bookingError' => __('Error booking appointment. Please try again.', 'leadership-coach'),
                'fieldRequired' => __('This field is required.', 'leadership-coach'),
                'invalidEmail' => __('Please enter a valid email address.', 'leadership-coach'),
                'dateInFuture' => __('Please select a future date.', 'leadership-coach'),
                'selectDateFirst' => __('Please select a date from the calendar.', 'leadership-coach'),
                'selectTimeFirst' => __('Please select a time slot.', 'leadership-coach')
            )
        ));
    }
}
add_action('wp_enqueue_scripts', 'leadership_coach_enqueue_booking_scripts', 20);

/**
 * AJAX handler for getting available time slots
 */
function leadership_coach_get_available_time_slots()
{
    // Verify nonce
    if (! wp_verify_nonce($_POST['nonce'], 'booking_system_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'leadership-coach')));
    }

    $date = sanitize_text_field($_POST['date']);

    if (empty($date)) {
        wp_send_json_error(array('message' => __('Date is required.', 'leadership-coach')));
    }

    // Validate date format
    if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        wp_send_json_error(array('message' => __('Invalid date format.', 'leadership-coach')));
    }

    // Check if date is in the future
    if (strtotime($date) <= strtotime('today')) {
        wp_send_json_error(array('message' => __('Please select a future date.', 'leadership-coach')));
    }

    // Get available slots
    $appointment_model = leadership_coach_get_appointment_model();
    $available_slots = $appointment_model->get_available_slots($date);

    wp_send_json_success(array('slots' => $available_slots));
}
add_action('wp_ajax_get_available_time_slots', 'leadership_coach_get_available_time_slots');
add_action('wp_ajax_nopriv_get_available_time_slots', 'leadership_coach_get_available_time_slots');

/**
 * AJAX handler for getting month availability data
 */
function leadership_coach_get_month_availability()
{
    // Verify nonce
    if (! wp_verify_nonce($_POST['nonce'], 'calendar_widget_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'leadership-coach')));
    }

    $year = intval($_POST['year']);
    $month = intval($_POST['month']);

    if (empty($year) || empty($month) || $month < 1 || $month > 12) {
        wp_send_json_error(array('message' => __('Invalid year or month.', 'leadership-coach')));
    }

    // Get appointment model
    $appointment_model = leadership_coach_get_appointment_model();

    // Get appointments for the month
    $appointments = $appointment_model->get_appointments_by_month($year, $month);

    // Calculate availability for each day
    $availability_data = array();
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $available_slots = $appointment_model->get_available_slots($date);

        $availability_data[$date] = array(
            'available_slots' => count($available_slots),
            'total_slots' => count($appointment_model->generate_time_slots($date)),
            'is_available' => count($available_slots) > 0
        );
    }

    wp_send_json_success(array('availability' => $availability_data));
}
add_action('wp_ajax_get_month_availability', 'leadership_coach_get_month_availability');
add_action('wp_ajax_nopriv_get_month_availability', 'leadership_coach_get_month_availability');

/**
 * AJAX handler for booking appointments
 */
function leadership_coach_book_appointment()
{
    // Verify nonce
    if (! wp_verify_nonce($_POST['appointment_nonce'], 'book_appointment')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'leadership-coach')));
    }

    // Sanitize and validate input data
    $appointment_data = array(
        'client_name' => sanitize_text_field($_POST['client_name']),
        'client_email' => sanitize_email($_POST['client_email']),
        'client_phone' => sanitize_text_field($_POST['client_phone']),
        'appointment_date' => sanitize_text_field($_POST['appointment_date']),
        'appointment_time' => sanitize_text_field($_POST['appointment_time']),
        'service_type' => sanitize_text_field($_POST['service_type']),
        'notes' => sanitize_textarea_field($_POST['appointment_notes']),
        'status' => 'pending'
    );

    // Validate required fields
    $required_fields = array('client_name', 'client_email', 'appointment_date', 'appointment_time', 'service_type');
    foreach ($required_fields as $field) {
        if (empty($appointment_data[$field])) {
            wp_send_json_error(array('message' => sprintf(__('%s is required.', 'leadership-coach'), ucfirst(str_replace('_', ' ', $field)))));
        }
    }

    // Validate email
    if (! is_email($appointment_data['client_email'])) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'leadership-coach')));
    }

    // Validate date is in the future
    if (strtotime($appointment_data['appointment_date']) <= strtotime('today')) {
        wp_send_json_error(array('message' => __('Please select a future date.', 'leadership-coach')));
    }

    // Create appointment
    $appointment_model = leadership_coach_get_appointment_model();
    $result = $appointment_model->create($appointment_data);

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    // Send confirmation emails
    leadership_coach_send_appointment_confirmation($result, $appointment_data);

    wp_send_json_success(array(
        'message' => __('Your appointment has been booked successfully! You will receive a confirmation email shortly.', 'leadership-coach'),
        'appointment_id' => $result
    ));
}
add_action('wp_ajax_book_appointment', 'leadership_coach_book_appointment');
add_action('wp_ajax_nopriv_book_appointment', 'leadership_coach_book_appointment');



/**
 * Register Services Custom Post Type
 */
function leadership_coach_register_services_post_type()
{
    $labels = array(
        'name'                  => _x('Services', 'Post type general name', 'leadership-coach'),
        'singular_name'         => _x('Service', 'Post type singular name', 'leadership-coach'),
        'menu_name'             => _x('Coaching Services', 'Admin Menu text', 'leadership-coach'),
        'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'leadership-coach'),
        'add_new'               => __('Add New', 'leadership-coach'),
        'add_new_item'          => __('Add New Service', 'leadership-coach'),
        'new_item'              => __('New Service', 'leadership-coach'),
        'edit_item'             => __('Edit Service', 'leadership-coach'),
        'view_item'             => __('View Service', 'leadership-coach'),
        'all_items'             => __('All Services', 'leadership-coach'),
        'search_items'          => __('Search Services', 'leadership-coach'),
        'parent_item_colon'     => __('Parent Services:', 'leadership-coach'),
        'not_found'             => __('No services found.', 'leadership-coach'),
        'not_found_in_trash'    => __('No services found in Trash.', 'leadership-coach'),
        'featured_image'        => _x('Service Image', 'Overrides the "Featured Image" phrase', 'leadership-coach'),
        'set_featured_image'    => _x('Set service image', 'Overrides the "Set featured image" phrase', 'leadership-coach'),
        'remove_featured_image' => _x('Remove service image', 'Overrides the "Remove featured image" phrase', 'leadership-coach'),
        'use_featured_image'    => _x('Use as service image', 'Overrides the "Use as featured image" phrase', 'leadership-coach'),
        'archives'              => _x('Service archives', 'The post type archive label', 'leadership-coach'),
        'insert_into_item'      => _x('Insert into service', 'Overrides the "Insert into post" phrase', 'leadership-coach'),
        'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the "Uploaded to this post" phrase', 'leadership-coach'),
        'filter_items_list'     => _x('Filter services list', 'Screen reader text for the filter links', 'leadership-coach'),
        'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination', 'leadership-coach'),
        'items_list'            => _x('Services list', 'Screen reader text for the items list', 'leadership-coach'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'service'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('coaching_service', $args);
}
add_action('init', 'leadership_coach_register_services_post_type');

/**
 * Add meta boxes for Services
 */
function leadership_coach_add_service_meta_boxes()
{
    add_meta_box(
        'service-details',
        __('Service Details', 'leadership-coach'),
        'leadership_coach_service_details_callback',
        'coaching_service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'leadership_coach_add_service_meta_boxes');

/**
 * Service details meta box callback
 */
function leadership_coach_service_details_callback($post)
{
    wp_nonce_field('leadership_coach_save_service_meta', 'leadership_coach_service_nonce');

    $price = get_post_meta($post->ID, '_service_price', true);
    $duration = get_post_meta($post->ID, '_service_duration', true);
    $booking_type = get_post_meta($post->ID, '_service_booking_type', true);
    $is_bookable = get_post_meta($post->ID, '_service_is_bookable', true);
?>

    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="service_price"><?php esc_html_e('Price', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter the service price (e.g., $150, €120, Free)', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="service_duration"><?php esc_html_e('Duration', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter the service duration (e.g., 60 minutes, 2 hours)', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="service_booking_type"><?php esc_html_e('Booking Type', 'leadership-coach'); ?></label>
            </th>
            <td>
                <select id="service_booking_type" name="service_booking_type">
                    <option value="free" <?php selected($booking_type, 'free'); ?>><?php esc_html_e('Free', 'leadership-coach'); ?></option>
                    <option value="paid" <?php selected($booking_type, 'paid'); ?>><?php esc_html_e('Paid', 'leadership-coach'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select whether this service is free or paid.', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="service_is_bookable"><?php esc_html_e('Bookable', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="checkbox" id="service_is_bookable" name="service_is_bookable" value="1" <?php checked($is_bookable, '1'); ?> />
                <label for="service_is_bookable"><?php esc_html_e('Allow online booking for this service', 'leadership-coach'); ?></label>
            </td>
        </tr>
    </table>

<?php
}

/**
 * Save Service custom fields
 */
function leadership_coach_save_service_meta($post_id)
{
    // Check if nonce is valid
    if (! isset($_POST['leadership_coach_service_nonce']) || ! wp_verify_nonce($_POST['leadership_coach_service_nonce'], 'leadership_coach_save_service_meta')) {
        return;
    }

    // Check if user has permissions to save data
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Save price
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
    }

    // Save duration
    if (isset($_POST['service_duration'])) {
        update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
    }

    // Save booking type
    if (isset($_POST['service_booking_type'])) {
        update_post_meta($post_id, '_service_booking_type', sanitize_text_field($_POST['service_booking_type']));
    }

    // Save bookable status
    $is_bookable = isset($_POST['service_is_bookable']) ? '1' : '0';
    update_post_meta($post_id, '_service_is_bookable', $is_bookable);
}
add_action('save_post', 'leadership_coach_save_service_meta');

/**
 * Register Testimonials Custom Post Type
 */
function leadership_coach_register_testimonials_post_type()
{
    $labels = array(
        'name'                  => _x('Testimonials', 'Post type general name', 'leadership-coach'),
        'singular_name'         => _x('Testimonial', 'Post type singular name', 'leadership-coach'),
        'menu_name'             => _x('Testimonials', 'Admin Menu text', 'leadership-coach'),
        'name_admin_bar'        => _x('Testimonial', 'Add New on Toolbar', 'leadership-coach'),
        'add_new'               => __('Add New', 'leadership-coach'),
        'add_new_item'          => __('Add New Testimonial', 'leadership-coach'),
        'new_item'              => __('New Testimonial', 'leadership-coach'),
        'edit_item'             => __('Edit Testimonial', 'leadership-coach'),
        'view_item'             => __('View Testimonial', 'leadership-coach'),
        'all_items'             => __('All Testimonials', 'leadership-coach'),
        'search_items'          => __('Search Testimonials', 'leadership-coach'),
        'parent_item_colon'     => __('Parent Testimonials:', 'leadership-coach'),
        'not_found'             => __('No testimonials found.', 'leadership-coach'),
        'not_found_in_trash'    => __('No testimonials found in Trash.', 'leadership-coach'),
        'featured_image'        => _x('Client Photo', 'Overrides the "Featured Image" phrase', 'leadership-coach'),
        'set_featured_image'    => _x('Set client photo', 'Overrides the "Set featured image" phrase', 'leadership-coach'),
        'remove_featured_image' => _x('Remove client photo', 'Overrides the "Remove featured image" phrase', 'leadership-coach'),
        'use_featured_image'    => _x('Use as client photo', 'Overrides the "Use as featured image" phrase', 'leadership-coach'),
        'archives'              => _x('Testimonial archives', 'The post type archive label', 'leadership-coach'),
        'insert_into_item'      => _x('Insert into testimonial', 'Overrides the "Insert into post" phrase', 'leadership-coach'),
        'uploaded_to_this_item' => _x('Uploaded to this testimonial', 'Overrides the "Uploaded to this post" phrase', 'leadership-coach'),
        'filter_items_list'     => _x('Filter testimonials list', 'Screen reader text for the filter links', 'leadership-coach'),
        'items_list_navigation' => _x('Testimonials list navigation', 'Screen reader text for the pagination', 'leadership-coach'),
        'items_list'            => _x('Testimonials list', 'Screen reader text for the items list', 'leadership-coach'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $args);
}
add_action('init', 'leadership_coach_register_testimonials_post_type');

/**
 * Add meta boxes for Testimonials
 */
function leadership_coach_add_testimonial_meta_boxes()
{
    add_meta_box(
        'testimonial-details',
        __('Testimonial Details', 'leadership-coach'),
        'leadership_coach_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'leadership_coach_add_testimonial_meta_boxes');

/**
 * Testimonial details meta box callback
 */
function leadership_coach_testimonial_details_callback($post)
{
    wp_nonce_field('leadership_coach_save_testimonial_meta', 'leadership_coach_testimonial_nonce');

    $client_name = get_post_meta($post->ID, '_testimonial_client_name', true);
    $client_position = get_post_meta($post->ID, '_testimonial_client_position', true);
    $client_company = get_post_meta($post->ID, '_testimonial_client_company', true);
    $rating = get_post_meta($post->ID, '_testimonial_rating', true);
    $featured = get_post_meta($post->ID, '_testimonial_featured', true);
?>

    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="testimonial_client_name"><?php esc_html_e('Client Name', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="text" id="testimonial_client_name" name="testimonial_client_name" value="<?php echo esc_attr($client_name); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter the client\'s full name.', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="testimonial_client_position"><?php esc_html_e('Client Position', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="text" id="testimonial_client_position" name="testimonial_client_position" value="<?php echo esc_attr($client_position); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter the client\'s job title or position.', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="testimonial_client_company"><?php esc_html_e('Client Company', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="text" id="testimonial_client_company" name="testimonial_client_company" value="<?php echo esc_attr($client_company); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter the client\'s company or organization.', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="testimonial_rating"><?php esc_html_e('Rating', 'leadership-coach'); ?></label>
            </th>
            <td>
                <select id="testimonial_rating" name="testimonial_rating">
                    <option value=""><?php esc_html_e('Select Rating', 'leadership-coach'); ?></option>
                    <option value="1" <?php selected($rating, '1'); ?>><?php esc_html_e('1 Star', 'leadership-coach'); ?></option>
                    <option value="2" <?php selected($rating, '2'); ?>><?php esc_html_e('2 Stars', 'leadership-coach'); ?></option>
                    <option value="3" <?php selected($rating, '3'); ?>><?php esc_html_e('3 Stars', 'leadership-coach'); ?></option>
                    <option value="4" <?php selected($rating, '4'); ?>><?php esc_html_e('4 Stars', 'leadership-coach'); ?></option>
                    <option value="5" <?php selected($rating, '5'); ?>><?php esc_html_e('5 Stars', 'leadership-coach'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the star rating for this testimonial.', 'leadership-coach'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="testimonial_featured"><?php esc_html_e('Featured Testimonial', 'leadership-coach'); ?></label>
            </th>
            <td>
                <input type="checkbox" id="testimonial_featured" name="testimonial_featured" value="1" <?php checked($featured, '1'); ?> />
                <label for="testimonial_featured"><?php esc_html_e('Mark as featured testimonial', 'leadership-coach'); ?></label>
                <p class="description"><?php esc_html_e('Featured testimonials can be highlighted on the homepage.', 'leadership-coach'); ?></p>
            </td>
        </tr>
    </table>

<?php
}

/**
 * Save Testimonial custom fields
 */
function leadership_coach_save_testimonial_meta($post_id)
{
    // Check if nonce is valid
    if (! isset($_POST['leadership_coach_testimonial_nonce']) || ! wp_verify_nonce($_POST['leadership_coach_testimonial_nonce'], 'leadership_coach_save_testimonial_meta')) {
        return;
    }

    // Check if user has permissions to save data
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Save client name
    if (isset($_POST['testimonial_client_name'])) {
        update_post_meta($post_id, '_testimonial_client_name', sanitize_text_field($_POST['testimonial_client_name']));
    }

    // Save client position
    if (isset($_POST['testimonial_client_position'])) {
        update_post_meta($post_id, '_testimonial_client_position', sanitize_text_field($_POST['testimonial_client_position']));
    }

    // Save client company
    if (isset($_POST['testimonial_client_company'])) {
        update_post_meta($post_id, '_testimonial_client_company', sanitize_text_field($_POST['testimonial_client_company']));
    }

    // Save rating
    if (isset($_POST['testimonial_rating'])) {
        update_post_meta($post_id, '_testimonial_rating', sanitize_text_field($_POST['testimonial_rating']));
    }

    // Save featured status
    $featured = isset($_POST['testimonial_featured']) ? '1' : '0';
    update_post_meta($post_id, '_testimonial_featured', $featured);
}
add_action('save_post', 'leadership_coach_save_testimonial_meta');

/**
 * Handle contact form submission
 */
function leadership_coach_handle_contact_form()
{
    // Check if form was submitted
    if (! isset($_POST['contact_nonce']) || ! wp_verify_nonce($_POST['contact_nonce'], 'leadership_coach_contact_form')) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Check honeypot field
    if (! empty($_POST['website'])) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Sanitize and validate form data
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Validate email
    if (! is_email($email)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Prepare email content
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $email_subject = sprintf(__('New Contact Form Submission from %s', 'leadership-coach'), $site_name);

    $email_message = sprintf(
        __("You have received a new contact form submission:\n\nName: %s\nEmail: %s\nPhone: %s\nSubject: %s\n\nMessage:\n%s\n\n---\nThis email was sent from the contact form on %s", 'leadership-coach'),
        $name,
        $email,
        $phone,
        $subject,
        $message,
        home_url()
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    // Send email to admin
    $admin_sent = wp_mail($admin_email, $email_subject, $email_message, $headers);

    // Send auto-responder to client
    $client_subject = sprintf(__('Thank you for contacting %s', 'leadership-coach'), $site_name);
    $client_message = sprintf(
        __("Dear %s,\n\nThank you for reaching out to us! We have received your message and will get back to you within 24 hours.\n\nHere's a copy of your message:\n\nSubject: %s\nMessage: %s\n\nBest regards,\n%s Team\n\n---\nThis is an automated response. Please do not reply to this email.", 'leadership-coach'),
        $name,
        $subject,
        $message,
        $site_name
    );

    $client_headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    );

    $client_sent = wp_mail($email, $client_subject, $client_message, $client_headers);

    // Redirect based on email sending success
    if ($admin_sent) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_leadership_coach_contact_form', 'leadership_coach_handle_contact_form');
add_action('admin_post_nopriv_leadership_coach_contact_form', 'leadership_coach_handle_contact_form');

/**
 * Handle appointment request form submission
 */
function leadership_coach_handle_appointment_request()
{
    // Check if form was submitted
    if (! isset($_POST['appointment_request_nonce']) || ! wp_verify_nonce($_POST['appointment_request_nonce'], 'book_appointment_request')) {
        wp_redirect(add_query_arg('appointment', 'error', wp_get_referer()));
        exit;
    }

    // Sanitize and validate form data
    $client_name = sanitize_text_field($_POST['client_name']);
    $client_email = sanitize_email($_POST['client_email']);
    $client_phone = sanitize_text_field($_POST['client_phone']);
    $preferred_time = sanitize_text_field($_POST['preferred_time']);
    $session_type = sanitize_text_field($_POST['session_type']);
    $appointment_notes = sanitize_textarea_field($_POST['appointment_notes']);

    // Validate required fields
    if (empty($client_name) || empty($client_email) || empty($session_type)) {
        wp_redirect(add_query_arg('appointment', 'error', wp_get_referer()));
        exit;
    }

    // Validate email
    if (! is_email($client_email)) {
        wp_redirect(add_query_arg('appointment', 'error', wp_get_referer()));
        exit;
    }

    // Prepare email content
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $email_subject = sprintf(__('New Appointment Request from %s', 'leadership-coach'), $site_name);

    // Format preferred time
    $preferred_time_text = '';
    switch ($preferred_time) {
        case 'morning':
            $preferred_time_text = __('Morning (9 AM - 12 PM)', 'leadership-coach');
            break;
        case 'afternoon':
            $preferred_time_text = __('Afternoon (12 PM - 5 PM)', 'leadership-coach');
            break;
        case 'evening':
            $preferred_time_text = __('Evening (5 PM - 8 PM)', 'leadership-coach');
            break;
        default:
            $preferred_time_text = __('No preference specified', 'leadership-coach');
    }

    // Format session type
    $session_type_text = '';
    switch ($session_type) {
        case 'consultation':
            $session_type_text = __('30-Minute Free Consultation', 'leadership-coach');
            break;
        case 'coaching_session':
            $session_type_text = __('Leadership Coaching Session', 'leadership-coach');
            break;
        case 'strategy_session':
            $session_type_text = __('Strategy Planning Session', 'leadership-coach');
            break;
        case 'team_coaching':
            $session_type_text = __('Team Coaching Session', 'leadership-coach');
            break;
        default:
            $session_type_text = $session_type;
    }

    $email_message = sprintf(
        __("You have received a new appointment request:\n\nClient Details:\n- Name: %s\n- Email: %s\n- Phone: %s\n\nAppointment Preferences:\n- Session Type: %s\n- Preferred Time: %s\n\nMessage:\n%s\n\n---\nThis appointment request was sent from the calendar page on %s", 'leadership-coach'),
        $client_name,
        $client_email,
        $client_phone ?: __('Not provided', 'leadership-coach'),
        $session_type_text,
        $preferred_time_text,
        $appointment_notes ?: __('No additional notes', 'leadership-coach'),
        home_url()
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $client_name . ' <' . $client_email . '>'
    );

    // Send email to admin
    $admin_sent = wp_mail($admin_email, $email_subject, $email_message, $headers);

    // Send auto-responder to client
    $client_subject = sprintf(__('Appointment Request Received - %s', 'leadership-coach'), $site_name);
    $client_message = sprintf(
        __("Dear %s,\n\nThank you for your appointment request! We have received your information and will contact you within 24 hours to schedule your %s.\n\nYour Request Details:\n- Session Type: %s\n- Preferred Time: %s\n\nWe look forward to working with you on your leadership journey.\n\nBest regards,\n%s Team\n\n---\nThis is an automated response. Please do not reply to this email.", 'leadership-coach'),
        $client_name,
        $session_type_text,
        $session_type_text,
        $preferred_time_text,
        $site_name
    );

    $client_headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    );

    $client_sent = wp_mail($client_email, $client_subject, $client_message, $client_headers);

    // Redirect based on email sending success
    if ($admin_sent) {
        wp_redirect(add_query_arg('appointment', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('appointment', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_book_appointment_request', 'leadership_coach_handle_appointment_request');
add_action('admin_post_nopriv_book_appointment_request', 'leadership_coach_handle_appointment_request');

/**
 * Add contact information settings to Customizer
 */
function leadership_coach_contact_customizer($wp_customize)
{
    // Contact Information Section
    $wp_customize->add_section(
        'contact_information',
        array(
            'title'    => __('Contact Information', 'leadership-coach'),
            'priority' => 35,
        )
    );

    // Phone Number
    $wp_customize->add_setting(
        'phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'phone',
        array(
            'label'    => __('Phone Number', 'leadership-coach'),
            'section'  => 'contact_information',
            'type'     => 'text',
            'priority' => 5,
        )
    );

    // Email Address
    $wp_customize->add_setting(
        'email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'email',
        array(
            'label'    => __('Email Address', 'leadership-coach'),
            'section'  => 'contact_information',
            'type'     => 'email',
            'priority' => 10,
        )
    );

    // Address
    $wp_customize->add_setting(
        'address',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'address',
        array(
            'label'    => __('Address', 'leadership-coach'),
            'section'  => 'contact_information',
            'type'     => 'textarea',
            'priority' => 15,
        )
    );
}
add_action('customize_register', 'leadership_coach_contact_customizer');

/**
 * Form 
 */
function coachpress_lite_header_contact()
{
    $phone = get_theme_mod('phone');
    $email = get_theme_mod('email');

    if ($phone || $email) :
        echo '<div class="header-left">';
        if (!empty($phone)) echo '<div class="header-block"><i class="fas fa-phone"></i><a href="tel:' . preg_replace('/[^\d+]/', '', $phone) . '">' . esc_html($phone) . '</a></div>';
        if (!empty($email)) echo '<div class="header-block"><i class="fas fa-envelope"></i><a href="mailto:' . sanitize_email($email) . '">' . sanitize_email($email) . '</a></div>';
        echo '</div>';
    endif;
}

/**
 * Header Start
 */
function coachpress_lite_header()
{
    $ed_cart       = get_theme_mod('ed_shopping_cart', true);
    $ed_search     = get_theme_mod('ed_header_search', true);
    $header_layout = get_theme_mod('header_layout', 'two');

    if ($header_layout == 'one') {
        $class = 'center';
    } else {
        $class = 'left';
    }

?>
    <header id="masthead" class="site-header style-<?php echo esc_attr($header_layout); ?>" itemscope itemtype="http://schema.org/WPHeader">
        <div class="header-top">
            <div class="container">
                <?php
                coachpress_lite_header_contact();

                if (coachpress_lite_social_links(false)) {
                    echo '<div class="header-center">
                        <div class="header-social">';
                    coachpress_lite_social_links();
                    echo '</div>
                    </div>';
                } ?>

                <div class="header-right">
                    <?php
                    if ($ed_search) coachpress_lite_header_search();
                    if (coachpress_lite_is_woocommerce_activated() && $ed_cart) {
                        echo '<div class="header-cart">';
                        coachpress_lite_wc_cart_count();
                        echo '</div>';
                    } ?>
                    <?php coachpress_lite_secondary_navigation(); ?>
                </div>
            </div>
        </div> <!-- .header-top end -->

        <div class="header-main">
            <div class="container">
                <?php
                coachpress_lite_site_branding();
                if ($header_layout == 'two') echo '<div class="nav-wrap">';
                coachpress_lite_primary_navigation();
                if ($header_layout == 'two') leadership_coach_contact_button();
                if ($header_layout == 'two') echo '</div>';
                ?>
            </div>
        </div>
    </header>

    <?php
    coachpress_lite_mobile_navigation();
}

/**
 * Form 
 */
function leadership_coach_contact_button()
{
    $header_contact_button = get_theme_mod('header_contact_button');
    $header_contact_url = get_theme_mod('header_contact_url');
    if ($header_contact_button && $header_contact_url) : ?>
        <div class="button-wrap">
            <a href="<?php echo esc_url($header_contact_url); ?>" class="btn-readmore btn-two"><?php echo esc_html($header_contact_button); ?></a>
        </div>
    <?php
    endif;
}

/**
 * Footer Bottom
 */
function coachpress_lite_footer_bottom()
{ ?>
    <div class="footer-bottom">
        <div class="footer-menu">
            <div class="container">
                <?php coachpress_lite_footer_navigation(); ?>
            </div>
        </div>
        <div class="site-info">
            <div class="container">
                <?php
                coachpress_lite_get_footer_copyright();
                echo esc_html__(' Leadership Coach | Developed By ', 'leadership-coach');
                echo '<a href="' . esc_url('https://blossomthemes.com/wordpress-themes/leadership-coach/') . '" rel="nofollow" target="_blank">' . esc_html__('Blossom Themes', 'leadership-coach') . '</a>.';
                printf(esc_html__(' Powered by %s. ', 'leadership-coach'), '<a href="' . esc_url(__('https://wordpress.org/', 'leadership-coach')) . '" target="_blank">WordPress</a>');
                if (function_exists('the_privacy_policy_link')) {
                    the_privacy_policy_link();
                }
                ?>
            </div>
        </div>
        <button class="back-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M6.101 359.293L25.9 379.092c4.686 4.686 12.284 4.686 16.971 0L224 198.393l181.13 180.698c4.686 4.686 12.284 4.686 16.971 0l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L232.485 132.908c-4.686-4.686-12.284-4.686-16.971 0L6.101 342.322c-4.687 4.687-4.687 12.285 0 16.971z"></path>
            </svg>
        </button><!-- .back-to-top -->
    </div>
<?php
}

/**
 * Ajax Callback
 */
function coachpress_lite_dynamic_mce_css_ajax_callback()
{

    /* Check nonce for security */
    $nonce = isset($_REQUEST['_nonce']) ? $_REQUEST['_nonce'] : '';
    if (! wp_verify_nonce($nonce, 'coachpress_lite_dynamic_mce_nonce')) {
        die(); // don't print anything
    }

    /* Get Link Color */
    $primary_font    = get_theme_mod('primary_font', array('font-family' => 'Lato', 'variant' => 'regular'));
    $primary_fonts   = coachpress_lite_get_fonts($primary_font['font-family'], $primary_font['variant']);
    $secondary_font  = get_theme_mod('secondary_font', 'Lora');
    $secondary_fonts = coachpress_lite_get_fonts($secondary_font, 'regular');
    $tertiary_font   = get_theme_mod('tertiary_font', 'Great Vibes');
    $tertiary_fonts  = coachpress_lite_get_fonts($tertiary_font, 'regular');

    /* Set File Type and Print the CSS Declaration */
    header('Content-type: text/css');
    echo ':root .mce-content-body {
        --primary-font: ' . esc_html($primary_fonts['font']) . ';
        --secondary-font: ' . esc_html($secondary_fonts['font']) . ';
        --cursive-font: ' . esc_html($tertiary_fonts['font']) . ';
    }';
    die(); // end ajax process.
}

/**
 * Gutenberg Dynamic Style
 */
function coachpress_lite_gutenberg_inline_style()
{

    $primary_font    = get_theme_mod('primary_font', array('font-family' => 'Lato', 'variant' => 'regular'));
    $primary_fonts   = coachpress_lite_get_fonts($primary_font['font-family'], $primary_font['variant']);
    $secondary_font  = get_theme_mod('secondary_font', 'Lora');
    $secondary_fonts = coachpress_lite_get_fonts($secondary_font, 'regular');
    $tertiary_font   = get_theme_mod('tertiary_font', 'Great Vibes');
    $tertiary_fonts  = coachpress_lite_get_fonts($tertiary_font, 'regular');

    $custom_css = ':root .block-editor-page {
        --primary-font: ' . esc_html($primary_fonts['font']) . ';
        --secondary-font: ' . esc_html($secondary_fonts['font']) . ';
        --cursive-font: ' . esc_html($tertiary_fonts['font']) . ';
    }';

    return $custom_css;
}

/** Typography */
function coachpress_lite_fonts_url()
{
    $fonts_url = '';

    $primary_font       = get_theme_mod('primary_font', array('font-family' => 'Lato', 'variant' => 'regular'));
    $ig_primary_font    = coachpress_lite_is_google_font($primary_font['font-family']);
    $secondary_font     = get_theme_mod('secondary_font', 'Lora');
    $ig_secondary_font  = coachpress_lite_is_google_font($secondary_font);
    $tertiary_font      = get_theme_mod('tertiary_font', 'Great Vibes');
    $ig_tertiary_font   = coachpress_lite_is_google_font($tertiary_font);
    $site_title_font    = get_theme_mod('site_title_font', array('font-family' => 'Noto Serif', 'variant' => 'regular'));
    $ig_site_title_font = coachpress_lite_is_google_font($site_title_font['font-family']);

    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x('on', 'Primary Font: on or off', 'leadership-coach');
    $secondary  = _x('on', 'Secondary Font: on or off', 'leadership-coach');
    $tertiary   = _x('on', 'Tertiary Font: on or off', 'leadership-coach');
    $site_title = _x('on', 'Site Title Font: on or off', 'leadership-coach');


    if ('off' !== $primary || 'off' !== $secondary  || 'off' !== $tertiary || 'off' !== $site_title) {

        $font_families = array();

        if ('off' !== $primary && $ig_primary_font) {
            $primary_variant = coachpress_lite_check_varient($primary_font['font-family'], $primary_font['variant'], true);
            if ($primary_variant) {
                $primary_var = ':' . $primary_variant;
            } else {
                $primary_var = '';
            }
            $font_families[] = $primary_font['font-family'] . $primary_var;
        }

        if ('off' !== $secondary && $ig_secondary_font) {
            $secondary_variant = coachpress_lite_check_varient($secondary_font, 'regular', true);
            if ($secondary_variant) {
                $secondary_var = ':' . $secondary_variant;
            } else {
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }

        if ('off' !== $tertiary && $ig_tertiary_font) {
            $tertiary_variant = coachpress_lite_check_varient($tertiary_font, 'regular', true);
            if ($tertiary_variant) {
                $tertiary_var = ':' . $tertiary_variant;
            } else {
                $tertiary_var = '';
            }
            $font_families[] = $tertiary_font . $tertiary_var;
        }

        if ('off' !== $site_title && $ig_site_title_font) {

            if (! empty($site_title_font['variant'])) {
                $site_title_var = ':' . coachpress_lite_check_varient($site_title_font['font-family'], $site_title_font['variant']);
            } else {
                $site_title_var = '';
            }
            $font_families[] = $site_title_font['font-family'] . $site_title_var;
        }

        $font_families = array_diff(array_unique($font_families), array(''));

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    if (get_theme_mod('ed_localgoogle_fonts', false)) {
        $fonts_url = coachpress_lite_get_webfont_url(add_query_arg($query_args, 'https://fonts.googleapis.com/css'));
    }

    return esc_url_raw($fonts_url);
}

/** Enhanced Google Fonts URL for coaching website */
function leadership_coach_fonts_url()
{
    $fonts_url = '';

    // Enhanced font families for coaching website
    $font_families = array();

    // Heading fonts: Nunito and Poppins
    $font_families[] = 'Nunito:300,400,600,700,800';
    $font_families[] = 'Poppins:300,400,500,600,700';

    // Body fonts: Lora and Open Sans
    $font_families[] = 'Lora:400,500,600,700';
    $font_families[] = 'Open+Sans:300,400,500,600,700';

    if (! empty($font_families)) {
        $query_args = array(
            'family' => implode('&family=', $font_families),
            'display' => 'swap',
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');
    }

    return esc_url_raw($fonts_url);
}

/** Dynamic CSS */
function leadership_coach_dynamic_css()
{

    $primary_font    = get_theme_mod('primary_font', array('font-family' => 'Lato', 'variant' => 'regular'));
    $primary_fonts   = coachpress_lite_get_fonts($primary_font['font-family'], $primary_font['variant']);
    $secondary_font  = get_theme_mod('secondary_font', 'Lora');
    $secondary_fonts = coachpress_lite_get_fonts($secondary_font, 'regular');
    $tertiary_font   = get_theme_mod('tertiary_font', 'Great Vibes');
    $tertiary_fonts  = coachpress_lite_get_fonts($tertiary_font, 'regular');

    $font_size       = get_theme_mod('font_size', 18);

    $site_title_font      = get_theme_mod('site_title_font', array('font-family' => 'Noto Serif', 'variant' => 'regular'));
    $site_title_fonts     = coachpress_lite_get_fonts($site_title_font['font-family'], $site_title_font['variant']);
    $site_title_font_size = get_theme_mod('site_title_font_size', 30);

    $logo_width       = get_theme_mod('logo_width', 150);

    $wheeloflife_color = get_theme_mod('wheeloflife_color', '#eef9f5');

    // Enhanced color palette for coaching website
    $primary_purple = get_theme_mod('primary_purple', '#9B5DE5');
    $secondary_lilac = get_theme_mod('secondary_lilac', '#CBA6F7');
    $accent_pink = get_theme_mod('accent_pink', '#F6C6EA');
    $light_background = get_theme_mod('light_background', '#F8F6FA');
    $dark_text = get_theme_mod('dark_text', '#2E2C38');

    // Enhanced typography settings
    $heading_font = get_theme_mod('heading_font', 'Nunito');
    $body_font = get_theme_mod('body_font', 'Lora');

    echo "<style type='text/css' media='all'>"; ?>

    /*Enhanced Typography and Color Palette*/

    :root {
    /* Enhanced Color Palette */
    --primary-purple: <?php echo esc_html($primary_purple); ?>;
    --secondary-lilac: <?php echo esc_html($secondary_lilac); ?>;
    --accent-pink: <?php echo esc_html($accent_pink); ?>;
    --light-background: <?php echo esc_html($light_background); ?>;
    --dark-text: <?php echo esc_html($dark_text); ?>;

    /* Enhanced Typography System */
    --heading-font: '<?php echo esc_html($heading_font); ?>', sans-serif;
    --body-font: '<?php echo esc_html($body_font); ?>', serif;
    --primary-font: <?php echo wp_kses_post($primary_fonts['font']); ?>;
    --secondary-font: <?php echo wp_kses_post($secondary_fonts['font']); ?>;
    --cursive-font: <?php echo wp_kses_post($tertiary_fonts['font']); ?>;

    /* Responsive Typography Scale */
    --font-size-xs: 0.75rem;
    --font-size-sm: 0.875rem;
    --font-size-base: 1rem;
    --font-size-lg: 1.125rem;
    --font-size-xl: 1.25rem;
    --font-size-2xl: 1.5rem;
    --font-size-3xl: 1.875rem;
    --font-size-4xl: 2.25rem;
    --font-size-5xl: 3rem;

    /* Spacing System */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    --spacing-3xl: 4rem;
    }

    body {
    font-size : <?php echo absint($font_size); ?>px;
    }

    .custom-logo-link img{
    width : <?php echo absint($logo_width); ?>px;
    max-width: 100%;
    }

    .site-title{
    font-size : <?php echo absint($site_title_font_size); ?>px;
    font-family : <?php echo wp_kses_post($site_title_fonts['font']); ?>;
    font-weight : <?php echo esc_html($site_title_fonts['weight']); ?>;
    font-style : <?php echo esc_html($site_title_fonts['style']); ?>;
    }

    section#wheeloflife_section {
    background-color:<?php echo coachpress_lite_sanitize_hex_color($wheeloflife_color); ?>;
    }

<?php echo "</style>";
}
add_action('wp_head', 'leadership_coach_dynamic_css', 99);

/**
 * Returns Home Sections 
 */
function coachpress_lite_get_home_sections()
{

    $ed_banner = get_theme_mod('ed_banner_section', 'static_banner');
    $sections = array(
        'promo'         => array('sidebar' => 'promo'),
        'about'         => array('sidebar' => 'about'),
        'client'        => array('sidebar' => 'client'),
        'service'       => array('sidebar' => 'service'),
        'wheeloflife'   => array('section' => 'wheeloflife'),
        'testimonial'   => array('sidebar' => 'testimonial'),
        'cta'           => array('sidebar' => 'cta'),
        'blog'          => array('section' => 'blog'),
        'newsletter'    => array('sidebar' => 'newsletter'),
    );

    $enabled_section = array();

    if ($ed_banner == 'static_banner' || $ed_banner == 'slider_banner' || $ed_banner == 'static_nl_banner') array_push($enabled_section, 'banner');

    foreach ($sections as $k => $v) {
        if (array_key_exists('sidebar', $v)) {
            if (is_active_sidebar($v['sidebar'])) array_push($enabled_section, $v['sidebar']);
        } else {
            if (get_theme_mod('ed_' . $v['section'] . '_section', false)) array_push($enabled_section, $v['section']);
        }
    }
    return apply_filters('coachpress_lite_home_sections', $enabled_section);
}



/**
 * Send appointment confirmation emails
 */
function leadership_coach_send_appointment_confirmation($appointment_id, $appointment_data)
{
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    // Format date and time for display
    $formatted_date = date('F j, Y', strtotime($appointment_data['appointment_date']));
    $formatted_time = date('g:i A', strtotime($appointment_data['appointment_time']));

    // Email to client
    $client_subject = sprintf(__('Appointment Confirmation - %s', 'leadership-coach'), $site_name);
    $client_message = sprintf(
        __("Dear %s,\n\nThank you for booking an appointment with us!\n\nAppointment Details:\n- Service: %s\n- Date: %s\n- Time: %s\n\nWe look forward to working with you. If you need to reschedule or cancel, please contact us as soon as possible.\n\nBest regards,\n%s", 'leadership-coach'),
        $appointment_data['client_name'],
        $appointment_data['service_type'],
        $formatted_date,
        $formatted_time,
        $site_name
    );

    wp_mail($appointment_data['client_email'], $client_subject, $client_message);

    // Email to admin
    $admin_subject = sprintf(__('New Appointment Booking - %s', 'leadership-coach'), $site_name);
    $admin_message = sprintf(
        __("A new appointment has been booked:\n\nClient Details:\n- Name: %s\n- Email: %s\n- Phone: %s\n\nAppointment Details:\n- Service: %s\n- Date: %s\n- Time: %s\n- Notes: %s\n\nAppointment ID: %d", 'leadership-coach'),
        $appointment_data['client_name'],
        $appointment_data['client_email'],
        $appointment_data['client_phone'],
        $appointment_data['service_type'],
        $formatted_date,
        $formatted_time,
        $appointment_data['notes'],
        $appointment_id
    );

    wp_mail($admin_email, $admin_subject, $admin_message);
}

/**
 * Add appointment cancellation and rescheduling functionality
 */
function leadership_coach_handle_appointment_actions()
{
    // Handle appointment cancellation
    if (isset($_GET['action']) && $_GET['action'] === 'cancel_appointment' && isset($_GET['appointment_id']) && isset($_GET['token'])) {
        $appointment_id = intval($_GET['appointment_id']);
        $token = sanitize_text_field($_GET['token']);

        // Verify token (simple hash-based verification)
        $appointment_model = leadership_coach_get_appointment_model();
        $appointment = $appointment_model->get($appointment_id);

        if ($appointment && hash('sha256', $appointment['client_email'] . $appointment_id) === $token) {
            $result = $appointment_model->update_status($appointment_id, 'cancelled');

            if (! is_wp_error($result)) {
                // Send cancellation confirmation
                leadership_coach_send_cancellation_confirmation($appointment);

                // Redirect with success message
                wp_redirect(add_query_arg('cancelled', '1', home_url()));
                exit;
            }
        }

        // Redirect with error message
        wp_redirect(add_query_arg('cancel_error', '1', home_url()));
        exit;
    }
}
add_action('init', 'leadership_coach_handle_appointment_actions');

/**
 * Send appointment cancellation confirmation
 */
function leadership_coach_send_cancellation_confirmation($appointment)
{
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $formatted_date = date('F j, Y', strtotime($appointment['appointment_date']));
    $formatted_time = date('g:i A', strtotime($appointment['appointment_time']));

    // Email to client
    $client_subject = sprintf(__('Appointment Cancelled - %s', 'leadership-coach'), $site_name);
    $client_message = sprintf(
        __("Dear %s,\n\nYour appointment has been cancelled as requested.\n\nCancelled Appointment:\n- Service: %s\n- Date: %s\n- Time: %s\n\nIf you would like to reschedule, please visit our website or contact us directly.\n\nBest regards,\n%s", 'leadership-coach'),
        $appointment['client_name'],
        $appointment['service_type'],
        $formatted_date,
        $formatted_time,
        $site_name
    );

    wp_mail($appointment['client_email'], $client_subject, $client_message);

    // Email to admin
    $admin_subject = sprintf(__('Appointment Cancelled - %s', 'leadership-coach'), $site_name);
    $admin_message = sprintf(
        __("An appointment has been cancelled:\n\nClient: %s (%s)\nService: %s\nDate: %s\nTime: %s\n\nAppointment ID: %d", 'leadership-coach'),
        $appointment['client_name'],
        $appointment['client_email'],
        $appointment['service_type'],
        $formatted_date,
        $formatted_time,
        $appointment['id']
    );

    wp_mail($admin_email, $admin_subject, $admin_message);
}

/**
 * Generate appointment cancellation link
 */
function leadership_coach_get_cancellation_link($appointment_id, $client_email)
{
    $token = hash('sha256', $client_email . $appointment_id);
    return add_query_arg(array(
        'action' => 'cancel_appointment',
        'appointment_id' => $appointment_id,
        'token' => $token
    ), home_url());
}

/**
 * Add appointment management capabilities
 */
function leadership_coach_add_appointment_capabilities()
{
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('manage_appointments');
        $role->add_cap('edit_appointments');
        $role->add_cap('delete_appointments');
    }
}
add_action('admin_init', 'leadership_coach_add_appointment_capabilities');

/**
 * Enhanced availability checking with business rules
 */
function leadership_coach_is_time_slot_available($date, $time, $exclude_appointment_id = null)
{
    $appointment_model = leadership_coach_get_appointment_model();

    // Check for existing appointments
    if ($appointment_model->has_conflict($date, $time, $exclude_appointment_id)) {
        return false;
    }

    // Check business hours (9 AM to 5 PM, Monday to Friday)
    $day_of_week = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
    if ($day_of_week > 5) { // Weekend
        return false;
    }

    $hour = intval(date('H', strtotime($time)));
    if ($hour < 9 || $hour >= 17) { // Outside business hours
        return false;
    }

    // Check for holidays or blocked dates (can be extended)
    $blocked_dates = get_option('leadership_coach_blocked_dates', array());
    if (in_array($date, $blocked_dates)) {
        return false;
    }

    return true;
}

/**
 * Get appointment statistics for dashboard widget
 */
function leadership_coach_get_appointment_stats()
{
    $appointment_model = leadership_coach_get_appointment_model();

    // Get stats for current month
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t');

    return $appointment_model->get_stats($start_date, $end_date);
}

/**
 * Add dashboard widget for appointment overview
 */
function leadership_coach_add_dashboard_widget()
{
    wp_add_dashboard_widget(
        'leadership_coach_appointments_overview',
        __('Appointments Overview', 'leadership-coach'),
        'leadership_coach_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'leadership_coach_add_dashboard_widget');

/**
 * Dashboard widget content
 */
function leadership_coach_dashboard_widget_content()
{
    $stats = leadership_coach_get_appointment_stats();
    $appointment_model = leadership_coach_get_appointment_model();

    // Get upcoming appointments
    $upcoming = $appointment_model->get_by_date_range(
        date('Y-m-d'),
        date('Y-m-d', strtotime('+7 days')),
        'confirmed'
    );

?>
    <div class="appointments-dashboard-widget">
        <div class="stats-summary">
            <h4><?php esc_html_e('This Month', 'leadership-coach'); ?></h4>
            <ul>
                <li><?php printf(__('Total: %d', 'leadership-coach'), $stats['total']); ?></li>
                <li><?php printf(__('Pending: %d', 'leadership-coach'), $stats['pending']); ?></li>
                <li><?php printf(__('Confirmed: %d', 'leadership-coach'), $stats['confirmed']); ?></li>
                <li><?php printf(__('Completed: %d', 'leadership-coach'), $stats['completed']); ?></li>
            </ul>
        </div>

        <div class="upcoming-appointments">
            <h4><?php esc_html_e('Upcoming This Week', 'leadership-coach'); ?></h4>
            <?php if (empty($upcoming)) : ?>
                <p><?php esc_html_e('No upcoming appointments.', 'leadership-coach'); ?></p>
            <?php else : ?>
                <ul>
                    <?php foreach (array_slice($upcoming, 0, 5) as $appointment) : ?>
                        <li>
                            <strong><?php echo esc_html($appointment['client_name']); ?></strong><br>
                            <?php echo esc_html(date('M j, g:i A', strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']))); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=coaching-appointments')); ?>" class="button button-primary">
                <?php esc_html_e('Manage Appointments', 'leadership-coach'); ?>
            </a>
        </p>
    </div>
<?php
}

/**
 * Include admin classes
 */
require_once get_stylesheet_directory() . '/inc/admin/class-appointments-admin.php';
/**
 * Enqueue custom navigation script
 */
function leadership_coach_custom_nav_script() {
    wp_enqueue_script(
        'custom-nav',
        get_stylesheet_directory_uri() . '/custom-nav.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'leadership_coach_custom_nav_script');

/**
 * Force correct template for calendar page
 */
function leadership_coach_force_calendar_template($template) {
    if (is_page('calendar') || is_page(25)) { // Force calendar page to use the right template
        $new_template = get_stylesheet_directory() . '/page-calendar.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'leadership_coach_force_calendar_template');

/**
 * Enqueue additional styles
 */
function leadership_coach_enqueue_additional_styles() {
    // Enqueue blog styles
    wp_enqueue_style(
        'blog-styles',
        get_stylesheet_directory_uri() . '/assets/css/blog-styles.css',
        array('leadership-coach'),
        '1.0.0'
    );
    
    // Enqueue Calendly styles and scripts on calendar page
    if (is_page_template('page-calendar.php') || is_page('calendar')) {
        // Official Calendly widget CSS and JS (required for proper rendering)
        wp_enqueue_style(
            'calendly-widget-external',
            'https://assets.calendly.com/assets/external/widget.css',
            array(),
            null
        );
        wp_enqueue_script(
            'calendly-widget-external',
            'https://assets.calendly.com/assets/external/widget.js',
            array(),
            null,
            true
        );

        // Custom page styles
        wp_enqueue_style(
            'calendly-styles',
            get_stylesheet_directory_uri() . '/assets/css/calendly-styles.css',
            array('leadership-coach'),
            '1.0.1'
        );
        
        // Temporarily disabled for troubleshooting
        /*
        wp_enqueue_script(
            'calendly-integration',
            get_stylesheet_directory_uri() . '/inc/js/calendly-integration.js',
            array('jquery'),
            '1.0.1',
            true
        );
        */
    }
    
    // Enqueue simple calendar styles
    if (is_page_template('page-calendar-simple.php')) {
        wp_enqueue_style(
            'simple-calendar',
            get_stylesheet_directory_uri() . '/assets/css/simple-calendar.css',
            array('leadership-coach'),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'leadership_coach_enqueue_additional_styles');

/**
 * Disable breadcrumbs shown by the parent theme across inner pages
 * This prevents the Home > About trail from appearing.
 */
add_filter('theme_mod_ed_breadcrumb', '__return_false');
