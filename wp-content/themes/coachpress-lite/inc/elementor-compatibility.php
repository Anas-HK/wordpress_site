<?php 
/**
 * Elementor activation
 */

/**
 * Filter to alter elementor support
 */
function coachpress_lite_elementor_support(){
	update_option( 'elementor_cpt_support', array( 'blossom-portfolio', 'post','page' ) );
	update_option( 'elementor_default_generic_fonts', 'Noto Serif' );
}
add_action( 'after_switch_theme', 'coachpress_lite_elementor_support' );

/**
 * Filter to alter Default Font and Default Color
 */
function coachpress_lite_elementor_defaults( $config ) {

	$primary_font    = get_theme_mod( 'primary_font', 'Didact Gothic' );
	$secondary_font  = get_theme_mod( 'secondary_font', 'Noto Serif' ); 

	$config['default_schemes']['color']['items'] = [
		'1' => '#ebc1c8',
		'2' => '#7d6a91',
		'3' => '#171717',
		'4' => '#ebc1c8'
	];

	$config['default_schemes']['typography']['items'] = [
		'1' => array(
			'font_family' => $primary_font,
	        'font_weight' => '400',
		),
		'2' => array(
			'font_family' => $primary_font,
	        'font_weight' => '400',
		),
		'3' => array(
			'font_family' => $primary_font,
	        'font_weight' => '400',
		),
		'4' => array(
			'font_family' => $secondary_font,
	        'font_weight' => '600',
		)];

	$config['i18n']['global_colors'] = __( 'CoachPress Lite Color', 'coachpress-lite' );
	$config['i18n']['global_fonts']  = __( 'CoachPress Lite Fonts', 'coachpress-lite' );

	return $config;
}
add_filter('elementor/editor/localize_settings', 'coachpress_lite_elementor_defaults', 99 );