<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CoachPress_Lite
 */

global $wp_query, $post;
$ed_slider = get_theme_mod( 'ed_banner_section', 'static_banner' );
?>

<section class="no-results not-found">
	<div class="page-content">
        <h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'coachpress-lite' ); ?></h2>
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : 
            if( $ed_slider && $wp_query->found_posts == 0 && $post ){ ?>
                <p><?php
    				printf(
    					wp_kses(
    						/* translators: 1: link to WP admin new post page. */
    						__( 'Your blog posts are displayed in the slider. To display blog post here, <a href="%1$s">please publish more blog posts</a>.', 'coachpress-lite' ),
    						array(
    							'a' => array(
    								'href' => array(),
    							),
    						)
    					),
    					esc_url( admin_url( 'post-new.php' ) )
    				);
    			?></p>
                
            <?php
            }else{        
        ?>
            <p><?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'coachpress-lite' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
			?></p>

		<?php }
		
        elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'coachpress-lite' ); ?></p>
			<?php
				get_search_form();

		else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'coachpress-lite' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
