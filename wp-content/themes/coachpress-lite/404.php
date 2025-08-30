<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package CoachPress_Lite
 */

$error_404_image = get_theme_mod( 'error_404_image', esc_url( get_template_directory_uri() . '/images/error.jpg' ) );

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<section class="error-404 not-found">
				<figure class="error-img">
                    <img src="<?php echo esc_url( $error_404_image ); ?>" alt="<?php esc_attr_e( 'error image', 'coachpress-lite' ); ?>">
                </figure>
				<div class="page-content">
					<h2><?php esc_html_e( 'Oops!', 'coachpress-lite' ); ?></h2>
					<p><?php esc_html_e( 'The page you are looking for may have been moved, deleted, or possibly never existed.', 'coachpress-lite' ); ?></p>
					<a class="btn-readmore" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html_e( 'Go to Homepage', 'coachpress-lite' ); ?></a>
				</div><!-- .page-content -->
				<div class="error-404-search">
					<?php get_search_form(); ?>
				</div>
			</section><!-- .error-404 -->
		</main><!-- #main -->
		<?php
	    /**
	     * @see coachpress_lite_latest_posts
	    */
	    do_action( 'coachpress_lite_latest_posts' ); ?>
	</div><!-- #primary -->
<?php
    
get_footer();