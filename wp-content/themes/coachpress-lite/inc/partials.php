<?php
/**
 * CoachPress Lite Customizer Partials
 *
 * @package CoachPress_Lite
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function coachpress_lite_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function coachpress_lite_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'coachpress_lite_get_phone' ) ) :
/**
 * Phone Number in Header
*/
function coachpress_lite_get_phone(){
    return esc_html( get_theme_mod( 'phone', '' ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_email' ) ) :
/**
 * Email in Header
*/
function coachpress_lite_get_email(){
    return esc_html( get_theme_mod( 'email', '' ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_banner_title' ) ) :
/**
 * Banner Title
*/
function coachpress_lite_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'Be The Spark That Lights Up The Room', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_banner_sub_title' ) ) :
/**
 * Banner Subtitle
*/
function coachpress_lite_get_banner_sub_title(){
    return wp_kses_post( wpautop(get_theme_mod( 'banner_subtitle', __( 'To empower women to make a positive impact on the world with fiery passion, unbridled self-belief, and head-turning style.', 'coachpress-lite' ) ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_slider_subtitle' ) ) :
/**
 * Slider Subtitle
*/
function coachpress_lite_get_slider_subtitle(){
    return esc_html( get_theme_mod( 'slider_subtitle', '' ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_slider_readmore' ) ) :
/**
 * Slider Read More
*/
function coachpress_lite_get_slider_readmore(){
    return esc_html( get_theme_mod( 'slider_readmore', __( 'Read More', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_slider_button_one' ) ) :
/**
 * Slider Button One
*/
function coachpress_lite_get_slider_button_one(){
    return esc_html( get_theme_mod( 'button_one', __( 'Get Started', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_slider_button_two' ) ) :
/**
 * Slider Button Two
*/
function coachpress_lite_get_slider_button_two(){
    return esc_html( get_theme_mod( 'button_two', __( 'Know More', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_service_title' ) ) :
/**
 * Service Title
*/
function coachpress_lite_get_service_title(){
    return esc_html( get_theme_mod( 'service_title', __( 'How Can I Help You', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_service_subtitle' ) ) :
/**
 * Service Subtitle
*/
function coachpress_lite_get_service_subtitle(){
    return esc_html( get_theme_mod( 'service_subtitle', __( 'Services', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_service_content' ) ) :
/**
 * Service Content
*/
function coachpress_lite_get_service_content(){
    return esc_html( get_theme_mod( 'service_content', __( 'One-on-One Personalised services for clients anywhere in the world, to empower you to feel positive about your life, relationships, career and health.', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_testimonial_title' ) ) :
/**
 * Testimonial Title
*/
function coachpress_lite_get_testimonial_title(){
    return esc_html( get_theme_mod( 'testimonial_title', __( 'Loved and Trusted by My Clients', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_service_button_label' ) ) :
/**
 * Service Button Label
*/
function coachpress_lite_get_service_button_label(){
    return esc_html( get_theme_mod( 'service_button_label', __( 'View All Services', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_testimonial_subtitle' ) ) :
/**
 * Testimonial Subtitle
*/
function coachpress_lite_get_testimonial_subtitle(){
    return esc_html( get_theme_mod( 'testimonial_subtitle', __( 'Testimonials', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_testimonial_content' ) ) :
/**
 * Testimonial Content
*/
function coachpress_lite_get_testimonial_content(){
    return esc_html( get_theme_mod( 'testimonial_content', __( 'Read what my clients are saying whom I\'ve helped to make a difference in their life.', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_blog_title' ) ) :
/**
 * Blog Title
*/
function coachpress_lite_get_blog_title(){
    return esc_html( get_theme_mod( 'blog_section_title', __( 'From The Blog', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_blog_subtitle' ) ) :
/**
 * Blog Subtitle
*/
function coachpress_lite_get_blog_subtitle(){
    return esc_html( get_theme_mod( 'blog_section_subtitle', __( 'Latest', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_blog_content' ) ) :
/**
 * Blog Content
*/
function coachpress_lite_get_blog_content(){
    return esc_html( get_theme_mod( 'blog_section_desc', __( 'Show your latest blog posts here. You can modify this section from Appearance > Customize > Front Page Settings > Blog Section.', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_blog_readmore' ) ) :
/**
 * Blog readmore
*/
function coachpress_lite_blog_readmore(){
    return esc_html( get_theme_mod( 'blog_readmore', __( 'Read More Label', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_blog_view_all' ) ) :
/**
 * Blog readmore
*/
function coachpress_lite_get_blog_view_all(){
    return esc_html( get_theme_mod( 'blog_view_all', __( 'View All Label', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function coachpress_lite_get_read_more(){
    return esc_html( get_theme_mod( 'read_more_text', __( 'Continue Reading', 'coachpress-lite' ) ) );    
}
endif;

if( ! function_exists( 'coachpress_lite_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function coachpress_lite_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'coachpress-lite' ) ) );
}
endif;

if( ! function_exists( 'coachpress_lite_get_wol_section_title' ) ) :
/**
 * Wheel of life section title
 */
function coachpress_lite_get_wol_section_title(){
    return esc_html( get_theme_mod( 'wol_section_title' ) );
}
endif;   

if( ! function_exists( 'coachpress_lite_get_wol_section_content' ) ) :
    /**
     * Wheel of life section title
    */
    function coachpress_lite_get_wol_section_content(){
        return esc_html( get_theme_mod( 'wol_section_content' ) );
}
endif;


if( ! function_exists( 'coachpress_lite_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function coachpress_lite_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'coachpress-lite' );
        echo date_i18n( esc_html__( 'Y', 'coachpress-lite' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'coachpress-lite' );
    }
    echo '</span>'; 
}
endif;