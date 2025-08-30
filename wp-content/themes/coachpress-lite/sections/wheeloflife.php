<?php
/**
 * Wheel of Life Section
 * 
 * @package CoachPress
 */

$ed_section         = get_theme_mod( 'ed_wheeloflife_section', false );
$section_title      = get_theme_mod( 'wol_section_title' );
$section_content    = get_theme_mod( 'wol_section_content' );
$section_img        = get_theme_mod( 'wheeloflife_img', get_template_directory_uri() . '/images/chart.png' );
$alt_image          = attachment_url_to_postid( $section_img );
$section_shortcode  = get_theme_mod( 'wheeloflife_shortcode' );
$btn_text           = get_theme_mod( 'wheeloflife_cp_text', __( 'Start Assessment','coachpress-lite' ) );
$btn_url            = get_theme_mod( 'wheeloflife_url_cp' );

if ( ( $section_title || $section_content || $section_shortcode || ( $btn_text && $btn_url ) ) && $ed_section ){ ?>
    <section id="wheeloflife_section" class="wheeloflife-section">
        <div class="container">
            <?php 
                if( $section_title ) echo '<div class="section-header"><h2 class="section-title"><span>' . esc_html( $section_title ) . '</span></h2></div>';
                if( $section_content || $section_img ){ ?>
                    <div class="section-content">
                        <?php 
                        if( $section_content ) echo '<p>' . esc_html( $section_content ) . '</p>'; 
                        if( $section_img ) echo '<img src="' . esc_url( $section_img ) . '"  alt="' . esc_attr( get_post_meta( $alt_image, '_wp_attachment_image_alt', true ) ) . '">'; 
                        ?>
                    </div>
                <?php }
                if( $section_shortcode && coachpress_lite_is_wheel_of_life_activated() && $btn_text && $btn_url ){ ?>
                    <div class="wheeloflife-wrapper">
                        <a href="<?php echo esc_url( $btn_url ); ?>" target="nofollow" class="wheeloflife-btn is-primary">
                            <?php echo esc_html( $btn_text ); ?>
                        </a>
                    </div>
                <?php
                }elseif( $section_shortcode && coachpress_lite_is_wheel_of_life_activated() ){
                    echo do_shortcode( wp_kses_post( $section_shortcode ) );
                }else{
                    if( $btn_text && $btn_url ){
                        ?>
                        <div class="wheeloflife-wrapper">
                            <a href="<?php echo esc_url( $btn_url ); ?>" target="nofollow" class="wheeloflife-btn is-primary">
                                <?php echo esc_html( $btn_text ); ?>
                            </a>
                        </div>
                        <?php
                    }
                }    
            ?>
        </div>
    </section>    
<?php
}
