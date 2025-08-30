<?php
/**
 * Elementor Setting
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_elementor( $wp_customize ) {
    
    $wp_customize->add_section(
        'elementor_settings',
        array(
            'title'      => __( 'Elementor Settings', 'coachpress-lite' ),
            'priority'   => 80,
            'capability' => 'edit_theme_options',
        )
    );
    
    $wp_customize->add_setting(
        'ed_elementor',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_elementor',
            array(
                'section'       => 'elementor_settings',
                'label'         => __( 'Enable Elementor Page Builder in FrontPage', 'coachpress-lite' ),
                'description'   => __( 'You can override your Homepage Contents from this Elementor Page Builder', 'coachpress-lite' ),
            )
        )
    );   
        
}
add_action( 'customize_register', 'coachpress_lite_customize_register_elementor' );