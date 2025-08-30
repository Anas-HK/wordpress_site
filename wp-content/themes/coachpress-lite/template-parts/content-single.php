<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CoachPress_Lite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 

        coachpress_lite_single_entry_header(); 

        /**
         * @hooked coachpress_lite_post_thumbnail        - 20        
        */
        do_action( 'coachpress_lite_before_single_entry_content' );
        
        echo '<div class="content-wrap">';
        coachpress_lite_single_article_meta();
        /**
         * @hooked coachpress_lite_entry_content         - 15
         * @hooked coachpress_lite_single_entry_footer   - 20
        */
        do_action( 'coachpress_lite_single_post_entry_content' );
        echo '</div>';
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
