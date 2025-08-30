<?php
/**
 * Services Section
 * 
 * @package CoachPress_Lite
 */

$service_subtitle 		= get_theme_mod( 'service_subtitle', __( 'Services', 'coachpress-lite' ) );
$service_title 			= get_theme_mod( 'service_title', __( 'How Can I Help You', 'coachpress-lite' ) );
$service_content 		= get_theme_mod( 'service_content', __( 'One-on-One Personalised services for clients anywhere in the world, to empower you to feel positive about your life, relationships, career and health.', 'coachpress-lite' ) );
$service_btn			= get_theme_mod( 'service_button_label', __( 'View All Services', 'coachpress-lite' ) );
$service_btn_url		= get_theme_mod( 'service_button_link' );

if( is_active_sidebar( 'service' ) ){ ?>
<section id="service_section" class="service-section">
	<?php if( $service_title || $service_subtitle || $service_content ) : ?>
		<header class="section-header">
			<div class="container">
				<?php if( $service_subtitle ) echo '<span class="section-subtitle">' . esc_html( $service_subtitle ) . '</span>'; ?>
				<?php if( $service_title ) echo '<h2 class="section-title">' . esc_html( $service_title ) . '</h2>'; ?>
				<?php if( $service_content ) echo '<div class="section-desc">' . esc_html( $service_content ) . '</div>'; ?>
			</div>
    	</header>
    <?php endif; ?>
	<div class="service-inner-wrapper">
		<div class="section-grid">
			<?php dynamic_sidebar( 'service' ); ?>
		</div>
		<?php if( $service_btn && $service_btn_url ) { ?>
			<div class="button-wrap">
				<a href="<?php echo esc_attr( $service_btn_url ); ?>" class="btn-readmore"><?php echo esc_html( $service_btn ); ?></a>
			</div>
		<?php } ?>
	</div>
</section> <!-- .service-section -->
<?php
}