<?php
/**
 * Layout Settings
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_layout( $wp_customize ) {
    
    /** Layout Settings */
    $wp_customize->add_panel( 
        'layout_settings',
         array(
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Layout Settings', 'coachpress-lite' ),
            'description' => __( 'Change different page layout from here.', 'coachpress-lite' ),
        ) 
    );

    /** Blog Page Layout Settings */
    $wp_customize->add_section(
        'blog_layout',
        array(
            'title'    => __( 'Blog Page Layout', 'coachpress-lite' ),
            'priority' => 40,
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'blog_page_layout', 
        array(
            'default'           => 'classic',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'blog_page_layout',
            array(
                'section'     => 'blog_layout',
                'label'       => __( 'Blog Page Layout', 'coachpress-lite' ),
                'description' => __( 'Choose the blog page layout for your site.', 'coachpress-lite' ),
                'choices'     => array(
                    'classic' => esc_url( get_template_directory_uri() . '/images/blog/classic.jpg' ),
                    'grid'    => esc_url( get_template_directory_uri() . '/images/blog/grid.jpg' ),
                )
            )
        )
    );

    $wp_customize->add_setting(
        'blog_sidebar_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'blog_sidebar_text',
            array(
                'section'     => 'blog_layout',
                'description' => sprintf( __( '%1$sClick here%2$s to select the sidebar layout.', 'coachpress-lite' ), '<span class="text-inner-link blog_sidebar_text">', '</span>' ),
            )
        )
    );

    /** Home Page Layout Settings */
    $wp_customize->add_section(
        'general_layout_settings',
        array(
            'title'    => __( 'General Sidebar Layout', 'coachpress-lite' ),
            'priority' => 55,
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'page_sidebar_layout', 
        array(
            'default'           => 'no-sidebar',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'page_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Page Sidebar Layout', 'coachpress-lite' ),
                'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in respective page.', 'coachpress-lite' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'post_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'post_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Post Sidebar Layout', 'coachpress-lite' ),
                'description' => __( 'This is the general sidebar layout for posts & custom post. You can override the sidebar layout for individual post in respective post.', 'coachpress-lite' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'layout_style', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Image_Control(
            $wp_customize,
            'layout_style',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Default Sidebar Layout', 'coachpress-lite' ),
                'description' => __( 'This is the general sidebar layout for whole site.', 'coachpress-lite' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );

    /** Header Layout Section */
    $wp_customize->add_section(
        'header_layout_settings',
        array(
            'title'    => __( 'Header Layout', 'coachpress-lite' ),
            'priority' => 10,
            'panel'    => 'layout_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'header_layout_img_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'header_layout_img_text',
            array(
                'section'     => 'header_layout_settings',
                'priority' => 30,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'header_layout_img', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'header_layout_img',
            array(
                'section'     => 'header_layout_settings',
                'priority' => 50,
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/header-layout.png',
                ),
            )
        )
    );

    /** Singe Post Section */
    $wp_customize->add_section(
        'single_layout_section',
        array(
            'title'    => __( 'Singe Post Layout', 'coachpress-lite' ),
            'priority' => 12,
            'panel'    => 'layout_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'single_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'single_layout_text',
            array(
                'section'     => 'single_layout_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'single_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'single_layout_settings',
            array(
                'section'     => 'single_layout_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/single-layout.png',
                ),
            )
        )
    );

    /** Slider Layout Section */
    $wp_customize->add_section(
        'slider_layout_section',
        array(
            'title'    => __( 'Slider Layout', 'coachpress-lite' ),
            'priority' => 15,
            'panel'    => 'layout_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'slider_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'slider_layout_text',
            array(
                'section'     => 'slider_layout_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'slider_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'slider_layout_settings',
            array(
                'section'     => 'slider_layout_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/slider-layout.png',
                ),
            )
        )
    );

    /** CTA Banner Layout Section */
    $wp_customize->add_section(
        'cta_static_banner_layout_settings',
        array(
            'title'    => __( 'CTA Static Banner Layout', 'coachpress-lite' ),
            'priority' => 18,
            'panel'    => 'layout_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'cta_banner_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'cta_banner_layout_text',
            array(
                'section'     => 'cta_static_banner_layout_settings',
                'priority' => 30,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'cta_banner_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'cta_banner_layout_settings',
            array(
                'section'     => 'cta_static_banner_layout_settings',
                'priority' => 50,
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/static-banner.png',
                ),
            )
        )
    );

    /** Pagination Settings */
    $wp_customize->add_section(
        'pagination_section',
        array(
            'title'    => __( 'Pagination Settings', 'coachpress-lite' ),
            'priority' => 45,
            'panel'    => 'layout_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'pagination_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'pagination_text',
            array(
                'section'     => 'pagination_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'pagination_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'pagination_settings',
            array(
                'section'     => 'pagination_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pagination.png',
                ),
            )
        )
    );

}
add_action( 'customize_register', 'coachpress_lite_customize_register_layout' );