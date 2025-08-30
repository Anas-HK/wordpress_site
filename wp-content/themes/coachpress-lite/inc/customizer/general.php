<?php
/**
 * General Settings
 *
 * @package CoachPress_Lite
 */

function coachpress_lite_customize_register_general( $wp_customize ){
    
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'coachpress-lite' ),
            'description' => __( 'Customize Header, Social, Sharing, SEO, Post/Page, Newsletter, Performance and Miscellaneous settings.', 'coachpress-lite' ),
        ) 
    );

    /** Top Bar Section */
    $wp_customize->add_section(
        'top_bar_section',
        array(
            'title'    => __( 'Top Bar Settings', 'coachpress-lite' ),
            'priority' => 10,
            'panel'    => 'general_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'top_bar_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'top_bar_text',
            array(
                'section'     => 'top_bar_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'top_bar_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'top_bar_settings',
            array(
                'section'     => 'top_bar_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/top-bar.png',
                ),
            )
        )
    );

    /** Header Settings */
    $wp_customize->add_section(
        'header_settings',
        array(
            'title'    => __( 'Header Settings', 'coachpress-lite' ),
            'priority' => 20,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Header Search */
    $wp_customize->add_setting( 
        'ed_header_search', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_header_search',
            array(
                'section'     => 'header_settings',
                'label'       => __( 'Enable Header Search', 'coachpress-lite' ),
                'description' => __( 'Enable to show Search button in header.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Shopping Cart */
    $wp_customize->add_setting( 
        'ed_shopping_cart', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_shopping_cart',
            array(
                'section'         => 'header_settings',
                'label'           => __( 'Shopping Cart', 'coachpress-lite' ),
                'description'     => __( 'Enable to show Shopping cart in the header.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_is_woocommerce_activated'
            )
        )
    );
        
    /** Phone */
    $wp_customize->add_setting(
        'phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'phone',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Phone', 'coachpress-lite' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'phone', array(
        'selector'        => '.header-block .phone',
        'render_callback' => 'coachpress_lite_get_phone',
    ) );

    /** Email */
    $wp_customize->add_setting(
        'email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'email',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Email', 'coachpress-lite' ),
        )
    );

    $wp_customize->selective_refresh->add_partial( 'email', array(
        'selector'        => '.header-block .email',
        'render_callback' => 'coachpress_lite_get_email',
    ) );

    $wp_customize->add_setting( 'blog_background_image',
        array(
            'default'           => esc_url( get_template_directory_uri() . '/images/page-header-bg.jpg' ),
            'sanitize_callback' => 'coachpress_lite_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'blog_background_image',
            array(
                'label'         => esc_html__( 'Blog Background Image', 'coachpress-lite' ),
                'description'   => esc_html__( 'Choose background Image of your choice. Recommended size for this image is 1920px by 232px.', 'coachpress-lite' ),
                'section'       => 'header_settings',
                'type'          => 'image',
            )
        )
    );

    /** Header Settings Ends */

    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'coachpress-lite' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_social_links',
            array(
                'section'     => 'social_media_settings',
                'label'       => __( 'Enable Social Links', 'coachpress-lite' ),
                'description' => __( 'Enable to show social links at header.', 'coachpress-lite' ),
            )
        )
    );
    
    $wp_customize->add_setting( 
        new CoachPress_Lite_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'CoachPress_Lite_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Control_Repeater(
            $wp_customize,
            'social_links',
            array(
                'section' => 'social_media_settings',               
                'label'   => __( 'Social Links', 'coachpress-lite' ),
                'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'coachpress-lite' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'coachpress-lite' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'coachpress-lite' ),
                        'description' => __( 'Example: https://facebook.com', 'coachpress-lite' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'coachpress-lite' ),
                    'field' => 'link'
                )                        
            )
        )
    );
    /** Social Media Settings Ends */

    /** Top Bar Section */
    $wp_customize->add_section(
        'social_sharing_section',
        array(
            'title'    => __( 'Social Sharing', 'coachpress-lite' ),
            'priority' => 35,
            'panel'    => 'general_settings',
        )
    );

     /** Note */
     $wp_customize->add_setting(
        'social_sharing_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'social_sharing_text',
            array(
                'section'     => 'social_sharing_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'social_sharing_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'social_sharing_settings',
            array(
                'section'     => 'social_sharing_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/social-sharing.png',
                ),
            )
        )
    );


    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'coachpress-lite' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_post_update_date',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Last Update Post Date', 'coachpress-lite' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_breadcrumb',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Breadcrumb', 'coachpress-lite' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'coachpress-lite' ),
        )
    );  
    /** SEO Settings Ends */

    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'coachpress-lite' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_prefix_archive',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Prefix in Archive Page', 'coachpress-lite' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Blog Post Image Crop */
    $wp_customize->add_setting( 
        'ed_crop_blog', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_crop_blog',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Blog Post Image Crop', 'coachpress-lite' ),
                'description' => __( 'Enable to avoid automatic cropping of featured image in home, archive and search posts.', 'coachpress-lite' ),
            )
        )
    );

    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_excerpt',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Enable Blog Excerpt', 'coachpress-lite' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 30,
            'sanitize_callback' => 'coachpress_lite_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Slider_Control( 
            $wp_customize,
            'excerpt_length',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Excerpt Length', 'coachpress-lite' ),
                'description' => __( 'Automatically generated excerpt length (in words).', 'coachpress-lite' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 100,
                    'step'  => 5,
                )                 
            )
        )
    );

    $wp_customize->add_setting(
        'blog_main_title',
        array(
            'default'           => __( 'Latest Articles', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'blog_main_title',
        array(
            'label'   => __( 'Blog Title', 'coachpress-lite' ),
            'section' => 'post_page_settings',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'blog_main_content',
        array(
            'default'           => __( 'Read the articles to grow your business and career.', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'blog_main_content',
        array(
            'label'   => __( 'Blog Description', 'coachpress-lite' ),
            'section' => 'post_page_settings',
            'type'    => 'text',
        )
    );
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'Continue Reading', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'coachpress-lite' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.home .buttom-wrap .btn-link',
        'render_callback' => 'coachpress_lite_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Note_Control( 
            $wp_customize,
            'post_note_text',
            array(
                'section'     => 'post_page_settings',
                'description' => sprintf( __( '%s These options affect your individual posts.', 'coachpress-lite' ), '<hr/>' ),
            )
        )
    );

    /** Single Post Image Crop */
    $wp_customize->add_setting( 
        'ed_crop_single', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_crop_single',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Single Post Image Crop', 'coachpress-lite' ),
                'description' => __( 'Enable to avoid automatic cropping of featured image in single post.', 'coachpress-lite' ),
            )
        )
    );

    /** Hide Author Section */
    $wp_customize->add_setting( 
        'ed_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Author Section', 'coachpress-lite' ),
                'description' => __( 'Enable to hide author section.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Related Posts', 'coachpress-lite' ),
                'description' => __( 'Enable to show related posts in single page.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'Recommended Articles', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Posts Section Title', 'coachpress-lite' ),
            'active_callback' => 'coachpress_lite_post_page_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.additional-post .post-title',
        'render_callback' => 'coachpress_lite_get_related_title',
    ) );

    $wp_customize->add_setting(
        'related_portfolio_title',
        array(
            'default'           => __( 'Related Projects', 'coachpress-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_portfolio_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Portfolio Title', 'coachpress-lite' ),
        )
    );
    
    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Comments', 'coachpress-lite' ),
                'description' => __( 'Enable to show Comments in Single Post/Page.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Comments Below Post Content */
    $wp_customize->add_setting(
        'toggle_comments',
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'toggle_comments',
            array(
                'section'         => 'post_page_settings',
                'label'           => __( 'Comments Below Post Content', 'coachpress-lite' ),
                'description'     => __( 'Enable to show comment section right after post content. Refresh site for changes.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_post_page_ac'
            )
        )
    );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_category',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Category', 'coachpress-lite' ),
                'description' => __( 'Enable to hide category.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Hide Post Author */
    $wp_customize->add_setting( 
        'ed_post_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_post_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Post Author', 'coachpress-lite' ),
                'description' => __( 'Enable to  hide post author.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_post_date',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Posted Date', 'coachpress-lite' ),
                'description' => __( 'Enable to hide posted date.', 'coachpress-lite' ),
            )
        )
    );
    
    /** Show Featured Image */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_featured_image',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Featured Image', 'coachpress-lite' ),
                'description' => __( 'Enable to show featured image in post detail (single post).', 'coachpress-lite' ),
            )
        )
    );

    /** Posts(Blog) & Pages Settings Ends */

    /** Newsletter Settings */
    $wp_customize->add_section(
        'newsletter_settings',
        array(
            'title'    => __( 'Newsletter Settings', 'coachpress-lite' ),
            'priority' => 65,
            'panel'    => 'general_settings',
        )
    );
    
    if( coachpress_lite_is_btnw_activated() ){
        
        /** Enable Newsletter Section */
        $wp_customize->add_setting( 
            'ed_newsletter', 
            array(
                'default'           => false,
                'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new CoachPress_Lite_Toggle_Control( 
                $wp_customize,
                'ed_newsletter',
                array(
                    'section'     => 'newsletter_settings',
                    'label'       => __( 'Newsletter Section', 'coachpress-lite' ),
                    'description' => __( 'Enable to show Newsletter Section', 'coachpress-lite' ),
                )
            )
        );
        
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'newsletter_shortcode',
            array(
                'type'        => 'text',
                'section'     => 'newsletter_settings',
                'label'       => __( 'Newsletter Shortcode', 'coachpress-lite' ),
                'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'coachpress-lite' ),
            )
        ); 
    } else {
        $wp_customize->add_setting(
            'newsletter_recommend',
            array(
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        $wp_customize->add_control(
            new CoachPress_Lite_Plugin_Recommend_Control(
                $wp_customize,
                'newsletter_recommend',
                array(
                    'section'     => 'newsletter_settings',
                    'label'       => __( 'Newsletter Shortcode', 'coachpress-lite' ),
                    'capability'  => 'install_plugins',
                    'plugin_slug' => 'blossomthemes-email-newsletter',//This is the slug of recommended plugin.
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'coachpress-lite' ), '<strong>', '</strong>' ),
                )
            )
        );
    }

    /** Newsletter Settings Ends */

    /** Instagram Settings */
    $wp_customize->add_section(
        'instagram_settings',
        array(
            'title'    => __( 'Instagram Settings', 'coachpress-lite' ),
            'priority' => 100,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Instagram Section */
    $wp_customize->add_setting( 
        'ed_instagram', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_instagram',
            array(
                'section'     => 'instagram_settings',
                'label'       => __( 'Instagram Section', 'coachpress-lite' ),
                'description' => __( 'Enable to show Instagram Section', 'coachpress-lite' ),
            )
        )
    );

    $wp_customize->add_setting( 
        'instagram_shortcode', 
        array(
            'default'           => '[instagram-feed]',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    
    $wp_customize->add_control(
        'instagram_shortcode',
        array(
            'section'         => 'instagram_settings',
            'label'           => __( 'Shortcode', 'coachpress-lite' ),
            'type'            => 'text',
            'description'     => __( 'Add shortcode for your instagram profile below:', 'coachpress-lite' ),
            'active_callback' => 'coachpress_lite_instagram_ac',
        )
    );
    /** Instagram Settings End */

    /** Miscellaneous Settings */
    $wp_customize->add_section(
        'misc_settings',
        array(
            'title'    => __( 'Misc Settings', 'coachpress-lite' ),
            'priority' => 85,
            'panel'    => 'general_settings',
        )
    );

    /** Shop Page Description */
    $wp_customize->add_setting( 
        'ed_shop_archive_description', 
        array(
            'default'           => false,
            'sanitize_callback' => 'coachpress_lite_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new CoachPress_Lite_Toggle_Control( 
            $wp_customize,
            'ed_shop_archive_description',
            array(
                'section'         => 'misc_settings',
                'label'           => __( 'Shop Page Description', 'coachpress-lite' ),
                'description'     => __( 'Enable to show Shop Page Description.', 'coachpress-lite' ),
                'active_callback' => 'coachpress_lite_is_woocommerce_activated'
            )
        )
    );
    
    $wp_customize->add_setting(
        'error_404_image',
        array(
            'default'           => esc_url( get_template_directory_uri() . '/images/error.jpg' ),
            'sanitize_callback' => 'coachpress_lite_sanitize_image',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'error_404_image',
            array(
                'label'           => __( '404 Image', 'coachpress-lite' ),
                'description'     => __( 'Choose the background image for 404 page.', 'coachpress-lite' ),
                'section'         => 'misc_settings',
            )
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'misc_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Note_Control( 
            $wp_customize,
            'misc_text',
            array(
                'section'     => 'misc_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'coachpress-lite' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/coachpress/?utm_source=coachpress_lite&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'misc_settings_image', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'coachpress_lite_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Coachpress_Lite_Radio_Image_Control(
            $wp_customize,
            'misc_settings_image',
            array(
                'section'     => 'misc_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/misc-settings.png',
                ),
            )
        )
    );

    /** Miscellaneous Settings Ends */
    
}
add_action( 'customize_register', 'coachpress_lite_customize_register_general' );