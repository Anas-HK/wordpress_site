<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CoachPress_Lite
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/content', 'single' );

            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->

        <?php
        /**
         * @hooked coachpress_lite_navigation           - 15  
         * @hooked coachpress_lite_author               - 25
         * @hooked coachpress_lite_related_posts        - 35
         * @hooked coachpress_lite_comment              - 45
        */
        do_action( 'coachpress_lite_after_post_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();