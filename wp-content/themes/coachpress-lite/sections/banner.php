<?php
/**
 * Banner Section
 * 
 * @package CoachPress_Lite
 */

$ed_banner        = get_theme_mod( 'ed_banner_section', 'static_banner' );
$slider_type      = get_theme_mod( 'slider_type', 'latest_posts' ); 
$slider_cat       = get_theme_mod( 'slider_cat' );
$posts_per_page   = get_theme_mod( 'no_of_slides', 3 );
$ed_full_image    = get_theme_mod( 'slider_full_image', false );
$ed_caption       = get_theme_mod( 'slider_caption', true );
$slider_subtitle  = get_theme_mod( 'slider_subtitle', '' );
$slider_readmore  = get_theme_mod( 'slider_readmore', __( 'Read More', 'coachpress-lite' ) );
$button_one       = get_theme_mod( 'button_one', __( 'Get Started', 'coachpress-lite' ) );
$button_one_url   = get_theme_mod( 'button_one_url', '#' );
$button_two       = get_theme_mod( 'button_two', __( 'Know More', 'coachpress-lite' ) );
$button_two_url   = get_theme_mod( 'button_two_url', '#' );
$button_one_new   = get_theme_mod( 'button_one_tab_new', false );
$button_two_new   = get_theme_mod( 'button_two_tab_new', false );
$banner_title     = get_theme_mod( 'banner_title', __( 'Be The Spark That Lights Up The Room', 'coachpress-lite' ) );
$banner_subtitle  = get_theme_mod( 'banner_subtitle', __( 'To empower women to make a positive impact on the world with fiery passion, unbridled self-belief, and head-turning style.', 'coachpress-lite' ) );
$banner_newsletter = get_theme_mod( 'banner_newsletter' );
$banner_caption_layout    = get_theme_mod( 'banner_caption_layout', 'right' );
$mobile_header_image      = get_theme_mod( 'header_image_mobile' );
$mobile_img_id            = attachment_url_to_postid( $mobile_header_image);
$target_one = $target_two = '';

$banner_layout = ( $ed_banner == 'static_banner' ) ? 'two' : 'one';

if( ( $ed_banner == 'static_banner' || $ed_banner == 'static_nl_banner' ) && has_custom_header() ){ ?>
    <div id="banner_section" class="site-banner<?php if( $ed_banner == 'static_nl_banner' ) echo ' cta-newsletter-banner'; ?><?php if( $ed_banner == 'static_banner' ) echo ' static-cta'; ?> style-<?php echo esc_attr( $banner_layout ); ?><?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
        <?php if( $ed_banner == 'static_nl_banner' ) {
            echo '<div class="container">';
        }
        ?>
            <div class="item <?php echo esc_attr( $banner_caption_layout ); ?>">
            <?php 
                if( wp_is_mobile() && $mobile_header_image ){
                    echo '<div id="wp-custom-header" class="wp-custom-header">' .
                    wp_get_attachment_image( $mobile_img_id, 'full' ) .
                    '</div>';
                } else {
                    the_custom_header_markup();
                }  
                if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $button_one && $button_one_url ) || ( $button_two && $button_two_url ) ) ){
                    echo '<div class="banner-caption">';
                    echo '<div class="container"><div class="banner-caption-inner">';
                    if( $banner_title ) echo '<h2 class="title">' . esc_html( $banner_title ) . '</h2>';
                    if( $banner_subtitle ) echo '<div class="banner-desc">' . wp_kses_post( wpautop( $banner_subtitle ) ) . '</div>';
                    if( ( $button_one && $button_one_url ) || ( $button_two && $button_two_url ) ) {
                        echo '<div class="button-wrap">';
                        if( $button_one && $button_one_url ) {
                            if( $button_one_new ) $target_one = ' target="_blank"';
                            echo '<a href="' . esc_url( $button_one_url ) . '" class="btn-readmore btn-one"' . $target_one . '>' . esc_html( $button_one ) . '</a>';                              
                        }
                        if( $button_two && $button_two_url ) {
                            if( $button_two_new ) $target_two = ' target="_blank"';
                            echo '<a href="' . esc_url( $button_two_url  ) . '" class="btn-readmore btn-two"' . $target_two . '>' . esc_html( $button_two ) . '</a>';
                        }                      
                        echo '</div>';
                    }
                    echo '</div></div>';
                    echo '</div>';
                }elseif( $ed_banner == 'static_nl_banner' && $banner_newsletter ){
                    echo '<div class="banner-caption">';
                    echo do_shortcode( wp_kses_post( $banner_newsletter ) );
                    echo '</div>';
                }  
            ?>
            </div>
            <?php if( $ed_banner == 'static_nl_banner' ) {
                echo '</div>';
            } 
        ?>
    </div>
<?php
}elseif( $ed_banner == 'slider_banner' ){

    if( $slider_type == 'latest_posts' || $slider_type == 'cat' ){
        
        $image_size = $ed_full_image ? 'full' : 'coachpress-lite-slider';
        $args = array(
            'post_status'         => 'publish',            
            'ignore_sticky_posts' => true
        );
        
        if( $slider_type === 'cat' && $slider_cat ){
            $args['post_type']      = 'post';
            $args['cat']            = $slider_cat; 
            $args['posts_per_page'] = -1;  
        }else{
            $args['post_type']      = 'post';
            $args['posts_per_page'] = $posts_per_page;
        }
            
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
            <div id="banner_section" class="site-banner banner-slider style-one">
                <div class="container">            
                    <div class="item-wrap owl-carousel">            
        			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                        <div class="item">
                            <div class="banner-img">
                				<?php 
                                if( has_post_thumbnail() ){
                				    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
                				}else{ 
                				    coachpress_lite_get_fallback_svg( $image_size );//fallback
                                } ?>
                            </div>
                            <?php if( $ed_caption ){ ?>                        
                				<div class="banner-caption">
        							<?php
                                        if( $slider_subtitle ) echo '<span class="section-subtitle">' . esc_html( $slider_subtitle ) . '</span>';
                                
                                        the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                                        echo '<div class="banner-desc">' . esc_html( get_the_excerpt() ) . '</div>';
                                        if( ( $button_one && $button_one_url ) || $slider_readmore ) {
                                            echo '<div class="button-wrap">';
                                            if( $button_one && $button_one_url ) {
                                                if( $button_one_new ) $target_one = ' target="_blank"';
                                                echo '<a href="' . esc_url( $button_one_url ) . '" class="btn-readmore btn-one"' . $target_one . '>' . esc_html( $button_one ) . '</a>';                              
                                            }
                                            if( $slider_readmore ) {
                                                echo '<a href="' . esc_url( get_permalink()  ) . '" class="btn-readmore btn-two">' . esc_html( $slider_readmore ) . '</a>';
                                            }  
                                            echo '</div>';                            
                                        }
                                    ?>
                				</div>
                            <?php } ?>
            			</div>
        		      	<?php } ?>
                    </div>                
                </div>                
        	</div>
        <?php
        }
        wp_reset_postdata();
    
    }
}