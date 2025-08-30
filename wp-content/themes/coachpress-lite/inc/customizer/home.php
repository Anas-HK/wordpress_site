<?php
/**
 * Front Page Settings
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_frontpage( $wp_customize ) {
	
    /** Front Page Settings */
    $wp_customize->add_panel( 
        'frontpage_settings',
         array(
            'priority'    => 40,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Front Page Settings', 'coachpress-lite' ),
            'description' => __( 'Static Home Page settings.', 'coachpress-lite' ),
        ) 
    );    
    
    /** Slider Settings */
    $wp_customize->get_section( 'header_image' )->panel                    = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'coachpress-lite' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'coachpress_lite_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'coachpress_lite_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'coachpress_lite_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
        'ed_banner_section',
        array(
            'default'           => 'static_banner',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'ed_banner_section',
            array(
                'label'       => __( 'Banner Options', 'coachpress-lite' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'coachpress-lite' ),
                'section'     => 'header_image',
                'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'coachpress-lite' ),
                    'static_banner'    => __( 'Static/Video CTA Banner', 'coachpress-lite' ),
                    'static_nl_banner' => __( 'Static/Video Newsletter Banner', 'coachpress-lite' ),
                    'slider_banner'    => __( 'Banner as Slider', 'coachpress-lite' ),
                ),
                'priority' => 5 
            )            
        )
    );

    $wp_customize->add_setting(
        'header_image_mobile',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_image',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'header_image_mobile',
            array(
                'label'           => __( 'Mobile Banner Image', 'coachpress-lite' ),
                'description'     => __( 'Upload a banner image for the mobile devices only.', 'coachpress-lite' ),
                'section'         => 'header_image',
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );
    
    /** Slider Content Style */
    $wp_customize->add_setting(
        'slider_type',
        array(
            'default'           => 'latest_posts',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'slider_type',
            array(
                'label'   => __( 'Slider Content Style', 'coachpress-lite' ),
                'section' => 'header_image',
                'choices' => array(
                    'latest_posts' => __( 'Latest Posts', 'coachpress-lite' ),
                    'cat'          => __( 'Category', 'coachpress-lite' ),
                ),
                'active_callback' => 'coachpress_lite_banner_ac' 
            )
        )
    );

    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Be The Spark That Lights Up The Room', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector' => '.site-banner .banner-caption h2.title',
        'render_callback' => 'coachpress_lite_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'To empower women to make a positive impact on the world with fiery passion, unbridled self-belief, and head-turning style.', 'coachpress-lite' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Description', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'textarea',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector' => '.site-banner .banner-caption .banner-desc',
        'render_callback' => 'coachpress_lite_get_banner_sub_title',
    ) );
    
    /** Read More Text */
    $wp_customize->add_setting(
        'slider_subtitle',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'slider_subtitle',
        array(
            'type'            => 'text',
            'section'         => 'header_image',
            'label'           => __( 'Slider Subtitle', 'coachpress-lite' ),
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'slider_subtitle', array(
        'selector' => '.site-banner .banner-caption .section-subtitle',
        'render_callback' => 'coachpress_lite_get_slider_subtitle',
    ) );

    /** Banner Label */
    $wp_customize->add_setting(
        'button_one',
        array(
            'default'           => __( 'Get started', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'button_one',
        array(
            'label'           => __( 'Button One Label', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'button_one', array(
        'selector' => '.site-banner .banner-caption a.btn-one',
        'render_callback' => 'coachpress_lite_get_slider_button_one',
    ) );
    
    /** Banner Link */
    $wp_customize->add_setting(
        'button_one_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'button_one_url',
        array(
            'label'           => __( 'Banner One Link', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'url',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    $wp_customize->add_setting(
        'button_one_tab_new',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'button_one_tab_new',
            array(
                'section'       => 'header_image',
                'label'         => __( 'Button One Open in New tab', 'coachpress-lite' ),
                'description'   => __( 'Enable to open button one link in new window.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );

    $wp_customize->add_setting(
        'button_two',
        array(
            'default'           => __( 'Know More', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'button_two',
        array(
            'label'           => __( 'Button Two Label', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'button_two', array(
        'selector' => '.site-banner .banner-caption a.btn-two',
        'render_callback' => 'coachpress_lite_get_slider_button_two',
    ) );

    $wp_customize->add_setting(
        'button_two_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'button_two_url',
        array(
            'label'           => __( 'Banner Two Link', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'url',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );

    $wp_customize->add_setting(
        'button_two_tab_new',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'button_two_tab_new',
            array(
                'section'       => 'header_image',
                'label'         => __( 'Button Two Open in New tab', 'coachpress-lite' ),
                'description'   => __( 'Enable to open button two link in new window.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );

    $wp_customize->add_setting(
        'slider_readmore',
        array(
            'default'           => __( 'Read More', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'slider_readmore',
        array(
            'type'            => 'text',
            'section'         => 'header_image',
            'label'           => __( 'Slider Readmore', 'coachpress-lite' ),
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'slider_readmore', array(
        'selector' => '.site-banner .banner-caption a.btn-two',
        'render_callback' => 'coachpress_lite_get_slider_readmore',
    ) );

    /** Banner Newsletter */
    $wp_customize->add_setting(
        'banner_newsletter',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'banner_newsletter',
        array(
            'label'           => __( 'Banner Newsletter Shortcode', 'coachpress-lite' ),
            'description'     => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'coachpress-lite' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'coachpress_lite_banner_ac'
        )
    );
    
    /** Slider Category */
    $wp_customize->add_setting(
        'slider_cat',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'slider_cat',
            array(
                'label'           => __( 'Slider Category', 'coachpress-lite' ),
                'section'         => 'header_image',
                'choices'         => coachpress_lite_get_categories(),
                'active_callback' => 'coachpress_lite_banner_ac' 
            )
        )
    );
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 3,
            'sanitize_callback' => 'coachpress_lite_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Slider_Control( 
            $wp_customize,
            'no_of_slides',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'coachpress-lite' ),
                'description' => __( 'Choose the number of slides you want to display', 'coachpress-lite' ),
                'choices'     => array(
                    'min'   => 1,
                    'max'   => 20,
                    'step'  => 1,
                ),
                'active_callback' => 'coachpress_lite_banner_ac'                 
            )
        )
    );
    
    /** HR */
    $wp_customize->add_setting(
        'hr',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'hr',
            array(
                'section'     => 'header_image',
                'description' => '<hr/>',
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    ); 
    
    /** Slider Auto */
    $wp_customize->add_setting(
        'slider_auto',
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'slider_auto',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Slider Auto', 'coachpress-lite' ),
                'description' => __( 'Enable slider auto transition.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );
    
    /** Slider Loop */
    $wp_customize->add_setting(
        'slider_loop',
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'slider_loop',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Slider Loop', 'coachpress-lite' ),
                'description' => __( 'Enable slider loop.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );
    
    /** Slider Caption */
    $wp_customize->add_setting(
        'slider_caption',
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'slider_caption',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Slider Caption', 'coachpress-lite' ),
                'description' => __( 'Enable slider caption.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );

    $wp_customize->add_setting( 
        'banner_caption_layout', 
        array(
            'default'           => 'right',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Radio_Buttonset_Control(
            $wp_customize,
            'banner_caption_layout',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Banner Caption Alignment', 'coachpress-lite' ),
                'description' => __( 'Choose alignment for banner caption.', 'coachpress-lite' ),
                'choices'     => array(
                    'left'      => __( 'Left', 'coachpress-lite' ),
                    'right'     => __( 'Right', 'coachpress-lite' ),
                ),
                'active_callback' => 'coachpress_lite_banner_ac' 
            )
        )
    );
    
    /** Full Image */
    $wp_customize->add_setting(
        'slider_full_image',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'slider_full_image',
            array(
                'section'         => 'header_image',
                'label'           => __( 'Full Image', 'coachpress-lite' ),
                'description'     => __( 'Enable to use full size image in slider.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );
    
    /** Slider Animation */
    $wp_customize->add_setting(
        'slider_animation',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new CoachPress_Lite_Select_Control(
            $wp_customize,
            'slider_animation',
            array(
                'label'       => __( 'Slider Animation', 'coachpress-lite' ),
                'section'     => 'header_image',
                'choices'     => array(
                    'bounceOut'      => __( 'Bounce Out', 'coachpress-lite' ),
                    'bounceOutLeft'  => __( 'Bounce Out Left', 'coachpress-lite' ),
                    'bounceOutRight' => __( 'Bounce Out Right', 'coachpress-lite' ),
                    'bounceOutUp'    => __( 'Bounce Out Up', 'coachpress-lite' ),
                    'bounceOutDown'  => __( 'Bounce Out Down', 'coachpress-lite' ),
                    'fadeOut'        => __( 'Fade Out', 'coachpress-lite' ),
                    'fadeOutLeft'    => __( 'Fade Out Left', 'coachpress-lite' ),
                    'fadeOutRight'   => __( 'Fade Out Right', 'coachpress-lite' ),
                    'fadeOutUp'      => __( 'Fade Out Up', 'coachpress-lite' ),
                    'fadeOutDown'    => __( 'Fade Out Down', 'coachpress-lite' ),
                    'flipOutX'       => __( 'Flip OutX', 'coachpress-lite' ),
                    'flipOutY'       => __( 'Flip OutY', 'coachpress-lite' ),
                    'hinge'          => __( 'Hinge', 'coachpress-lite' ),
                    'pulse'          => __( 'Pulse', 'coachpress-lite' ),
                    'rollOut'        => __( 'Roll Out', 'coachpress-lite' ),
                    'rotateOut'      => __( 'Rotate Out', 'coachpress-lite' ),
                    'rubberBand'     => __( 'Rubber Band', 'coachpress-lite' ),
                    'shake'          => __( 'Shake', 'coachpress-lite' ),
                    ''               => __( 'Slide', 'coachpress-lite' ),
                    'slideOutLeft'   => __( 'Slide Out Left', 'coachpress-lite' ),
                    'slideOutRight'  => __( 'Slide Out Right', 'coachpress-lite' ),
                    'slideOutUp'     => __( 'Slide Out Up', 'coachpress-lite' ),
                    'slideOutDown'   => __( 'Slide Out Down', 'coachpress-lite' ),
                    'swing'          => __( 'Swing', 'coachpress-lite' ),
                    'tada'           => __( 'Tada', 'coachpress-lite' ),
                    'zoomOut'        => __( 'Zoom Out', 'coachpress-lite' ),
                    'zoomOutLeft'    => __( 'Zoom Out Left', 'coachpress-lite' ),
                    'zoomOutRight'   => __( 'Zoom Out Right', 'coachpress-lite' ),
                    'zoomOutUp'      => __( 'Zoom Out Up', 'coachpress-lite' ),
                    'zoomOutDown'    => __( 'Zoom Out Down', 'coachpress-lite' ),                    
                ),
                'active_callback' => 'coachpress_lite_banner_ac'                                 
            )
        )
    );
    
    /** Slider Speed */
    $wp_customize->add_setting(
        'slider_speed',
        array(
            'default'           => 5000,
            'sanitize_callback' => 'coachpress_lite_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Slider_Control( 
            $wp_customize,
            'slider_speed',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Slider Speed', 'coachpress-lite' ),
                'description' => __( 'Controls the speed of slider in miliseconds.', 'coachpress-lite' ),
                'choices'     => array(
                    'min'  => 1000,
                    'max'  => 20000,
                    'step' => 500,
                ),
                'active_callback' => 'coachpress_lite_banner_ac'
            )
        )
    );

    /** Slider Settings Ends */  

    /** About Section */
    $wp_customize->add_section(
        'about',
        array(
            'title'    => __( 'About Section', 'coachpress-lite' ),
            'priority' => 40,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting( 'about_signature',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'about_signature',
            array(
                'label'         => esc_html__( 'About Signature', 'coachpress-lite' ),
                'description'   => esc_html__( 'Choose the signature image for about section.', 'coachpress-lite' ),
                'section'       => 'about',
                'type'          => 'image',
                'priority'      => -1,
            )
        )
    );

    $wp_customize->add_setting(
        'about_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'about_note_text',
            array(
                'section'     => 'about',
                'description' => __( '<hr/>Add "Blossom: Featured Page" widget for about section.', 'coachpress-lite' ),
                'priority'    => -1
            )
        )
    );

    $about_section = $wp_customize->get_section( 'sidebar-widgets-about' );
    if ( ! empty( $about_section ) ) {

        $about_section->panel     = 'frontpage_settings';
        $about_section->priority  = 40;
        $wp_customize->get_control( 'about_note_text' )->section = 'sidebar-widgets-about';
        $wp_customize->get_control( 'about_signature' )->section = 'sidebar-widgets-about';
    }  
    
    /** About Section Ends */

    /** Service Section */
    $wp_customize->add_section(
        'service',
        array(
            'title'    => __( 'Service Section', 'coachpress-lite' ),
            'priority' => 50,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Service SubTitle  */
    $wp_customize->add_setting(
        'service_subtitle',
        array(
            'default'           => __( 'Services', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'service_subtitle',
        array(
            'label'           => __( 'Service Section Subtitle', 'coachpress-lite' ),
            'description'     => __( 'Add subtitle for service section.', 'coachpress-lite' ),
            'section'         => 'service',
            'type'            => 'text',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
        'selector' => '.service-section .section-header .section-subtitle',
        'render_callback' => 'coachpress_lite_get_service_subtitle',
    ) );

    /** Service Title  */
    $wp_customize->add_setting(
        'service_title',
        array(
            'default'           => __( 'How Can I Help You', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'service_title',
        array(
            'label'           => __( 'Service Section Title', 'coachpress-lite' ),
            'description'     => __( 'Add title for service section.', 'coachpress-lite' ),
            'section'         => 'service',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'service_title', array(
        'selector' => '.service-section .section-header h2.section-title',
        'render_callback' => 'coachpress_lite_get_service_title',
    ) );    

    /** Service SubTitle  */
    $wp_customize->add_setting(
        'service_content',
        array(
            'default'           => __( 'One-on-One Personalised services for clients anywhere in the world, to empower you to feel positive about your life, relationships, career and health.', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'service_content',
        array(
            'label'           => __( 'Service Section Content', 'coachpress-lite' ),
            'description'     => __( 'Add description for service section.', 'coachpress-lite' ),
            'section'         => 'service',
            'type'            => 'text',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'service_content', array(
        'selector' => '.service-section .section-header .section-desc',
        'render_callback' => 'coachpress_lite_get_service_content',
    ) );
    
    $wp_customize->add_setting(
        'service_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'service_note_text',
            array(
                'section'     => 'service',
                'description' => __( '<hr/>Add "Blossom: Icon Text" widget for service section. The recommended image size for this section is 356px by 229px', 'coachpress-lite' ),
                'priority'    => -1
            )
        )
    );

    $wp_customize->add_setting( 
        'service_button_label', 
        array(
            'default'           => __( 'View All Services', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) 
    );
    
    $wp_customize->add_control(
        'service_button_label',
        array(
            'section'         => 'service',
            'label'           => __( 'Service Button Label', 'coachpress-lite' ),
            'type'            => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'service_button_label', array(
        'selector' => '.service-section .service-inner-wrapper .button-wrap .btn-readmore',
        'render_callback' => 'coachpress_lite_get_service_button_label',
    ) );

    $wp_customize->add_setting( 
        'service_button_link', 
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw'
        ) 
    );
    
    $wp_customize->add_control(
        'service_button_link',
        array(
            'section'         => 'service',
            'label'           => __( 'Service Button Link', 'coachpress-lite' ),
            'type'            => 'url',
        )
    );

    $service_section = $wp_customize->get_section( 'sidebar-widgets-service' );
    if ( ! empty( $service_section ) ) {

        $service_section->panel     = 'frontpage_settings';
        $service_section->priority  = 50;
        $wp_customize->get_control( 'service_note_text' )->section      = 'sidebar-widgets-service';
        $wp_customize->get_control( 'service_title' )->section          = 'sidebar-widgets-service';
        $wp_customize->get_control( 'service_subtitle' )->section       = 'sidebar-widgets-service';
        $wp_customize->get_control( 'service_content' )->section        = 'sidebar-widgets-service';
        $wp_customize->get_control( 'service_button_label' )->section   = 'sidebar-widgets-service';
        $wp_customize->get_control( 'service_button_link' )->section    = 'sidebar-widgets-service';
    }  
    
    /** Service Section Ends */

        /** Wheel of life section */
        $wp_customize->add_section(
            'wheel_of_life',
            array(
                'title'    => __( 'Wheel of Life Section', 'coachpress-lite' ),
                'priority' => 55,
                'panel'    => 'frontpage_settings',
            )
        );

        $wp_customize->add_setting(
            'ed_wheeloflife_section',
            array(
                'default'           => false,
                'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
            )
        );
    
        $wp_customize->add_control(
            new CoachPress_Lite_Toggle_Control(
                $wp_customize,
                'ed_wheeloflife_section',
                array(
                    'label'       => __( 'Enable Wheel of Life Section', 'coachpress-lite' ),
                    'section'     => 'wheel_of_life',
                )            
            )
        );

        /** Note */
        $wp_customize->add_setting(
            'wol_section_title',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'wol_section_title',
            array(
                'label'           => __( 'Section Title', 'coachpress-lite' ),
                'section'         => 'wheel_of_life',
                'type'            => 'text',
                'active_callback' => 'coachpress_lite_wheeloflife_ac',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'wol_section_title', array(
            'selector'        => '.wheeloflife-section h2.section-title',
            'render_callback' => 'coachpress_lite_get_wol_section_title',
        ) );

        $wp_customize->add_setting(
            'wol_section_content',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'wol_section_content',
            array(
                'label'           => __( 'Section Content', 'coachpress-lite' ),
                'section'         => 'wheel_of_life',
                'type'            => 'text',
                'active_callback' => 'coachpress_lite_wheeloflife_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'wol_section_content', array(
            'selector'        => '.wheeloflife-section .section-content p',
            'render_callback' => 'coachpress_lite_get_wol_section_content',
        ) );

        /** Image */
        $wp_customize->add_setting(
            'wheeloflife_img',
            array(
                'default'           => get_template_directory_uri() . '/images/chart.png',
                'sanitize_callback' => 'wellness_coach_lite_sanitize_image',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'wheeloflife_img',
                array(
                    'label'             => __( 'Wheel of Life Image', 'coachpress-lite' ),
                    'section'           => 'wheel_of_life',
                    'active_callback' => 'coachpress_lite_wheeloflife_ac',
                )
            )
        );

        /** Tab Section */    
        $wp_customize->add_setting( 
            'wheel_of_life_tab', 
            array(
                'default'           => 'coach',
                'sanitize_callback' => 'coachpress_lite_sanitize_radio'
            ) 
        );
        
        $wp_customize->add_control(
            new Coachpress_Lite_Radio_Buttonset_Control(
                $wp_customize,
                'wheel_of_life_tab',
                array(
                    'section'	  => 'wheel_of_life',
                    'choices'	  => array(
                        'coach' => __( 'Coach Podium', 'coachpress-lite' ),
                        'wol'   => __( 'Wheel of Life Plugin', 'coachpress-lite' ),
                    ),
                    'active_callback' => 'coachpress_lite_wheeloflife_ac',
                )
            )
        );

        $wp_customize->add_setting(
            'wheeloflife_text_cp',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Coachpress_Lite_Note_Control( 
                $wp_customize,
                'wheeloflife_text_cp',
                array(
                    'section'         => 'wheel_of_life',
                    'description'     => sprintf( __( '%1$sCoach Podium%2$s helps coaches to create interactive "Wheel of Life" assessments to generate leads and enhance client engagement. Learn how to %3$screate Wheel of Life assessment using Coach Podium.%4$s ', 'coachpress-lite' ), '<a href="https://coachpodium.com/?utm_source=theme_customizer&utm_medium=theme&utm_campaign=blossom" target="_blank">', '</a>','<a href="https://docs.coachpodium.com/article/creating-wheel-of-life-assessment/?utm_source=theme_customizer&utm_medium=theme&utm_campaign=blossom" target="_blank" >', '</a>' ),
                    'active_callback' => 'coachpress_lite_wheeloflife_ac'
                )
            )
        );

        $wp_customize->add_setting(
            'wheeloflife_cp_text',
            array(
                'default'           => __( 'Start Assessment','coachpress-lite' ),
                'sanitize_callback' => 'sanitize_text_field'
            )
        );
        
        $wp_customize->add_control(
            'wheeloflife_cp_text',
            array(
                'label'     => __( 'Button Label', 'coachpress-lite' ),
                'section'   => 'wheel_of_life',
                'type'      => 'text',
                'active_callback' => 'coachpress_lite_wheeloflife_ac'
            )
        );

        $wp_customize->add_setting(
            'wheeloflife_url_cp',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw', 
            )
        );
        
        $wp_customize->add_control(
            'wheeloflife_url_cp',
            array(
                'label'           => __( 'Button Link', 'coachpress-lite' ),
                'section'         => 'wheel_of_life',
                'type'            => 'url',
                'active_callback' => 'coachpress_lite_wheeloflife_ac'
            )
        );

        if( coachpress_lite_is_wheel_of_life_activated() ){

            $wp_customize->add_setting(
                'wheeloflife_text',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'wp_kses_post' 
                )
            );
            
            $wp_customize->add_control(
                new CoachPress_Lite_Note_Control( 
                    $wp_customize,
                    'wheeloflife_text',
                    array(
                        'section'         => 'wheel_of_life',
                        'description'     => sprintf( __( '%1$sWheel of Life plugin%2$s helps to create interactive assessment for lead generation. Refer to this %3$sdocumentation%4$s to configure the plugin.', 'coachpress-lite' ),'<a href="https://wheeloflife.pro/?utm_source=customizer&utm_medium=BT" target="_blank">','</a>', '<a href="https://wheeloflife.pro/docs/" target="_blank">', '</a>' ),
                        'active_callback' => 'coachpress_lite_wheeloflife_ac'
                    )
                )
            );

            $wp_customize->add_setting(
                'wheeloflife_shortcode',
                array(
                    'default'            => '',
                    'sanitize_callback'  => 'wp_kses_post',
                )
            );

            $wp_customize->add_control(
                'wheeloflife_shortcode',
                array(
                    'label'           => __('Wheel of Life shortcode', 'coachpress-lite'),
                    'description'     => __('Enter the Wheel of Life shortcode. Ex. [wheeloflife id=1456]', 'coachpress-lite'),
                    'section'         => 'wheel_of_life',
                    'type'            => 'text',
                    'active_callback' => 'coachpress_lite_wheeloflife_ac'
                )
            );

            $wp_customize->add_setting(
                'wheeloflife_learn_text',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'wp_kses_post' 
                )
            );
        
            $wp_customize->add_control(
                new CoachPress_Lite_Note_Control( 
                    $wp_customize,
                    'wheeloflife_learn_text',
                    array(
                        'section'         => 'wheel_of_life',
                        'description'     => sprintf( __( 'Refer to this %1$sdocumentation%2$s to learn how to use the shortcode.', 'coachpress-lite' ), '<a href="https://wheeloflife.pro/docs/how-to-display-embed-wheel-of-life-assessments/" target="_blank">', '</a>' ),
                        'active_callback' => 'coachpress_lite_wheeloflife_ac'
                    )
                )
            );

            $wp_customize->add_setting( 
                'wheeloflife_color', 
                array(
                    'default'           => '#FDF9F9',
                    'sanitize_callback' => 'sanitize_hex_color',
                ) 
            );
    
            $wp_customize->add_control( 
                new WP_Customize_Color_Control( 
                    $wp_customize, 
                    'wheeloflife_color', 
                    array(
                        'label'           => __( 'Section color', 'coachpress-lite' ),
                        'section'         => 'wheel_of_life',
                        'active_callback' => 'coachpress_lite_wheeloflife_ac'
                    )
                )
            );

        }else{
            $wp_customize->add_setting(
                'wol_activate_note',
                array(
                    'sanitize_callback' => 'wp_kses_post'
                )
            );

            $wp_customize->add_control(
                new CoachPress_Lite_Note_Control( 
                    $wp_customize,
                    'wol_activate_note',
                    array(
                        'section'     => 'wheel_of_life', 
                        'label'       => __( 'Wheel of Life', 'coachpress-lite' ),   				
                        'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sWheel of Life%2$s. After that option related with this section will be visible.', 'coachpress-lite' ), '<a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '" target="_blank">', '</a>' ),
                        'active_callback' => 'coachpress_lite_wheeloflife_ac'
                    )
                )
            ); 
        }
    /** Wheel of life section ends */

    /** Testimonial Section */
    $wp_customize->add_section(
        'testimonial',
        array(
            'title'    => __( 'Testimonial Section', 'coachpress-lite' ),
            'priority' => 60,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Testimonial SubTitle  */
    $wp_customize->add_setting(
        'testimonial_subtitle',
        array(
            'default'           => __( 'Testimonials', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_subtitle',
        array(
            'label'           => __( 'Testimonial Section Subtitle', 'coachpress-lite' ),
            'description'     => __( 'Add subtitle for testimonial section.', 'coachpress-lite' ),
            'section'         => 'testimonial',
            'type'            => 'text',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_subtitle', array(
        'selector' => '.testimonial-section .section-header .section-subtitle',
        'render_callback' => 'coachpress_lite_get_testimonial_subtitle',
    ) );

    /** Testimonial Title  */
    $wp_customize->add_setting(
        'testimonial_title',
        array(
            'default'           => __( 'Loved and Trusted by My Clients', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_title',
        array(
            'label'           => __( 'Testimonial Section Title', 'coachpress-lite' ),
            'description'     => __( 'Add title for testimonial section.', 'coachpress-lite' ),
            'section'         => 'testimonial',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
        'selector' => '.testimonial-section .section-header h2.section-title',
        'render_callback' => 'coachpress_lite_get_testimonial_title',
    ) );    

    /** Testimonial SubTitle  */
    $wp_customize->add_setting(
        'testimonial_content',
        array(
            'default'           => __( 'Read what my clients are saying whom I\'ve helped to make a difference in their life.', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_content',
        array(
            'label'           => __( 'Testimonial Section Content', 'coachpress-lite' ),
            'description'     => __( 'Add description for testimonial section.', 'coachpress-lite' ),
            'section'         => 'testimonial',
            'type'            => 'text',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_content', array(
        'selector' => '.testimonial-section .section-header .section-desc',
        'render_callback' => 'coachpress_lite_get_testimonial_content',
    ) );

    $wp_customize->add_setting(
        'testimonial_video_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'coachpress_lite_sanitize_code',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_video_url',
        array(
            'label'           => __( 'Video Link', 'coachpress-lite' ),
            'description'     => __( 'Add YouTube Embed code to display video.', 'coachpress-lite' ),
            'section'         => 'testimonial',
            'type'            => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'testimonial_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'testimonial_note_text',
            array(
                'section'     => 'testimonial',
                'description' => __( '<hr/>Add "Blossom: Testimonial" widget for testimonial section.', 'coachpress-lite' ),
                'priority'    => -1
            )
        )
    );

    $testimonial_section = $wp_customize->get_section( 'sidebar-widgets-testimonial' );
    if ( ! empty( $testimonial_section ) ) {

        $testimonial_section->panel     = 'frontpage_settings';
        $testimonial_section->priority  = 60;
        $wp_customize->get_control( 'testimonial_note_text' )->section = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_title' )->section     = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_subtitle' )->section  = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_content' )->section  = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_video_url' )->section  = 'sidebar-widgets-testimonial';
    }  
    
    /** Testimonial Section Ends */

    /** Blog Section */
    $wp_customize->add_section(
        'blog_section',
        array(
            'title'    => __( 'Blog Section', 'coachpress-lite' ),
            'priority' => apply_filters( 'coachpress_lite_set_blog_priority', 90 ),
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_blog_section',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_blog_section',
            array(
                'section'     => 'blog_section',
                'label'       => __( 'Enable Blog', 'coachpress-lite' ),
                'description' => __( 'Enable blog section.', 'coachpress-lite' ),
            )
        )
    );

    /** Blog title */
    $wp_customize->add_setting(
        'blog_section_title',
        array(
            'default'           => __( 'From The Blog', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_title',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Title', 'coachpress-lite' ),
            'type'    => 'text',
        )
    );

    // Selective refresh for blog title.
    $wp_customize->selective_refresh->add_partial( 'blog_section_title', array(
        'selector'            => '.blog-section .section-header h2.section-title',
        'render_callback'     => 'coachpress_lite_get_blog_title',
    ) );

    /** Blog description */
    $wp_customize->add_setting(
        'blog_section_subtitle',
        array(
            'default'           => __( 'Latest', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_subtitle',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Subtitle', 'coachpress-lite' ),
            'type'    => 'text',
        )
    ); 

    $wp_customize->selective_refresh->add_partial( 'blog_section_subtitle', array(
        'selector'            => '.blog-section .section-header .section-subtitle',
        'render_callback'     => 'coachpress_lite_get_blog_subtitle',
    ) );
    
    /** Blog description */
    $wp_customize->add_setting(
        'blog_section_desc',
        array(
            'default'           => __( 'Show your latest blog posts here. You can modify this section from Appearance > Customize > Front Page Settings > Blog Section.', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_desc',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Description', 'coachpress-lite' ),
            'type'    => 'text',
        )
    ); 

    $wp_customize->selective_refresh->add_partial( 'blog_section_desc', array(
        'selector'            => '.blog-section .section-header .section-desc',
        'render_callback'     => 'coachpress_lite_get_blog_content',
    ) );

    /** Read More Label */
    $wp_customize->add_setting(
        'blog_readmore',
        array(
            'default'           => __( 'Read More', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_readmore',
        array(
            'label'           => __( 'Read More Label', 'coachpress-lite' ),
            'section'         => 'blog_section',
            'type'            => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_readmore', array(
        'selector' => '.blog-section .button-wrap a span',
        'render_callback' => 'coachpress_lite_blog_readmore',
    ) ); 

    /** View All Label */
    $wp_customize->add_setting(
        'blog_view_all',
        array(
            'default'           => __( 'View All', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_view_all',
        array(
            'label'           => __( 'View All Label', 'coachpress-lite' ),
            'section'         => 'blog_section',
            'type'            => 'text',
            'active_callback' => 'coachpress_lite_blog_view_all_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_view_all', array(
        'selector' => '.blog-section .button-wrap .btn-readmore',
        'render_callback' => 'coachpress_lite_get_blog_view_all',
    ) ); 
    /** Blog Section Ends */

    /** Resource Section */
    $wp_customize->add_section(
        'resource_section',
        array(
            'title'    => __( 'Resource Section', 'coachpress-lite' ),
            'panel'    => 'frontpage_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'resource_section_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'resource_section_text',
            array(
                'section'     => 'resource_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'resource_section_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'resource_section_settings',
            array(
                'section'     => 'resource_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/resource-section.png',
                ),
            )
        )
    );

    /** Sort Section */
    
    $wp_customize->add_section(
        'sort_section',
        array(
            'title'    => __( 'Sort Front Page Section', 'coachpress-lite' ),
            'panel'    => 'frontpage_settings',
        )
    );


     /** Note */
     $wp_customize->add_setting(
        'sort_section_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'sort_section_text',
            array(
                'section'     => 'sort_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'sort_section_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'sort_section_settings',
            array(
                'section'     => 'sort_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/sort.png',
                ),
            )
        )
    );

    /** One Page Section */
    $wp_customize->add_section(
        'one_page_section',
        array(
            'title'    => __( 'One Page Settings', 'coachpress-lite' ),
            'panel'    => 'frontpage_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'one_page_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'one_page_text',
            array(
                'section'     => 'one_page_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'one_page_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'one_page_settings',
            array(
                'section'     => 'one_page_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/one-page.png',
                ),
            )
        )
    );
      
}
add_action( 'customize_register', 'coachpress_lite_customize_register_frontpage' );