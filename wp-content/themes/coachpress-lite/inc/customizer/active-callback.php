<?php
/**
 * Active Callback
 * 
 * @package CoachPress_Lite
*/

/**
 * Active Callback for Banner Slider
*/
function coachpress_lite_banner_ac( $control ){
    $banner           = $control->manager->get_setting( 'ed_banner_section' )->value();
    $slider_type      = $control->manager->get_setting( 'slider_type' )->value();
    $control_id       = $control->id;
    
    if ( $control_id == 'header_image' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'external_header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'button_one' && !( $banner == 'no_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'button_one_url' && !( $banner == 'no_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'button_one_tab_new' && !( $banner == 'no_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'button_two' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'button_two_url' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'button_two_tab_new' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_newsletter' && $banner == 'static_nl_banner' ) return true;
    if ( $control_id == 'banner_caption_layout' && ( ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) ) return true;
    if ( $control_id == 'header_image_mobile' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;

    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_auto' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_loop' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_caption' && $banner == 'slider_banner' ) return true;          
    if ( $control_id == 'slider_subtitle' && $banner == 'slider_banner' ) return true;    
    if ( $control_id == 'slider_readmore' && $banner == 'slider_banner' ) return true;    
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && $slider_type == 'latest_posts' ) return true;
    if ( $control_id == 'slider_full_image' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_animation' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_speed' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'hr' && $banner == 'slider_banner' ) return true;
    
    return false;
}

/**
 * Active Callback for post/page
*/
function coachpress_lite_post_page_ac( $control ){
    
    $ed_related = $control->manager->get_setting( 'ed_related' )->value();
    $ed_comment = $control->manager->get_setting( 'ed_comments' )->value();
    $control_id = $control->id;
    
    if ( $control_id == 'related_post_title' && $ed_related == true ) return true;
    if ( $control_id == 'toggle_comments' && $ed_comment == true ) return true;
    
    return false;
}

/**
 * Active Callback for Shop page description
*/
function coachpress_lite_shop_description_ac( $control ){
    $ed_shop_archive_desc = $control->manager->get_setting( 'ed_shop_archive_description' )->value();
    $control_id = $control->id;
    
    if( $control_id == 'shop_archive_description' && $ed_shop_archive_desc == true && coachpress_lite_is_woocommerce_activated() ) return true;
    
    return false;
}

/**
 * Active Callback for Blog View All Button
*/
function coachpress_lite_blog_view_all_ac(){
    $blog = get_option( 'page_for_posts' );
    if( $blog ) return true;
    
    return false; 
}

function coachpress_lite_sanitize_code( $value ){
    return htmlspecialchars_decode( stripslashes( $value ) );
}

/**
 * Active Callback for Wheel of Life
*/
function coachpress_lite_wheeloflife_ac( $control ){
    $ed_wheeloflife = get_theme_mod( 'ed_wheeloflife_section' , false );
    $ed_tab         = $control->manager->get_setting( 'wheel_of_life_tab' )->value();
    $control_id     = $control->id;
    
    if ( $ed_wheeloflife && $control_id == 'wol_section_title'  ) return true;
    if ( $ed_wheeloflife && $control_id == 'wol_section_content'  ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_img'  ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheel_of_life_tab'  ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_cp_text' && $ed_tab == 'coach' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_url_cp' && $ed_tab == 'coach' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_text_cp' && $ed_tab == 'coach' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wol_activate_note' && $ed_tab == 'wol' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_text' && $ed_tab == 'wol' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_shortcode' && $ed_tab == 'wol' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_learn_text' && $ed_tab == 'wol' ) return true;
    if ( $ed_wheeloflife && $control_id == 'wheeloflife_color' && $ed_tab == 'wol' ) return true;
    
    return false; 
}

/**
 * Active Callback for local fonts
*/
function coachpress_lite_ed_localgoogle_fonts(){
    $ed_localgoogle_fonts = get_theme_mod( 'ed_localgoogle_fonts' , false );

    if( $ed_localgoogle_fonts ) return true;
    
    return false; 
}

/**
 * Active Callback for instagram
*/
function coachpress_lite_instagram_ac( $control ){
    
    $ed_insta   = $control->manager->get_setting( 'ed_instagram' )->value();
    $control_id = $control->id;
    
    if ( $control_id == 'instagram_shortcode' && $ed_insta ) return true;
    
    return false;
}