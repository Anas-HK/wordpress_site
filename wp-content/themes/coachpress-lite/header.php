<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CoachPress_Lite
 */
    /**
     * Doctype Hook
     * 
     * @hooked coachpress_lite_doctype
    */
    do_action( 'coachpress_lite_doctype' );
?>
<head itemscope itemtype="https://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked coachpress_lite_head
    */
    do_action( 'coachpress_lite_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">

<?php
    wp_body_open();
    
    /**
     * Before Header
     * 
     * @hooked coachpress_lite_page_start - 20 
    */
    do_action( 'coachpress_lite_before_header' );
    
    /**
     * Header
     * 
     * @hooked coachpress_lite_header              - 20     
    */
    do_action( 'coachpress_lite_header' );
    
    /**
     * Content
     * 
     * @hooked coachpress_lite_content_start
    */
    do_action( 'coachpress_lite_content' );