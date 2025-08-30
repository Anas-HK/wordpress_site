<?php
/**
 * CoachPress Lite Template Functions which enhance the theme by hooking into WordPress
 *
 * @package CoachPress_Lite
 */

if( ! function_exists( 'coachpress_lite_doctype' ) ) :
/**
 * Doctype Declaration
*/
function coachpress_lite_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'coachpress_lite_doctype', 'coachpress_lite_doctype' );

if( ! function_exists( 'coachpress_lite_head' ) ) :
/**
 * Before wp_head 
*/
function coachpress_lite_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'coachpress_lite_before_wp_head', 'coachpress_lite_head' );

if( ! function_exists( 'coachpress_lite_page_start' ) ) :
/**
 * Page Start
*/
function coachpress_lite_page_start(){ ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content (Press Enter)', 'coachpress-lite' ); ?></a>
    <?php
}
endif;
add_action( 'coachpress_lite_before_header', 'coachpress_lite_page_start', 20 );

if( ! function_exists( 'coachpress_lite_header' ) ) :
/**
 * Header Start
*/
function coachpress_lite_header(){ 
    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true );
    $ed_search = get_theme_mod( 'ed_header_search', true ); ?>

    <header id="masthead" class="site-header style-one" itemscope itemtype="http://schema.org/WPHeader">
        <div class="header-top">
            <div class="container">
                <?php coachpress_lite_header_contact( false ); ?>
                <?php if( coachpress_lite_social_links( false ) ) {
                    echo '<div class="header-center">
                        <div class="header-social">';
                        coachpress_lite_social_links();
                        echo '</div>
                    </div>';
                } ?>
                <div class="header-right">
                    <?php if( $ed_search ) coachpress_lite_header_search(); ?>
                    <?php if( coachpress_lite_is_woocommerce_activated() && $ed_cart ) {
                        echo '<div class="header-cart">';
                        coachpress_lite_wc_cart_count();
                        echo '</div>';
                    } ?>
                    <?php coachpress_lite_secondary_navigation(); ?>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <?php coachpress_lite_site_branding(); ?>
                <?php coachpress_lite_primary_navigation(); ?>
            </div>
        </div>
    </header>
    <?php coachpress_lite_mobile_navigation(); ?>
    <?php
}
endif;
add_action( 'coachpress_lite_header', 'coachpress_lite_header', 20 );

if( ! function_exists( 'coachpress_lite_content_start' ) ) :
/**
 * Content Start
 *  
*/
function coachpress_lite_content_start(){ ?>       
    <div id="content" class="site-content">
    <?php  
        if( is_page() && coachpress_lite_is_elementor_activated() && coachpress_lite_is_elementor_activated_post() ) return false;  
        if( !( is_front_page() && ! is_home() ) && ! is_404() && !( coachpress_lite_is_woocommerce_activated() && is_singular( 'product' ) ) ) coachpress_lite_page_header();
        if( coachpress_lite_is_woocommerce_activated() && is_singular( 'product' ) ) { ?>
            <div class="breadcrumb-wrapper">
                <div class="container">
                    <?php coachpress_lite_breadcrumb(); ?>
                </div>
            </div>
        <?php }
        if( !( is_front_page() && ! is_home() ) ) echo '<div class="container">';
}
endif;
add_action( 'coachpress_lite_content', 'coachpress_lite_content_start' );

if ( ! function_exists( 'coachpress_lite_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function coachpress_lite_post_thumbnail() {
    $image_size     = 'thumbnail';
    $ed_featured    = get_theme_mod( 'ed_featured_image', true );
    $ed_crop_blog   = get_theme_mod( 'ed_crop_blog', false );
    $ed_crop_single = get_theme_mod( 'ed_crop_single', false );
    $sidebar        = coachpress_lite_sidebar();
    
    if( is_home() || is_archive() || is_search() ){        
        $image_size = coachpress_lite_blog_image_size();
        if( has_post_thumbnail() ){                        
            echo '<figure class="post-thumbnail" style="background-image: url( ' . esc_url( get_template_directory_uri() . '/images/blog-img-bg.png' ) . '"><a href="' . esc_url( get_permalink() ) . '">';
            if( $ed_crop_blog ){
                the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) );    
            }else{
                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
            }
            echo '</a></figure>';
        }       
    }elseif( is_singular() ){
        
        if( is_single() ){
            $image_size = ( $sidebar ) ? 'coachpress-lite-single' : 'coachpress-lite-single-five';
            if( $ed_featured && has_post_thumbnail() ){
                echo '<div class="post-thumbnail">';
                if( $ed_crop_single ){
                    the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) );
                }else{
                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                }
                echo '</div>';    
            }
        }
    }
}
endif;
add_action( 'coachpress_lite_before_page_entry_content', 'coachpress_lite_post_thumbnail' );
add_action( 'coachpress_lite_before_post_entry_content', 'coachpress_lite_post_thumbnail', 10 );
add_action( 'coachpress_lite_before_single_entry_content', 'coachpress_lite_post_thumbnail', 20 );

if( ! function_exists( 'coachpress_lite_entry_header' ) ) :
/**
 * Entry Header
*/
function coachpress_lite_entry_header(){ 
    $blog_page_layout = get_theme_mod( 'blog_page_layout', 'classic' );
    ?>
    <header class="entry-header">
		<?php 
            
            if( 'post' === get_post_type() ){
                echo '<div class="entry-meta">';
                if( $blog_page_layout != 'grid-fullwidth-layout' ) coachpress_lite_posted_on();                
                coachpress_lite_category();
                echo '</div>';
            }		

            if ( is_singular() ) :
                the_title( '<h1 class="entry-title">', '</h1>' );
            else :
                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
            endif; 
        
		?>
	</header>         
    <?php    
}
endif;
add_action( 'coachpress_lite_post_entry_content', 'coachpress_lite_entry_header', 10 );

if( ! function_exists( 'coachpress_lite_entry_content' ) ) :
/**
 * Entry Content
*/
function coachpress_lite_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content" itemprop="text">
		<?php
			if( is_singular() || ! $ed_excerpt ){
                the_content();    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'coachpress-lite' ),
    				'after'  => '</div>',
    			) );
            }else{
                the_excerpt();
            }
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'coachpress_lite_page_entry_content', 'coachpress_lite_entry_content', 15 );
add_action( 'coachpress_lite_post_entry_content', 'coachpress_lite_entry_content', 15 );
add_action( 'coachpress_lite_single_post_entry_content', 'coachpress_lite_entry_content', 15 );

if( ! function_exists( 'coachpress_lite_entry_footer' ) ) :
/**
 * Entry Footer
*/
function coachpress_lite_entry_footer(){ 
    $readmore = get_theme_mod( 'read_more_text', __( 'Read More', 'coachpress-lite' ) ); 

    if( $readmore ) : ?>
    	<div class="button-wrap">
            <a class="btn-link" href="<?php the_permalink(); ?>">
                <?php echo esc_html( $readmore ); ?>
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
	<?php 
        if( get_edit_post_link() ){
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Edit <span class="screen-reader-text">%s</span>', 'coachpress-lite' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        }
    endif;
}
endif;
add_action( 'coachpress_lite_post_entry_content', 'coachpress_lite_entry_footer', 20 );

if( ! function_exists( 'coachpress_lite_single_entry_footer' ) ) :
/**
 * Entry Footer
*/
function coachpress_lite_single_entry_footer(){ ?>
    <footer class="entry-footer">
        <?php
            coachpress_lite_tag();
                        
            if( get_edit_post_link() ){
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'coachpress-lite' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            }
        ?>
    </footer><!-- .entry-footer -->
    <?php 
}
endif;
add_action( 'coachpress_lite_page_entry_content', 'coachpress_lite_single_entry_footer', 20 );
add_action( 'coachpress_lite_single_post_entry_content', 'coachpress_lite_single_entry_footer', 20 );

if( ! function_exists( 'coachpress_lite_navigation' ) ) :
/**
 * Navigation
*/
function coachpress_lite_navigation(){
    if( is_singular( 'post' ) ){
        $next_post = get_next_post();
        $prev_post = get_previous_post();

        $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );

        if( $prev_post || $next_post ){?>            
            <nav class="post-navigation pagination" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'coachpress-lite' ); ?></h2>
                <div class="nav-links">
                    <?php if( $prev_post ){ ?>
                    <div class="nav-previous">
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
                            <article class="post">
                                <figure class="post-thumbnail">
                                    <?php
                                    $prev_img = get_post_thumbnail_id( $prev_post->ID );
                                    if( $prev_img ){
                                        $prev_url = wp_get_attachment_image_url( $prev_img, 'coachpress-lite-grid' );
                                        echo '<img src="' . esc_url( $prev_url ) . '" alt="' . the_title_attribute( 'echo=0', $prev_post ) . '">';                                        
                                    }else{
                                        coachpress_lite_get_fallback_svg( 'coachpress-lite-grid' );
                                    }
                                    ?>
                                </figure>
                                <div class="content-wrap">
                                    <header class="entry-header">
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <?php echo $time_string; ?>
                                            </span>
                                            <?php 
                                            $prev_category = get_the_category( $prev_post->ID ); ?>
                                            <span class="category" itemprop="about">
                                                <?php foreach( $prev_category as $pv_category ) :
                                                    echo '<span>' . esc_html( $pv_category->name ) . '</span>';
                                                endforeach; ?>
                                            </span>
                                        </div>
                                        <h3 class="entry-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></h3>
                                    </header>                               
                                </div>
                            </article>
                            <span class="meta-nav"><?php esc_html_e( 'Previous', 'coachpress-lite' ); ?></span>
                        </a>                        
                    </div>
                    <?php } ?>
                    <?php if( $next_post ){ ?>
                    <div class="nav-next">
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
                            <article class="post">
                                <figure class="post-thumbnail">
                                    <?php
                                    $next_img = get_post_thumbnail_id( $next_post->ID );
                                    if( $next_img ){
                                        $next_url = wp_get_attachment_image_url( $next_img, 'coachpress-lite-grid' );
                                        echo '<img src="' . esc_url( $next_url ) . '" alt="' . the_title_attribute( 'echo=0', $next_post ) . '">';                                        
                                    }else{
                                        coachpress_lite_get_fallback_svg( 'coachpress-lite-grid' );
                                    }
                                    ?>
                                </figure>
                                <div class="content-wrap">
                                    <header class="entry-header">
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <?php echo $time_string; ?>
                                            </span>
                                            <?php 
                                            $next_category = get_the_category( $next_post->ID ); ?>
                                            <span class="category" itemprop="about">
                                                <?php foreach( $next_category as $nt_category ) :
                                                    echo '<span>' . esc_html( $nt_category->name ) . '</span>';
                                                endforeach; ?>
                                            </span>
                                        </div>
                                        <h3 class="entry-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></h3>
                                    </header>                               
                                </div>
                            </article>
                            <span class="meta-nav"><?php esc_html_e( 'Next', 'coachpress-lite' ); ?>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </nav>        
            <?php
        }
    }else{    
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'coachpress-lite' ),
            'next_text'          => __( 'Next', 'coachpress-lite' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'coachpress-lite' ) . ' </span>',
        ) );
    }
}
endif;
add_action( 'coachpress_lite_after_post_content', 'coachpress_lite_navigation', 15 );
add_action( 'coachpress_lite_after_posts_content', 'coachpress_lite_navigation' );

if( ! function_exists( 'coachpress_lite_author' ) ) :
/**
 * Author Section
*/
function coachpress_lite_author(){ 
    $ed_author    = get_theme_mod( 'ed_author', false );
    $author_title = get_the_author();
    if( ! $ed_author && get_the_author_meta( 'description' ) ){ ?>
    <div class="author-section">
        <div class="author-img-title-wrap">
    		<figure class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 95 ); ?></figure>
            <div class="author-title-wrap">
                <?php if( $author_title ) echo '<h3 class="author-name">' . esc_html( $author_title ) . '</h3>'; ?>
            </div>
        </div>
		<div class="author-content">
			<?php echo wpautop( wp_kses_post( get_the_author_meta( 'description' ) ) ); ?>		
		</div>
	</div>
    <?php
    }
}
endif;
add_action( 'coachpress_lite_after_post_content', 'coachpress_lite_author', 25 );

if( ! function_exists( 'coachpress_lite_related_posts' ) ) :
/**
 * Related Posts 
*/
function coachpress_lite_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', true );
    
    if( $ed_related_post ){
        coachpress_lite_get_posts_list( 'related' );    
    }
}
endif;                                                                               
add_action( 'coachpress_lite_after_post_content', 'coachpress_lite_related_posts', 35 );

if( ! function_exists( 'coachpress_lite_latest_posts' ) ) :
/**
 * Latest Posts
*/
function coachpress_lite_latest_posts(){ 
    coachpress_lite_get_posts_list( 'latest' );
}
endif;
add_action( 'coachpress_lite_latest_posts', 'coachpress_lite_latest_posts' );

if( ! function_exists( 'coachpress_lite_comment' ) ) :
/**
 * Comments Template 
*/
function coachpress_lite_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if( get_theme_mod( 'ed_comments', true ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
}
endif;
add_action( 'coachpress_lite_after_post_content', 'coachpress_lite_comment', coachpress_lite_comment_toggle() );
add_action( 'coachpress_lite_after_page_content', 'coachpress_lite_comment' );

if( ! function_exists( 'coachpress_lite_content_end' ) ) :
/**
 * Content End
*/
function coachpress_lite_content_end(){ 

    if( !( is_front_page() && ! is_home() ) && !( is_page() && coachpress_lite_is_elementor_activated() && coachpress_lite_is_elementor_activated_post() ) ) echo '</div>'; ?>       
    </div><!-- .site-content -->
    <?php
}
endif;
add_action( 'coachpress_lite_before_footer', 'coachpress_lite_content_end', 20 );

if( ! function_exists( 'coachpress_lite_instagram' ) ) :
/**
 * Before Footer
*/
function coachpress_lite_instagram(){ 

    $ed_instagram = get_theme_mod( 'ed_instagram', false );
    $insta_code   = get_theme_mod( 'instagram_shortcode', '[instagram-feed]' );

    if( $ed_instagram ){
        echo '<div id="instagram_section" class="instagram-section">';
        echo do_shortcode( $insta_code );
        echo '</div>';    
    }
    
}
endif;
add_action( 'coachpress_lite_before_footer', 'coachpress_lite_instagram', 30 );

if( ! function_exists( 'coachpress_lite_footer_start' ) ) :
/**
 * Footer Start
*/
function coachpress_lite_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'coachpress_lite_footer', 'coachpress_lite_footer_start', 20 );

if( ! function_exists( 'coachpress_lite_footer_top' ) ) :
/**
 * Footer Top
*/
function coachpress_lite_footer_top(){
    $footer_above_bg = get_theme_mod( 'footer_above_bg' );             
    if( is_active_sidebar( 'above-footer' ) ){ ?>
        <div class="footer-top" <?php if( $footer_above_bg ) { ?> style="background-image: url('<?php echo esc_url( $footer_above_bg ); ?>');" <?php } ?>>
    		<div class="container">
    			<div class="grid column-1">
    				<div class="col">
    				   <?php dynamic_sidebar( 'above-footer' ); ?>	
    				</div>
                </div>
    		</div>
    	</div>
        <?php 
    }
}
endif;
add_action( 'coachpress_lite_footer', 'coachpress_lite_footer_top', 25 );

if( ! function_exists( 'coachpress_lite_footer_mid' ) ) :
/**
 * Footer Mid
*/
function coachpress_lite_footer_mid(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
                 
    if( $active_sidebars ){ ?>
        <div class="footer-mid">
            <div class="container">
                <div class="grid column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
                    <div class="col">
                       <?php dynamic_sidebar( $active ); ?> 
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <?php 
    }
}
endif;
add_action( 'coachpress_lite_footer', 'coachpress_lite_footer_mid', 30 );

if( ! function_exists( 'coachpress_lite_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function coachpress_lite_footer_bottom(){ ?>
    <div class="footer-bottom">
        <div class="footer-menu">
            <div class="container">
                <?php coachpress_lite_footer_navigation(); ?>
            </div>
        </div>
		<div class="site-info">   
            <div class="container">         
            <?php
                coachpress_lite_get_footer_copyright();
                echo esc_html__( ' CoachPress Lite | Developed By ', 'coachpress-lite' ); 
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'coachpress-lite' ) . '</a>.';                
                printf( esc_html__( ' Powered by %s. ', 'coachpress-lite' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'coachpress-lite' ) ) .'" target="_blank">WordPress</a>' );
                if( function_exists( 'the_privacy_policy_link' ) ){
                    the_privacy_policy_link();
                }
            ?>               
            </div>
        </div>
        <button class="back-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M6.101 359.293L25.9 379.092c4.686 4.686 12.284 4.686 16.971 0L224 198.393l181.13 180.698c4.686 4.686 12.284 4.686 16.971 0l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L232.485 132.908c-4.686-4.686-12.284-4.686-16.971 0L6.101 342.322c-4.687 4.687-4.687 12.285 0 16.971z"></path></svg>
        </button><!-- .back-to-top -->
	</div>
    <?php
}
endif;
add_action( 'coachpress_lite_footer', 'coachpress_lite_footer_bottom', 40 );

if( ! function_exists( 'coachpress_lite_footer_end' ) ) :
/**
 * Footer End 
*/
function coachpress_lite_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'coachpress_lite_footer', 'coachpress_lite_footer_end', 50 );

if( ! function_exists( 'coachpress_lite_page_end' ) ) :
/**
 * Page End
*/
function coachpress_lite_page_end(){ ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'coachpress_lite_after_footer', 'coachpress_lite_page_end', 20 );