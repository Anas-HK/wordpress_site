<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CoachPress_Lite
 */
    
    /**
     * After Content
     * 
     * @hooked coachpress_lite_content_end - 20
     * @hooked coachpress_lite_instagram - 30
    */
    do_action( 'coachpress_lite_before_footer' );
    
    /**
     * Footer
     * 
     * @hooked coachpress_lite_footer_start  - 20
     * @hooked coachpress_lite_footer_top    - 25
     * @hooked coachpress_lite_footer_mid    - 30
     * @hooked coachpress_lite_footer_bottom - 40
     * @hooked coachpress_lite_footer_end    - 50
    */
    do_action( 'coachpress_lite_footer' );
    
    /**
     * After Footer
     * 
     * @hooked coachpress_lite_page_end    - 20
    */
    do_action( 'coachpress_lite_after_footer' );

    wp_footer(); ?>

</body>
</html>
