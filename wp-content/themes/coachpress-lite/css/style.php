<?php
/**
 * CoachPress Lite Dynamic Styles
 * 
 * @package CoachPress_Lite
*/
if( ! function_exists( 'coachpress_lite_dynamic_css' ) ) :
/**
 * 
 * Dynamic css
 */
function coachpress_lite_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', array( 'font-family'=>'Didact Gothic', 'variant'=>'regular' ) );
    $primary_fonts   = coachpress_lite_get_fonts( $primary_font['font-family'], $primary_font['variant'] );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Noto Serif' );
    $secondary_fonts = coachpress_lite_get_fonts( $secondary_font, 'regular' );
    $tertiary_font   = get_theme_mod( 'tertiary_font', 'Great Vibes' );
    $tertiary_fonts  = coachpress_lite_get_fonts( $tertiary_font, 'regular' );

    $primary_color    = '#E8C1C8';
    $secondary_color  = '#7D6A91';
       
    $rgb  = coachpress_lite_hex2rgb( coachpress_lite_sanitize_hex_color( $primary_color ) ); 
    $rgb2 = coachpress_lite_hex2rgb( coachpress_lite_sanitize_hex_color( $secondary_color ) ); 

    $font_size       = get_theme_mod( 'font_size', 18 );

    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Noto Serif', 'variant'=>'regular' ) );
    $site_title_fonts     = coachpress_lite_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 30 );

    $logo_width       = get_theme_mod( 'logo_width', 150 );

    $wheeloflife_color = get_theme_mod( 'wheeloflife_color', '#FDF9F9' );
     
    echo "<style type='text/css' media='all'>"; ?>

    /*Typography*/

    :root {
        --primary-font: <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
        --secondary-font: <?php echo wp_kses_post( $secondary_fonts['font'] ); ?>;
        --cursive-font: <?php echo wp_kses_post( $tertiary_fonts['font'] ); ?>;
        --primary-color: <?php echo coachpress_lite_sanitize_hex_color( $primary_color ); ?>;
	    --primary-color-rgb: <?php printf('%1$s, %2$s, %3$s', $rgb[0], $rgb[1], $rgb[2] ); ?>;
        --secondary-color: <?php echo coachpress_lite_sanitize_hex_color( $secondary_color ); ?>;
	    --secondary-color-rgb: <?php printf('%1$s, %2$s, %3$s', $rgb2[0], $rgb2[1], $rgb2[2] ); ?>;
    }

    body {
        font-size   : <?php echo absint( $font_size ); ?>px;        
    }

    .custom-logo-link img{
        width    : <?php echo absint( $logo_width ); ?>px;
        max-width: 100%;
    }

    .site-title{
        font-size   : <?php echo absint( $site_title_font_size ); ?>px;
        font-family : <?php echo wp_kses_post( $site_title_fonts['font'] ); ?>;
        font-weight : <?php echo esc_html( $site_title_fonts['weight'] ); ?>;
        font-style  : <?php echo esc_html( $site_title_fonts['style'] ); ?>;
    }

    .widget_bttk_icon_text_widget .rtc-itw-inner-holder .btn-readmore::before {
        background-image: url('data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" width="48.781" height="9.63" viewBox="0 0 48.781 9.63"><g transform="translate(-1019.528 -1511)"><path d="M141.589-1.863l-8.34,4.815v-9.63Z" transform="translate(926.721 1517.678)" fill="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $secondary_color ) ); ?>"></path><path d="M3089.528,1523h40.965" transform="translate(-2070 -7.502)" fill="none" stroke="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $secondary_color ) ); ?>" stroke-width="1"></path></g></svg>');
    }

    .widget_bttk_testimonial_widget .testimonial-content p:first-child::before,
    .widget_bttk_testimonial_widget .testimonial-content p:last-child::after {
        background-image: url('data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" width="16.139" height="12.576" viewBox="0 0 16.139 12.576"><path d="M154.714,262.991c-.462.312-.9.614-1.343.9-.3.2-.612.375-.918.56a2.754,2.754,0,0,1-2.851.133,1.764,1.764,0,0,1-.771-.99,6.549,6.549,0,0,1-.335-1.111,5.386,5.386,0,0,1-.219-1.92,16.807,16.807,0,0,1,.3-1.732,2.392,2.392,0,0,1,.424-.8c.394-.534.808-1.053,1.236-1.56a3.022,3.022,0,0,1,.675-.61,2.962,2.962,0,0,0,.725-.749c.453-.576.923-1.137,1.38-1.71a3.035,3.035,0,0,0,.208-.35c.023-.038.044-.09.079-.107.391-.185.777-.383,1.179-.54.284-.11.5.141.739.234a.316.316,0,0,1-.021.2c-.216.411-.442.818-.663,1.226-.5.918-1.036,1.817-1.481,2.761a7.751,7.751,0,0,0-.915,3.069c-.009.326.038.653.053.98.009.2.143.217.288.2a1.678,1.678,0,0,0,1.006-.491c.2-.2.316-.207.537-.027.283.23.552.479.825.723a.174.174,0,0,1,.06.116,1.424,1.424,0,0,1-.327,1C154.281,262.714,154.285,262.755,154.714,262.991Z" transform="translate(-139.097 -252.358)" fill="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $primary_color ) ); ?>"/><path d="M222.24,262.76a5.243,5.243,0,0,1-2.138,1.427,1.623,1.623,0,0,0-.455.26,3.112,3.112,0,0,1-2.406.338,1.294,1.294,0,0,1-1.021-1.2,6.527,6.527,0,0,1,.449-2.954c.015-.043.04-.083.053-.127a13.25,13.25,0,0,1,1.295-2.632,14.155,14.155,0,0,1,1.224-1.677c.084.14.132.238.2.324.133.176.3.121.414-.06a1.248,1.248,0,0,0,.1-.23c.055-.149.143-.214.315-.111-.029-.308,0-.607.3-.727.114-.045.295.079.463.131.093-.161.227-.372.335-.6.029-.06-.012-.16-.033-.238-.042-.154-.1-.3-.137-.458a1.117,1.117,0,0,1,.27-.933c.154-.207.286-.431.431-.646a.586.586,0,0,1,1.008-.108,2.225,2.225,0,0,0,.336.306.835.835,0,0,0,.356.087,1.242,1.242,0,0,0,.294-.052c-.067.145-.114.257-.17.364-.7,1.34-1.422,2.665-2.082,4.023-.488,1.005-.891,2.052-1.332,3.08a.628.628,0,0,0-.032.11c-.091.415.055.542.478.461.365-.07.607-.378.949-.463a2.8,2.8,0,0,1,.823-.064c.174.01.366.451.317.687a2.48,2.48,0,0,1-.607,1.26C222.081,262.492,222.011,262.615,222.24,262.76Z" transform="translate(-216.183 -252.301)" fill="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $primary_color ) ); ?>"/></svg>');
    }

    .pagination .page-numbers.prev:hover::before,
    .pagination .page-numbers.next:hover::before {
        background-image: url('data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $primary_color ) ); ?>" d="M24.707 38.101L4.908 57.899c-4.686 4.686-4.686 12.284 0 16.971L185.607 256 4.908 437.13c-4.686 4.686-4.686 12.284 0 16.971L24.707 473.9c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L41.678 38.101c-4.687-4.687-12.285-4.687-16.971 0z"></path></svg>');
    }

    .post-navigation .meta-nav::before{
        background-image: url('data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" width="48.781" height="9.63" viewBox="0 0 48.781 9.63"><g transform="translate(-1019.528 -1511)"><path d="M141.589-1.863l-8.34,4.815v-9.63Z" transform="translate(926.721 1517.678)" fill="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $secondary_color ) ); ?>"></path><path d="M3089.528,1523h40.965" transform="translate(-2070 -7.502)" fill="none" stroke="<?php echo coachpress_lite_hash_to_percent23( coachpress_lite_sanitize_hex_color( $secondary_color ) ); ?>" stroke-width="1"></path></g></svg>');
    }

    section#wheeloflife_section {
        background-color: <?php echo coachpress_lite_sanitize_hex_color( $wheeloflife_color ); ?>;
    }
   
    <?php echo "</style>";
}
endif;
add_action( 'wp_head', 'coachpress_lite_dynamic_css', 99 );

/**
 * Function for sanitizing Hex color 
 */
function coachpress_lite_sanitize_hex_color( $color ){
	if ( '' === $color )
		return '';

    // 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;
}

/**
 * convert hex to rgb
 * @link https://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
*/
function coachpress_lite_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/**
 * Convert '#' to '%23'
*/
function coachpress_lite_hash_to_percent23( $color_code ){
    $color_code = str_replace( "#", "%23", $color_code );
    return $color_code;
}

if ( ! function_exists( 'coachpress_lite_gutenberg_inline_style' ) ) : 
/**
 * Gutenberg Dynamic Style
 */
function coachpress_lite_gutenberg_inline_style(){
 
    $primary_font    = get_theme_mod( 'primary_font', array( 'font-family'=>'Didact Gothic', 'variant'=>'regular' ) );
    $primary_fonts   = coachpress_lite_get_fonts( $primary_font['font-family'], $primary_font['variant'] );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Noto Serif' );
    $secondary_fonts = coachpress_lite_get_fonts( $secondary_font, 'regular' );
    $tertiary_font   = get_theme_mod( 'tertiary_font', 'Great Vibes' );
    $tertiary_fonts  = coachpress_lite_get_fonts( $tertiary_font, 'regular' );
 
    $custom_css = ':root .block-editor-page {
        --primary-font: ' . esc_html( $primary_fonts['font'] ) . ';
        --secondary-font: ' . esc_html( $secondary_fonts['font'] ) . ';
        --cursive-font: ' . esc_html( $tertiary_fonts['font'] ) . ';
    }';

    return $custom_css;
}
endif;