<?php
/**
 * CoachPress Lite Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package CoachPress_Lite
 */

function coachpress_lite_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'coachpress-lite' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'coachpress-lite' ),
        ),
        'newsletter' => array(
            'name'        => __( 'Newsletter Section', 'coachpress-lite' ),
            'id'          => 'newsletter', 
            'description' => __( 'Add "Blossom: BlossomThemes Email Newsletter" widget for newsletter section.', 'coachpress-lite' ),
        ),
        'promo' => array(
            'name'        => __( 'Promotional Section', 'coachpress-lite' ),
            'id'          => 'promo', 
            'description' => __( 'Add "Text & Blossom: Image Text" widget for promotional section. The recommended image size for this section is 370px by 424px.', 'coachpress-lite' ),
        ),
        'about' => array(
            'name'        => __( 'About Section', 'coachpress-lite' ),
            'id'          => 'about', 
            'description' => __( 'Add "Blossom: Featured Page Widget" for about section.', 'coachpress-lite' ),
        ),
        'service' => array(
            'name'        => __( 'Service Section', 'coachpress-lite' ),
            'id'          => 'service', 
            'description' => __( 'Add "Blossom: Icon text" widget for service section. The recommended image size for this section is 356px by 229px.', 'coachpress-lite' ),
        ),
        'testimonial' => array(
            'name'        => __( 'Testimonial Section', 'coachpress-lite' ),
            'id'          => 'testimonial', 
            'description' => __( 'Add "Text & Blossom: Testimonial" widget for testimonial section.', 'coachpress-lite' ),
        ),
        'client' => array(
            'name'        => __( 'Client Section', 'coachpress-lite' ),
            'id'          => 'client', 
            'description' => __( 'Add "Blossom: Client Logo Widget" for client section. The recommended image size for this section is 330px by 190px.', 'coachpress-lite' ),
        ),
        'cta' => array(
            'name'        => __( 'Call To Action Section', 'coachpress-lite' ),
            'id'          => 'cta', 
            'description' => __( 'Add "Blossom: Call To Action" widget for Call to Action section. The background color option will not work for this section', 'coachpress-lite' ),
        ),
        'above-footer'=> array(
            'name'        => __( 'Above Footer', 'coachpress-lite' ),
            'id'          => 'above-footer', 
            'description' => __( 'Add "Image & Blossom: Social Links" for above footer here.', 'coachpress-lite' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'coachpress-lite' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'coachpress-lite' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'coachpress-lite' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'coachpress-lite' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'coachpress-lite' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'coachpress-lite' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'coachpress-lite' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'coachpress-lite' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
}
add_action( 'widgets_init', 'coachpress_lite_widgets_init' );