<?php
/**
 * Appearance Settings
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_appearance( $wp_customize ) {
    
    $wp_customize->add_panel( 
        'appearance_settings', 
        array(
            'title'       => __( 'Appearance Settings', 'coachpress-lite' ),
            'priority'    => 25,
            'capability'  => 'edit_theme_options',
            'description' => __( 'Change color and body background.', 'coachpress-lite' ),
        ) 
    );

    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'coachpress-lite' ),
            'priority' => 20,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default' => array(                                         
                'font-family' => 'Didact Gothic',
                'variant'     => 'regular',
            ),
            'sanitize_callback' => array( 'CoachPress_Lite_Fonts', 'sanitize_typography' )
        )
    );

    $wp_customize->add_control( 
        new CoachPress_Lite_Typography_Control( 
            $wp_customize, 
            'primary_font', 
            array(
                'label'       => __( 'Primary Font', 'coachpress-lite' ),
                'description' => __( 'Primary font of the site.', 'coachpress-lite' ),
                'section'     => 'typography_settings',
            ) 
        ) 
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'Noto Serif',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'coachpress-lite' ),
                'description' => __( 'Secondary font of the site.', 'coachpress-lite' ),
                'section'     => 'typography_settings',
                'choices'     => coachpress_lite_get_all_fonts(),   
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
                'label'       => __( 'Tertiary Font', 'coachpress-lite' ),
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
                'label'       => __( 'Font Size', 'coachpress-lite' ),
                'description' => __( 'Change the font size of your site.', 'coachpress-lite' ),
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
                'label'         => __( 'Load Google Fonts Locally', 'coachpress-lite' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'coachpress-lite' )
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
                'label'         => __( 'Preload Local Fonts', 'coachpress-lite' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'coachpress-lite' ); ?></span>
        
        <input type="button" class="button button-primary coachpress-lite-flush-local-fonts-button" name="coachpress-lite-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'coachpress-lite' ); ?>" />
    <?php
    $coachpress_lite_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'coachpress-lite' ),
            'section'       => 'typography_settings',
            'description'   => $coachpress_lite_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'coachpress_lite_ed_localgoogle_fonts'
        )
    );

    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel              = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority           = 10;
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 15;

    /** Color */

    /** Note */
    $wp_customize->add_setting(
        'color_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'color_text',
            array(
                'section'     => 'colors',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'color_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'color_settings',
            array(
                'section'     => 'colors',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/colors.png',
                ),
            )
        )
    );

    /** Typography */

     /** Note */
     $wp_customize->add_setting(
        'typography_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'typography_text',
            array(
                'section'     => 'typography_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'typography_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'typography_settings',
            array(
                'section'     => 'typography_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/typography.png',
                ),
            )
        )
    );

}
add_action( 'customize_register', 'coachpress_lite_customize_register_appearance' );