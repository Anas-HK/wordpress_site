<?php
/**
 * Blog Section
 * 
 * @package CoachPress_Lite
 */

$section_enable     = get_theme_mod( 'ed_blog_section', false );
$section_title      = get_theme_mod( 'blog_section_title', __( 'From The Blog', 'coachpress-lite' ) );
$section_subtitle   = get_theme_mod( 'blog_section_subtitle', __( 'Latest', 'coachpress-lite' ) );
$section_desc       = get_theme_mod( 'blog_section_desc', __( 'Show your latest blog posts here. You can modify this section from Appearance > Customize > Front Page Settings > Blog Section.', 'coachpress-lite' ) );
$readmore           = get_theme_mod( 'blog_readmore', __( 'Read More', 'coachpress-lite' ) );
$ed_crop_blog       = get_theme_mod( 'ed_crop_blog', false );
$blog               = get_option( 'page_for_posts' );
$label              = get_theme_mod( 'blog_view_all', __( 'View All Posts', 'coachpress-lite' ) );

$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true
);

$qry = new WP_Query( $args );

if( $section_enable && ( $section_title || $section_subtitle || $section_desc || $qry->have_posts() ) ){ ?>

<section id="blog_section" class="blog-section">
	<div class="container">
        
        <?php if( $section_title || $section_subtitle || $section_desc ){ ?>
            <header class="section-header">	
                <?php 
                    if( $section_subtitle ) echo '<span class="section-subtitle">' . esc_html( $section_subtitle ) . '</span>';
                    if( $section_title ) echo '<h2 class="section-title">' . esc_html( $section_title ) . '</h2>';
                    if( $section_desc ) echo '<div class="section-desc">' . esc_html( $section_desc ) . '</div>'; 
                ?>
    		</header>
        <?php } ?>
        
        <?php if( $qry->have_posts() ){ ?>
            <div class="section-grid">
    			<?php 
                while( $qry->have_posts() ){
                    $qry->the_post(); ?>
                    <article class="post">
        				<figure class="post-thumbnail" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/images/blog-img-bg.png' ); ?>')">
                            <a href="<?php the_permalink(); ?>">
                            <?php 
                                if( has_post_thumbnail() ){
                                    if( $ed_crop_blog ){
                                        the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) );
                                    }else{
                                        the_post_thumbnail( 'coachpress-lite-grid', array( 'itemprop' => 'image' ) );
                                    }
                                }else{ 
                                    coachpress_lite_get_fallback_svg( 'coachpress-lite-grid' );//fallback
                                }                            
                            ?>                        
                            </a>
                        </figure>
        				<div class="content-wrap">
        					<header class="entry-header">
                                <div class="entry-meta">
                                    <?php coachpress_lite_posted_on();?>
                                    <?php coachpress_lite_category(); ?>
                                </div>
                                <h3 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
        					</header>
        					<div class="entry-content">
        						<?php the_excerpt(); ?>
        					</div>        					
        				

                            <?php if( $readmore ){ ?>
                                <div class="button-wrap">
                                    <a href="<?php the_permalink(); ?>" class="btn-link"><span><?php echo esc_html( $readmore ); ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48.781" height="9.63" viewBox="0 0 48.781 9.63">
                                            <g transform="translate(-1019.528 -1511)">
                                                <path d="M141.589-1.863l-8.34,4.815v-9.63Z" transform="translate(926.721 1517.678)"
                                                    fill="#806e6a" />
                                                <path d="M3089.528,1523h40.965" transform="translate(-2070 -7.502)" fill="none" stroke="#806e6a"
                                                    stroke-width="1" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
        			</article>			
        			<?php 
                }
                wp_reset_postdata();
                ?>
    		</div> 
            <?php if( $blog && $label ){ ?>
                <div class="button-wrap">
                    <a href="<?php the_permalink( $blog ); ?>" class="btn-readmore"><?php echo esc_html( $label ); ?></a>
                </div>
            <?php } ?>       
        <?php } ?>
	</div>
</section>
<?php 
}