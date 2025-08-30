<?php
/**
 * Footer Setting
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_footer( $wp_customize ) {
    
    $wp_customize->add_section(
        'footer_settings',
        array(
            'title'      => __( 'Footer Settings', 'coachpress-lite' ),
            'priority'   => 199,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Footer Copyright */
    $wp_customize->add_setting(
        'footer_copyright',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'footer_copyright',
        array(
            'label'       => __( 'Footer Copyright Text', 'coachpress-lite' ),
            'section'     => 'footer_settings',
            'type'        => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'footer_copyright', array(
        'selector' => '.site-info .copyright',
        'render_callback' => 'coachpress_lite_get_footer_copyright',
    ) );
        
    $wp_customize->add_setting( 'footer_above_bg',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'footer_above_bg',
            array(
                'label'         => esc_html__( 'Above Footer Background', 'coachpress-lite' ),
                'description'   => esc_html__( 'Choose the background image of the footer.', 'coachpress-lite' ),
                'section'       => 'footer_settings',
                'type'          => 'image',
            )
        )
    );


     /** Note */
     $wp_customize->add_setting(
        'footer_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'footer_text',
            array(
                'section'     => 'footer_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'footer_settings_image', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'footer_settings_image',
            array(
                'section'     => 'footer_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/footer.png',
                ),
            )
        )
    );
}
add_action( 'customize_register', 'coachpress_lite_customize_register_footer' );