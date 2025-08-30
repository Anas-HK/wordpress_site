<?php
/**
 * Toolkit Filters
 *
 * @package CoachPress_Lite
 */

if( ! function_exists( 'coachpress_lite_default_image_text_size' ) ) :
    function coachpress_lite_default_image_text_size(){
        return 'coachpress-litemo';
    }
endif;
add_filter( 'bttk_it_img_size', 'coachpress_lite_default_image_text_size' );

if( ! function_exists( 'coachpress_lite_default_client_logo_size' ) ) :
    function coachpress_lite_default_client_logo_size(){
        return 'coachpress-lite-client';
    }
endif;
add_filter( 'bttk_cl_img_size', 'coachpress_lite_default_client_logo_size' );

if( ! function_exists( 'coachpress_lite_featured_page_alignment' ) ) :
    function coachpress_lite_featured_page_alignment(){
        
        $array = array(
            'right'     => __( 'Right', 'coachpress-lite' ),
            'left'      => __( 'Left', 'coachpress-lite' ),
        );

        return $array;
    }
endif;
add_filter( 'bttk_img_alignment', 'coachpress_lite_featured_page_alignment' );

if( ! function_exists( 'coachpress_lite_default_icon_text_size' ) ) :
    function coachpress_lite_default_icon_text_size(){
        return 'coachpress-lite-service';
    }
endif;
add_filter( 'bttk_icon_img_size', 'coachpress_lite_default_icon_text_size' );

if( ! function_exists( 'coachpress_lite_featured_page_widget_filter' ) ) :
/**
 * Filter for Featured page widget
*/
function coachpress_lite_featured_page_widget_filter( $html, $args, $instance ){ 
    $read_more         = !empty( $instance['readmore'] ) ? $instance['readmore'] : __( 'Read More', 'coachpress-lite' );      
    $show_feat_img     = !empty( $instance['show_feat_img'] ) ? $instance['show_feat_img'] : '' ;  
    $show_page_content = !empty( $instance['show_page_content'] ) ? $instance['show_page_content'] : '' ;        
    $show_readmore     = !empty( $instance['show_readmore'] ) ? $instance['show_readmore'] : '' ;        
    $page_list         = !empty( $instance['page_list'] ) ? $instance['page_list'] : 1 ;
    $image_alignment   = !empty( $instance['image_alignment'] ) ? $instance['image_alignment'] : 'left' ;
    $about_signature   = get_theme_mod( 'about_signature' );

    if( !isset( $page_list ) || $page_list == '' ) return;
    
    $post_no = get_post( $page_list ); 
    
    $target = 'target="_blank"';
    if( isset($instance['target']) && $instance['target']!='' ) {
        $target = 'target="_self"';
    }
    
    if( $post_no ){
        setup_postdata( $post_no );
        ob_start(); ?>
        <div class="widget-featured-holder <?php echo esc_attr($image_alignment);?>">
            <div class="featured-holder-wrap">                    
                <?php

                if( ( has_post_thumbnail( $post_no ) ) && $show_feat_img ){ ?>
                <div class="img-holder">
                    <a <?php echo $target;?> href="<?php the_permalink( $post_no ); ?>">
                        <?php 
                        $featured_img_size = 'full';
                        if( has_post_thumbnail( $post_no ) ) echo get_the_post_thumbnail( $post_no, $featured_img_size ); ?>
                        <?php if( $about_signature ) echo '<span class="widget-signature"><img src="' . esc_url( $about_signature ) . '"' . '</span>'; ?>
                    </a>
                </div>
                <?php } ?>
                <div class="text-holder">
                    <?php 
                    echo $args['before_title']; //Done for SEO
                    echo esc_html( $post_no->post_title );
                    echo $args['after_title'];
                    ?>
                    <div class="featured_page_content">
                        <?php 
                        if( isset( $show_page_content ) && $show_page_content!='' ){
                            echo apply_filters( 'the_content', $post_no->post_content );                                
                        }else{
                            echo apply_filters( 'the_excerpt', get_the_excerpt( $post_no ) );                                
                        }
                        
                        if( isset( $show_readmore ) && $show_readmore!='' ){ ?>
                            <a href="<?php the_permalink( $post_no ); ?>" <?php echo $target;?> class="btn-readmore"><?php echo esc_html( $read_more );?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>                    
            </div>
        </div>                    
        <?php    
        $html = ob_get_clean();
        wp_reset_postdata();
        return $html;
    }
}
endif;
add_filter( 'blossom_featured_page_widget_filter', 'coachpress_lite_featured_page_widget_filter', 10, 3 );

if( ! function_exists( 'coachpress_lite_cta_widget_filter' ) ) :
/**
 * Filter for CTA widget
*/
function coachpress_lite_cta_widget_filter( $html, $args, $instance ){ 
        
    $title            = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
    $content          = ! empty( $instance['content'] ) ? $instance['content'] : '';
    $button_alignment = ! empty( $instance['button_alignment'] ) ? $instance['button_alignment'] : '';
    $button_number    = ! empty( $instance['button_number'] ) ? $instance['button_number'] : '1';
    $button1_text     = ! empty( $instance['button1_text'] ) ? $instance['button1_text'] : '' ;
    $button2_text     = ! empty( $instance['button2_text'] ) ? $instance['button2_text'] : '' ;
    $button1_url      = ! empty( $instance['button1_url'] ) ? $instance['button1_url'] : '' ;
    $button2_url      = ! empty( $instance['button2_url'] ) ? $instance['button2_url'] : '' ;

    $widget_bg_image  = !empty($instance['widget-bg-image']) ? esc_attr($instance['widget-bg-image']) : '';

    $target = 'target="_self"';
    if( isset($instance['target']) && $instance['target']!='' ){
        $target = 'target="_blank"';
    }

    ob_start();
    $image_source = '';

    if( $widget_bg_image ){
        /** Added to work for demo content compatible */   
        $attachment_id = $widget_bg_image;
        $cta_img_size = 'full';

        if ( !filter_var( $widget_bg_image, FILTER_VALIDATE_URL ) === false ) {
            $attachment_id = bttk_get_attachment_id( $widget_bg_image );
        }

        $image_source   = wp_get_attachment_image( $attachment_id, $cta_img_size );
                    
        $ctaclass = ' bttk-cta-bg';
    }else{
        $ctaclass = ' text';
    }

    ?>        
    <div class="<?php echo esc_attr( $button_alignment . $ctaclass ); ?>">
        <?php if( $image_source ) echo '<div class="cta-img-holder">' . $image_source . '</div>'; ?>
        <div class="blossomtheme-cta-container">
            <?php
            if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance ) . $args['after_title']; ?>
            <div class="text-holder">
                <?php if( $content ) echo wpautop( wp_kses_post( $content ) ); ?>
                <div class="button-wrap">
                    <?php
                        if( $button_number == '1' )
                        {
                            if( isset( $button1_text ) && $button1_url !='' ) echo '<a '.$target. 'href="' . esc_url( $button1_url ) . '" class="btn-cta btn-1">' . esc_html( $button1_text ) . '</a>';
                        }
                        if( $button_number == '2' )
                        {
                            if( isset( $button1_text ) && $button1_url !='' ) echo '<a '.$target. ' href="' . esc_url( $button1_url ) . '" class="btn-cta btn-1">' . esc_html( $button1_text ) . '</a>';
                            if( isset( $button2_text ) && $button2_url !='' ) echo '<a '.$target. '  href="' . esc_url( $button2_url ) . '" class="btn-cta btn-2">' . esc_html( $button2_text ) . '</a>';
                        }
                    ?>
                </div>
            </div>
        </div> 
    </div>        
    <?php 
    $html = ob_get_clean();
    wp_reset_postdata();
    return $html;
}
endif;
add_filter( 'blossom_cta_widget_filter', 'coachpress_lite_cta_widget_filter', 10, 3 );

if( ! function_exists( 'coachpress_lite_testimonial_widget_filter' ) ) :
/**
 * Filter for Testimonial widget
*/
function coachpress_lite_testimonial_widget_filter( $html, $args, $instance ){ 

    $name        = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
    $designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
    $testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
    $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';

    if( $image ){
        /** Added to work for demo testimonial compatible */
        $attachment_id = $image;
        if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
            $attachment_id = bttk_get_attachment_id( $image );
        }

        $icon_img_size = apply_filters('bttk_testimonial_icon_img_size','thumbnail');
    }
    
    ob_start(); 
    ?>
    
        <div class="bttk-testimonial-holder">
            <div class="bttk-testimonial-inner-holder">
                <?php if( $image ){ ?>
                    <div class="img-holder">
                        <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, array( 'alt' => esc_attr( $name )));?>
                    </div>
                <?php }?>
    
                <div class="text-holder">                            
                    <?php if( $testimonial ) echo '<div class="testimonial-content">' . wpautop( wp_kses_post( $testimonial ) ) . '</div>'; ?>
                </div>
                <div class="testimonial-meta">
                    <?php 
                        if( $name ) echo '<span class="name">' . esc_html( $name ) . '</span>';
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                        }
                    ?>
                </div>  
            </div>
        </div>
    <?php 
    $html = ob_get_clean();   
    return $html;
}
endif;
add_filter( 'blossom_testimonial_widget_filter', 'coachpress_lite_testimonial_widget_filter', 10, 3 );