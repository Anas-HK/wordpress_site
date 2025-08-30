<?php
/**
 * Testimonial Section
 * 
 * @package CoachPress_Lite
 */

$testimonial_subtitle 		= get_theme_mod( 'testimonial_subtitle', __( 'Testimonials', 'coachpress-lite' ) );
$testimonial_title 			= get_theme_mod( 'testimonial_title', __( 'Loved and Trusted by My Clients', 'coachpress-lite' ) );
$testimonial_content 		= get_theme_mod( 'testimonial_content', __( 'Read what my clients are saying whom I\'ve helped to make a difference in their life.', 'coachpress-lite' ) );
$testimonial_video_url 		= get_theme_mod( 'testimonial_video_url' );

if( is_active_sidebar( 'testimonial' ) ){ ?>
<section id="testimonial_section" class="testimonial-section">
	<?php if( $testimonial_title || $testimonial_subtitle || $testimonial_content ) : ?>
		<header class="section-header">
			<div class="container">
				<?php if( $testimonial_subtitle ) echo '<span class="section-subtitle">' . esc_html( $testimonial_subtitle ) . '</span>'; ?>
				<?php if( $testimonial_title ) echo '<h2 class="section-title">' . esc_html( $testimonial_title ) . '</h2>'; ?>
				<?php if( $testimonial_content ) echo '<div class="section-desc">' . esc_html( $testimonial_content ) . '</div>'; ?>
			</div>
    	</header>
    <?php endif; ?>
	<div class="section-grid<?php if( $testimonial_video_url ) { echo ' has-vdo-testimonial';} ?>">
		<div class="container">
			<div class="testimonial-wrap">
				<div class="testimonial-wrap-inner owl-carousel">
					<?php dynamic_sidebar( 'testimonial' ); ?>
				</div>
	    	</div>
		    <?php if( $testimonial_video_url ) : ?>
			    <div class="vdo-testimonial-wrap">
					<div class="vdo-testimonial">
						<?php echo htmlspecialchars_decode( stripslashes( $testimonial_video_url ) ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section> <!-- .testimonial-section -->
<?php
}