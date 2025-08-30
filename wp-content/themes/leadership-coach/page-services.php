<?php
/**
 * Template Name: Services Page
 * 
 * The template for displaying the Services page with dynamic content
 * from Services custom post type and WordPress native editing compatibility.
 *
 * @package Leadership Coach
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'services-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    
                    <!-- Main Content Section -->
                    <div class="services-intro">
                        <?php
                        the_content();
                        
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leadership-coach' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <!-- Services Display Section intentionally removed for a clean Services page -->

                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();