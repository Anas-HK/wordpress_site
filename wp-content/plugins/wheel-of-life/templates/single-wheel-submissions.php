<?php
/**
 * Wheel Submission template.
 *
 * @package Wheel_Of_Life
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $post;
$chart_data       = get_post_meta( $post->ID, 'chartData', true );
$chart_options    = get_post_meta( $post->ID, 'chartOptions', true );
$wheel_id         = get_post_meta( $post->ID, 'wheelId', true );
$wheel_title      = $wheel_id ? get_the_title( $wheel_id ) : __( 'My Wheel of Life', 'wheel-of-life' );
$chart_type       = get_post_meta( $post->ID, 'chartType', true );
$chart_type       = isset( $chart_type ) && '' != $chart_type ? $chart_type : 'polar-chart';
$wheel_CTA        = apply_filters( 'get_wheel_CTA_meta', array(), $wheel_id );
/* translators: %d: submission number */
$submission_title = sprintf( __( 'Wheel Submission No: #%d', 'wheel-of-life' ), $post->ID );

if ( ! empty( $wheel_CTA ) && $wheel_CTA['ctaType'] === 'no-cta' ) { ?>
	<div class="wlof-main-wrapper">
	<div class="wlof-title-wrap">
		<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
		<span class='wlof-published-on'>
			<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
			<time datetime='<?php echo esc_attr( get_the_date( 'c' ) ); ?>' itemprop='datePublished'><?php echo esc_html( get_the_date() ); ?></time>
		</span>
		<p><?php echo esc_html( $submission_title ); ?></p>
	</div>
	<div class='wlof-life-sbwl'>
		<div id='submission-chart' data-chartData='<?php echo wp_json_encode( $chart_data ); ?>' data-chartOption='<?php echo wp_json_encode( $chart_options ); ?>' data-reportLink='<?php echo esc_url( get_the_permalink() ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
		</div>
	</div>
	<?php
} elseif ( ! empty( $wheel_CTA ) && $wheel_CTA['ctaType'] === 'page-cta' ) {
	$cta_data = json_decode( wp_json_encode( $wheel_CTA, true ) );

	// cta
	$cta_title = $cta_data->title ?? '';
	$cta_desc  = $cta_data->description ?? '';
	$pb_label  = $cta_data->btn_label ?? '';
	$pb_link   = get_page_link( $cta_data->page->value ) ?? '';
	$pb_newtab = rest_sanitize_boolean( $cta_data->openInTab ?? false );

	// cta background
	$bg_type           = $cta_data->customizer->background->background_type ?? '';
	$bg_positionX      = $cta_data->customizer->background->background_image->x ?? 0;
	$bg_positionY      = $cta_data->customizer->background->background_image->y ?? 0;
	$bg_repeate        = $cta_data->customizer->background->background_repeat ?? '';
	$bg_size           = $cta_data->customizer->background->background_size ?? '';
	$bg_attachement    = $cta_data->customizer->background->background_attachment ?? '';
	$bg_img_url        = $cta_data->customizer->background->background_image->url ?? '';
	$bg_solid_color    = $cta_data->customizer->background->backgroundColor->default->color ?? '';
	$bg_gradient_color = $cta_data->customizer->background->gradient ?? '';
	$x_axis            = $bg_positionX * 100;
	$y_axis            = $bg_positionY * 100;
	$cta_overlay_color = sanitize_rgba_color( $cta_data->customizer->background->overlayColor->default->color );

	// spacing
	$cta_margin  = $cta_data->customizer->margin->top . ' ' . $cta_data->customizer->margin->right . ' ' . $cta_data->customizer->margin->bottom . ' ' . $cta_data->customizer->margin->left;
	$cta_padding = $cta_data->customizer->padding->top . ' ' . $cta_data->customizer->padding->right . ' ' . $cta_data->customizer->padding->bottom . ' ' . $cta_data->customizer->padding->left;

	// align
	$cta_align = $cta_data->customizer->alignment ?? '';

	// cta title
	$cta_title_size  = $cta_data->customizer->fontSize ?? '';
	$cta_title_color = sanitize_hex_color( $cta_data->customizer->fontColor ?? '' );

	// cta desc
	$cta_desc_size  = $cta_data->customizer->descFontSize ?? '';
	$cta_desc_color = sanitize_hex_color( $cta_data->customizer->descFontColor ?? '' );

	// buttons
	// primary button
	$pbFontSize       = $cta_data->customizer->pbFontSize ?? '';
	$pbFontColor      = sanitize_hex_color( $cta_data->customizer->pbfontColors->pbfontColor ?? '' );
	$pbBg             = sanitize_hex_color( $cta_data->customizer->pbBgColors->pbBgColor ?? '' );
	$pbBorderRadius   = $cta_data->customizer->pbBorderRadius ?? '';
	$pbfontHoverColor = sanitize_hex_color( $cta_data->customizer->pbfontColors->pbfontHoverColor ?? '' );
	$pbBgHoverColor   = sanitize_hex_color( $cta_data->customizer->pbBgColors->pbBgHoverColor ?? '' );

	?>
	<div class="wlof-main-wrapper">
		<div class="wlof-title-wrap">
			<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
			<span class='wlof-published-on'>
				<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
				<time datetime='<?php echo esc_attr( get_the_date( 'c' ) ); ?>' itemprop='datePublished'><?php echo esc_html( get_the_date() ); ?></time>
			</span>
			<p><?php echo esc_html( $submission_title ); ?></p>
		</div>
		<div class='wlof-life-sbwl'>
			<div id='submission-chart' data-chartData='<?php echo wp_json_encode( $chart_data ); ?>' data-chartOption='<?php echo wp_json_encode( $chart_options ); ?>' data-reportLink='<?php echo esc_url( get_the_permalink() ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
			</div>
		</div>
		<div class="wheeloflife-cta-container">
		<div
			class="wheeloflife-cta-wrapper <?php echo $bg_type === 'image' ? 'has-overlay' : '' ?>"
			style="text-align: <?php echo esc_attr( $cta_align ) ?>; background:
			<?php
				switch( $bg_type ) {
					case 'image':
						echo 'url(' . esc_url( $bg_img_url ) . '); background-repeat:' . esc_attr( $bg_repeate ) . '; background-size:' . esc_attr( $bg_size ) . '; background-attachment:' . esc_attr( $bg_attachement ) . '; background-position:' . (float) $x_axis . '% ' . (float) $y_axis . '%';
						break;
					case 'gradient':
						echo esc_attr( $bg_gradient_color );
						break;
					default:
						echo esc_attr( $bg_solid_color );
				}
			echo '; padding:' . esc_attr( $cta_padding ) . '; margin:' . esc_attr( $cta_margin ) . '; --cta-overlay-color:' . esc_attr( $cta_overlay_color ); ?>"
		>
			<h2
				class="wheeloflife-cta-title"
				style="font-size: <?php echo absint( $cta_title_size ) . 'px; color:' . esc_attr( $cta_title_color ); ?>"
			>
				<?php echo esc_html( $cta_title ); ?>
			</h2>
			<div
				class="wheeloflife-cta-description"
				style="font-size: <?php echo absint( $cta_desc_size ) . 'px; color:' . esc_attr( $cta_desc_color ); ?>"
			>
				<?php echo esc_html( wpautop( $cta_desc ) ); ?>
			</div>
			<div class="wheeloflife-cta-btns">
			<?php if ( ! empty( $pb_label ) && ! empty( $pb_link ) ) { ?>
				<a
					href="<?php echo esc_html( $pb_link ) ?>"
					rel="noexternal noopener"
					target="<?php echo $pb_newtab !== '' ? '_blank' : '' ?>"
					class="wheeloflife-btn primary"
					style="font-size: <?php echo absint( $pbFontSize ) . '; color:' . esc_attr( $pbFontColor ) . '; background:' . esc_attr( $pbBg ) . '; border-radius:' . absint( $pbBorderRadius ) . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo esc_attr( $pbfontHoverColor ); ?>';this.style.background='<?php echo esc_attr( $pbBgHoverColor ); ?>'"
					onMouseOut="this.style.color='<?php echo esc_attr( $pbFontColor ); ?>';this.style.background='<?php echo esc_attr( $pbBg ); ?>'"
				>
					<?php echo esc_html( $pb_label ); ?>
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
	<?php

} else {
	$cta_data = wol_get_cta_settings();
	$cta_data = json_decode( wp_json_encode( $cta_data ), false );

	// cta
	$cta_title = $cta_data->title ?? '';
	$cta_desc  = $cta_data->description ?? '';
	$pb_label  = $cta_data->buttonLabel ?? '';
	$pb_link   = $cta_data->buttonLink ?? '';
	$pb_newtab = rest_sanitize_boolean( $cta_data->openInTab ?? false) ;
	$sb_label  = $cta_data->sbuttonLabel ?? '';
	$sb_link   = $cta_data->sbuttonLink ?? '';
	$sb_newtab = rest_sanitize_boolean( $cta_data->sbopenInTab ?? false );

	$link_nofollow   = rest_sanitize_boolean( $cta_data->setLinkAttrNoFollow ?? false ) ;
	$link_sponser    = rest_sanitize_boolean( $cta_data->setLinkAttrSponser ?? false ) ;
	$link_download   = rest_sanitize_boolean( $cta_data->setLinkAttrDownload ?? false ) ;
	$sblink_nofollow = rest_sanitize_boolean( $cta_data->sbsetLinkAttrNoFollow ?? false ) ;
	$sblink_sponser  = rest_sanitize_boolean( $cta_data->sbsetLinkAttrSponser ?? false ) ;
	$sblink_download = rest_sanitize_boolean( $cta_data->sbsetLinkAttrDownload ?? false ) ;

	// cta background
	$bg_type           = $cta_data->customizer->background->background_type ?? '' ;
	$bg_positionX      = $cta_data->customizer->background->background_image->x ?? 0;
	$bg_positionY      = $cta_data->customizer->background->background_image->y ?? 0;
	$bg_repeate        = $cta_data->customizer->background->background_repeat ?? '';
	$bg_size           = $cta_data->customizer->background->background_size ?? '';
	$bg_attachement    = $cta_data->customizer->background->background_attachment ?? '';
	$bg_img_url        = $cta_data->customizer->background->background_image->url ?? '';
	$bg_solid_color    = $cta_data->customizer->background->backgroundColor->default->color ?? '';
	$bg_gradient_color = $cta_data->customizer->background->gradient ?? '';
	$x_axis            = $bg_positionX * 100;
	$y_axis            = $bg_positionY * 100;
	$cta_overlay_color = isset( $cta_data->customizer->background->overlayColor->default->color ) ? sanitize_rgba_color( $cta_data->customizer->background->overlayColor->default->color ) : null;

	// spacing
	$cta_margin = isset( $cta_data->customizer->margin ) ? $cta_data->customizer->margin->top . ' ' . $cta_data->customizer->margin->right . ' ' . $cta_data->customizer->margin->bottom . ' ' . $cta_data->customizer->margin->left : '';
	$cta_padding = isset( $cta_data->customizer->padding ) ? $cta_data->customizer->padding->top . ' ' . $cta_data->customizer->padding->right . ' ' . $cta_data->customizer->padding->bottom . ' ' . $cta_data->customizer->padding->left : '';

	// align
	$cta_align = $cta_data->customizer->alignment ?? '';

	// cta title
	$cta_title_size  = $cta_data->customizer->fontSize ?? '';
	$cta_title_color = sanitize_hex_color( $cta_data->customizer->fontColor ?? '' );

	// cta desc
	$cta_desc_size  = $cta_data->customizer->descFontSize ?? '';
	$cta_desc_color = sanitize_hex_color( $cta_data->customizer->descFontColor ?? '' );

	// buttons
	// primary button
	$pbFontSize       = $cta_data->customizer->pbFontSize ?? '';
	$pbFontColor      = sanitize_hex_color( $cta_data->customizer->pbfontColors->pbfontColor ?? '' );
	$pbBg             = sanitize_hex_color( $cta_data->customizer->pbBgColors->pbBgColor ?? '' );
	$pbBorderRadius   = $cta_data->customizer->pbBorderRadius ?? '';
	$pbfontHoverColor = sanitize_hex_color( $cta_data->customizer->pbfontColors->pbfontHoverColor ?? '' );
	$pbBgHoverColor   = sanitize_hex_color( $cta_data->customizer->pbBgColors->pbBgHoverColor ?? '' );


	// secondary button
	$sbFontSize       = $cta_data->customizer->sbFontSize ?? '';
	$sbFontColor      = sanitize_hex_color( $cta_data->customizer->sbfontColors->sbfontColor ?? '' );
	$sbBg             = sanitize_hex_color( $cta_data->customizer->sbBgColors->sbBgColor ?? '' );
	$sbBorderRadius   = $cta_data->customizer->sbBorderRadius ?? '';
	$sbfontHoverColor = sanitize_hex_color( $cta_data->customizer->sbfontColors->sbfontHoverColor ?? '' );
	$sbBgHoverColor   = sanitize_hex_color( $cta_data->customizer->sbBgColors->sbBgHoverColor ?? '' );

	?>
	<div class="wlof-main-wrapper">
		<div class="wlof-title-wrap">
			<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
			<span class='wlof-published-on'>
				<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
				<time datetime='<?php echo esc_attr( get_the_date( 'c' ) ); ?>' itemprop='datePublished'><?php echo esc_html( get_the_date() ); ?></time>
			</span>
			<p><?php echo esc_html( $submission_title ); ?></p>
		</div>
		<div class='wlof-life-sbwl'>
			<div id='submission-chart' data-chartData='<?php echo esc_attr( wp_json_encode( $chart_data ) ); ?>' data-chartOption='<?php echo esc_attr( wp_json_encode( $chart_options ) ); ?>' data-reportLink='<?php echo esc_attr( esc_url( get_the_permalink() ) ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
			</div>
		</div>
		<?php if ( $cta_title != '' ) { ?>
		<div class="wheeloflife-cta-container">
		<div
			class="wheeloflife-cta-wrapper <?php echo $bg_type === 'image' ? 'has-overlay' : '' ?>"
			style="text-align: <?php echo esc_attr( $cta_align ) ?>; background:
			<?php
				switch( $bg_type ) {
					case 'image':
						echo 'url(' . esc_url( $bg_img_url ) . '); background-repeat:' . esc_attr( $bg_repeate ) . '; background-size:' . esc_attr( $bg_size ) . '; background-attachment:' . esc_attr( $bg_attachement ) . '; background-position:' . (float) $x_axis . '% ' . (float) $y_axis . '%';
						break;
					case 'gradient':
						echo esc_attr( $bg_gradient_color );
						break;
					default:
						echo esc_attr( $bg_solid_color );
				}
			echo '; padding:' . esc_attr( $cta_padding ) . '; margin:' . esc_attr( $cta_margin ) . '; --cta-overlay-color:' . esc_attr( $cta_overlay_color ); ?>"
		>
			<h2
				class="wheeloflife-cta-title"
				style="font-size: <?php echo absint( $cta_title_size ) . 'px; color:' . esc_attr( $cta_title_color ); ?>"
			>
				<?php echo wp_kses_post( $cta_title ); ?>
			</h2>
			<div
				class="wheeloflife-cta-description"
				style="font-size: <?php echo absint( $cta_desc_size ) . 'px; color:' . esc_attr( $cta_desc_color ); ?>"
			>
				<?php echo wp_kses_post( wpautop( $cta_desc ) ); ?>
			</div>
			<div class="wheeloflife-cta-btns">
			<?php if ( ! empty( $pb_label ) && ! empty( $pb_link ) ) { ?>
				<a
					href="<?php echo esc_html( $pb_link ) ?>"
					rel="noexternal noopener
					<?php
					echo $link_nofollow == true ? 'nofollow ' : '';
					echo $link_sponser == true ? 'sponsored' : '';
					?>
					"
					target="<?php echo esc_attr( $pb_newtab ) != '' ? '_blank' : '' ?>"
					class="wheeloflife-btn primary"
					style="font-size: <?php echo absint( $pbFontSize ) . '; color:' . esc_attr( $pbFontColor ) . '; background:' . esc_attr( $pbBg ) . '; border-radius:' . absint( $pbBorderRadius ) . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo esc_attr( $pbfontHoverColor ); ?>';this.style.background='<?php echo esc_attr( $pbBgHoverColor ); ?>'"
					onMouseOut="this.style.color='<?php echo esc_attr( $pbFontColor ); ?>';this.style.background='<?php echo esc_attr( $pbBg ); ?>'"
					<?php echo $link_download == true ? 'download ' : ''; ?>
				>
					<?php echo esc_html( $pb_label ); ?>
				</a>
				<?php } ?>
				<?php if ( ! empty( $sb_label ) && ! empty( $sb_link ) ) { ?>
				<a
					href="<?php echo esc_url( $sb_link ) ?>"
					rel="noexternal noopener
					<?php
					echo $sblink_nofollow == true ? 'nofollow ' : '';
					echo $sblink_sponser == true ? 'sponsored' : '';
					?>
					"
					target="<?php echo $sb_newtab != '' ? '_blank' : ''; ?>"
					class="wheeloflife-btn secondary"
					style="font-size: <?php echo absint( $sbFontSize ) . '; color:' . esc_attr( $sbFontColor ) . '; background:' . esc_attr( $sbBg ) . '; border-radius: ' . absint( $sbBorderRadius ) . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo esc_attr( $sbfontHoverColor ); ?>';this.style.background='<?php echo esc_attr( $sbBgHoverColor ); ?>'"
					onMouseOut="this.style.color='<?php echo esc_attr( $sbFontColor ); ?>';this.style.background='<?php echo esc_attr( $sbBg ); ?>'"
					<?php echo $sblink_download == true ? 'download ' : ''; ?>
				>
					<?php echo esc_html( $sb_label ); ?>
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
			<?php
		}
}
get_footer();
