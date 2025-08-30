<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CoachPress_Lite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); echo ' itemscope itemtype="https://schema.org/Blog"'; ?>>
	<?php 
        /**
         * @hooked coachpress_lite_post_thumbnail - 10
        */
        do_action( 'coachpress_lite_before_post_entry_content' );
        
        echo '<div class="content-wrap">';
        /**
         * @hooked coachpress_lite_entry_header  - 10 
         * @hooked coachpress_lite_entry_content - 15
         * @hooked coachpress_lite_entry_footer  - 20
        */
        do_action( 'coachpress_lite_post_entry_content' );
        echo '</div>';
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
