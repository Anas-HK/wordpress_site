<?php
/**
 * Wheel_Of_Life Helper Functions.
 *
 * General helper functions avaiable on both the front-end and backend.
 *
 * @package Wheel_Of_Life\Functions
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wheel_Of_Life get global settings.
 *
 * @return void
 */
function wheel_of_life_get_global_settings() {

	$settings = get_option( 'wheel_of_life_social_sharing' ) ? get_option( 'wheel_of_life_social_sharing' ) : array();

	return $settings;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 *
 * @return string|array
 */
function wheeloflife_clean_vars( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wheeloflife_clean_vars', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Get Email Template Settings.
 */
function wol_get_cta_settings( $encode = false ) {
	$settings = get_option( 'wheel_of_life_CTA' ) ? json_decode( get_option( 'wheel_of_life_CTA' ), true ) : array();
	$defaults = apply_filters(
		'wol_cta_defaults',
		array(
			'title'                 => __( 'Is your life out of balance? Need a coach?', 'wheel-of-life' ),
			'description'           => __( 'I am a Certified Coach with experience of over 5 years. I can help you transform your life and get it run smoothly.', 'wheel-of-life' ),
			'buttonLabel'           => __( 'Schedule an Appointment', 'wheel-of-life' ),
			'buttonLink'            => '',
			'openInTab'             => false,
			'setLinkAttrNoFollow'   => false,
			'setLinkAttrSponser'    => false,
			'setLinkAttrDownload'   => false,
			'sbuttonLabel'          => __( 'Services', 'wheel-of-life' ),
			'sbuttonLink'           => '',
			'sbopenInTab'           => false,
			'sbsetLinkAttrNoFollow' => false,
			'sbsetLinkAttrSponser'  => false,
			'sbsetLinkAttrDownload' => false,
			'customizer'            => array(
				'background'     => array(
					'background_type'    => 'color',
					'background_pattern' => 'type-1',
					'background_image'   => array(
						'attachment_id' => null,
						'x'             => 0,
						'y'             => 0,
					),

					'background_repeat'     => 'no-repeat',
					'background_size'       => 'cover',
					'background_attachment' => 'scroll',

					'patternColor' => array(
						'default' => array(
							'color' => '#e5e7ea',
						),
					),

					'overlayColor' => array(
						'default' => array(
							'color' => 'rgba(22, 16, 16, 0.3)',
						),
					),

					'backgroundColor' => array(
						'default' => array(
							'color' => '#038181',
						),
					),
				),
				'bgtype'         => '',
				'bgColor'        => '',
				'bgImage'        => '',
				'alignment'      => 'center',
				'fontSize'       => 32,
				'fontColor'      => '#ffffff',
				'descFontSize'   => 18,
				'descFontColor'  => '#fff',
				'buttonType'     => 'primary',
				'pbFontSize'     => 16,
				'pbBorderRadius' => 4,
				'pbfontColors'   => array(
					'pbfontColor'      => '#fff',
					'pbfontHoverColor' => '#000',
				),
				'pbBgColors'     => array(
					'pbBgColor'      => '#038181',
					'pbBgHoverColor' => '#fff',
				),
				'sbfontColors'   => array(
					'sbfontColor'      => '#038181',
					'sbfontHoverColor' => '#000',
				),
				'sbBgColors'     => array(
					'sbBgColor'      => '#ffffff',
					'sbBgHoverColor' => '#fff',
				),
				'sbFontSize'     => 16,
				'sbBorderRadius' => 4,
				'margin'         => array(
					'top'    => '0',
					'left'   => '0',
					'right'  => '0',
					'bottom' => '0',
				),
				'padding'        => array(
					'top'    => '50px',
					'left'   => '38px',
					'right'  => '38px',
					'bottom' => '50px',
				),
			),
		)
	);
	$settings = wol_wp_parse_args( $settings, $defaults );

	return $encode ? wp_json_encode( $settings ) : $settings;
}

/**
 * Merge user defined arguments into defaults array.
 * Similar to wp_parse_args() just a bit extended to work with multidimensional arrays.
 *
 * @param array $args (Required) Value to merge with $defaults.
 * @param array $defaults Array that serves as the defaults. Default value: ''
 *
 * @return mixed
 * @since 1.0.0
 *
 */
function wol_wp_parse_args( &$args, $defaults = '' ) {
	$args     = (array) $args;
	$defaults = (array) $defaults;
	$result   = $defaults;

	foreach ( $args as $key => &$value ) {
		if ( is_array( $value ) && ! empty( $value ) && isset( $result[ $key ] ) ) {
			$result[ $key ] = wol_wp_parse_args( $value, $result[ $key ] );
		} else {
			$result[ $key ] = $value;
		}
	}

	return $result;
}

function wol_is_pro_activated() {
	$pro_activated = class_exists( "WheelOfLife_Pro\Wheel_Of_Life_Pro" );

	return $pro_activated;
}

/**
 * Checks if the given color is in valid RGBA format.
 *
 * @param string $color The color to check.
 *
 * @return bool True if the color is in valid RGBA format, false otherwise.
 */
function is_rgba_color( $color ) {
	if ( preg_match( '/^rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0?\.\d+)\s*\)$/', $color, $matches ) ) {
		return $matches[ 1 ] >= 0 && $matches[ 1 ] <= 255 &&
		       $matches[ 2 ] >= 0 && $matches[ 2 ] <= 255 &&
		       $matches[ 3 ] >= 0 && $matches[ 3 ] <= 255 &&
		       $matches[ 4 ] >= 0 && $matches[ 4 ] <= 1;
	}

	return false;
}

/**
 * Checks if the given color is in valid HEX format.
 *
 * @param string $color The color to check.
 *
 * @return bool True if the color is in valid HEX format, false otherwise.
 */
function is_hex_color( $color ) {
	return preg_match( '/^#[a-f0-9]{8}$/i', $color );
}

/**
 * Sanitizes the given color by checking if it's in valid RGBA or HEX format.
 *
 * @param string $color The color to sanitize.
 *
 * @return string|null The sanitized color if it's valid, null otherwise.
 */
function sanitize_rgba_color( $color ) {
	if ( is_rgba_color( $color ) || is_hex_color( $color ) ) {
		return $color;
	}

	return null;
}

/**
 * @param $wheel
 *
 * @return array
 */
function wheel_of_life_get_wheel_settings( $wheel ) {
	$wheel          = get_post( $wheel );
	$wheel          = get_the_content( '', false, $wheel );
	$wheel_settings = parse_blocks( $wheel );

	if ( is_array( $wheel_settings ) ) {
		foreach ( $wheel_settings as $wheel_setting ) {
			if ( 'wheeloflife/add-wheels' === $wheel_setting[ 'blockName' ] ) {
				$wheel_settings = $wheel_setting;
				break;
			}
		}
	}

	$wheel_rows = $wheel_settings[ 'innerBlocks' ];

	$settings = array();
	foreach ( $wheel_rows as $wheel_row ) {
		if ( isset( $wheel_row[ 'attrs' ] ) ) {
			$settings[] = [
				'label'        => $wheel_row[ 'attrs' ][ 'label' ] ?? '',
				'legend_color' => $wheel_row[ 'attrs' ][ 'lengendColor' ] ?? '#1db53f',
			];
		}
	}

	return $settings;
}
