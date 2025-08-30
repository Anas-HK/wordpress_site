<?php
/**
 * CoachPress Lite Standalone Functions.
 *
 * @package CoachPress_Lite
 */

if ( ! function_exists( 'coachpress_lite_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function coachpress_lite_posted_on() {

    $ed_post_date = get_theme_mod( 'ed_post_date', false );
    
    if( $ed_post_date ) return false;

	$ed_updated_post_date = get_theme_mod( 'ed_post_update_date', true );
    $on = '';
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		if( $ed_updated_post_date && ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) ){
            $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
            $on = ( is_single() ) ? __( 'Updated on ', 'coachpress-lite' ) : '';		  
		}else{
            $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';  
		}        
	}else{
	   $time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';   
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    $posted_on = sprintf( '%1$s %2$s', esc_html( $on ), '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );
	
	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'coachpress_lite_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function coachpress_lite_posted_by() {
    $ed_post_author = get_theme_mod( 'ed_post_author', false );
	
    if( $ed_post_author ) return false;

    $byline = sprintf(
		/* translators: %s: post author. */
		'%s',
		'<span itemprop="name"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url"><img class="avatar" src="' . esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 75 ) ) ) . '" alt="' . esc_attr( get_the_author() ) . '" /> <b class="fn">' . esc_html( get_the_author() ) . '</b></a></span>' 
    );
	echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">' . $byline . '</span>';
}
endif;

if( ! function_exists( 'coachpress_lite_comment_count' ) ) :
/**
 * Comment Count
*/
function coachpress_lite_comment_count(){
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comment-box"><svg xmlns="http://www.w3.org/2000/svg" width="17.736" height="17.73" viewBox="0 0 17.736 17.73">
            <path
                d="M479.879,292.355c.67.662,1.332,2.288,1.955,2.95a2.216,2.216,0,0,1,.295.683,2.1,2.1,0,0,1-.716.191c-1.409-.158-2.812-.366-4.219-.531a2.077,2.077,0,0,0-.88.078,8.874,8.874,0,1,1,5.718-9.975C482.522,288.588,481.793,290.1,479.879,292.355Zm.519,2.841.06-.147c-.39-.371-.785-1.738-1.169-2.115-.565-.555-.562-.6-.028-1.184,2.307-2.536,2.737-5.3,1.059-8.28a8.016,8.016,0,1,0-4.5,11.5,3.59,3.59,0,0,1,1.728-.143C478.5,294.962,479.449,295.072,480.4,295.2Z"
                transform="translate(-464.443 -278.495)" fill="#806e6a" /></svg>';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'coachpress-lite' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}    
}
endif;

if ( ! function_exists( 'coachpress_lite_category' ) ) :
/**
 * Prints categories
 */
function coachpress_lite_category(){
    $ed_category  = get_theme_mod( 'ed_category', false );
    // Hide category and tag text for pages.
	if ( 'post' === get_post_type() && ! $ed_category ) {
		$categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
			echo '<span class="category" itemprop="about">' . $categories_list . '</span>';
		}
	}
}
endif;

if ( ! function_exists( 'coachpress_lite_tag' ) ) :
/**
 * Prints tags
 */
function coachpress_lite_tag(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		$tags_list = get_the_tag_list( '', ' ' );
		if ( $tags_list ) {
			printf( '<div class="tags" itemprop="about">' . esc_html__( '%1$sTags:%2$s %3$s', 'coachpress-lite' ) . '</div>', '<span>', '</span>', $tags_list );
		}
	}
}
endif;

if( ! function_exists( 'coachpress_lite_get_posts_list' ) ) :
/**
 * Returns Latest, Related & Popular Posts
*/
function coachpress_lite_get_posts_list( $status ){
    
    if( !is_singular( 'post' ) ) return false;
    
    global $post;
    $sidebar = coachpress_lite_sidebar();
    
    $args = array(
        'post_type'           => 'post',
        'posts_status'        => 'publish',
        'ignore_sticky_posts' => true
    );
    
    switch( $status ){
        case 'latest':        
        $args['posts_per_page']  = 6;
        $subtitle               = __( 'From Our Blog', 'coachpress-lite' );
        $title                  = __( 'Recommended Articles', 'coachpress-lite' );
        $image_size             = 'coachpress-lite-classic';
        $blog                   = get_option( 'page_for_posts' );
        $label                  = get_theme_mod( 'error_404_button_label', __( 'Go To Blog', 'coachpress-lite' ) );
        $readmore               = get_theme_mod( 'read_more_text', __( 'Read More', 'coachpress-lite' ) );
        break;
        
        case 'related':
        $args['posts_per_page'] = 4;
        $args['post__not_in']   = array( $post->ID );
        $args['orderby']        = 'rand';
        $subtitle               = '';
        $title                  = get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'coachpress-lite' ) );
        $image_size             = ( $sidebar ) ? 'coachpress-lite-grid' : 'coachpress-lite-grid-full';
        $blog                   = '';
        $label                  = '';
        $readmore               = '';
        $cats                   = get_the_category( $post->ID );        
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }    
        break;
    }
    
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>    
        <div class="additional-post">
    		<?php 
            if( $subtitle ) echo '<span class="section-subtitle">' . esc_html( $subtitle ) . '</span>';
            if( $title ) echo '<h3 class="post-title">' . esc_html( $title ) . '</h3>'; ?>
            <div class="section-grid">
    			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                <article class="post">
                    <figure class="post-thumbnail" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/images/blog-img-bg.png' ); ?>')">
        				<a href="<?php the_permalink(); ?>">
                            <?php
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                                }else{ 
                                    coachpress_lite_get_fallback_svg( $image_size );//fallback
                                }
                            ?>
                        </a>
                    </figure>
    				<div class="content-wrap">
                        <header class="entry-header">
        					<div class="entry-meta">    
                                <?php
                                coachpress_lite_category();
                                ?>
                            </div>
                            <?php
                                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                            ?>                        
        				</header>
                        <?php if( $readmore ) : ?>
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
                        <?php endif; ?>
                    </div>
    			</article>
    			<?php }?>    
            </div>
            <?php if( $blog && $label ) :   ?>
                <div class="button-wrap">
                    <a href="<?php the_permalink( $blog ); ?>" class="btn-readmore btn-two"><?php echo esc_html( $label ); ?></a>
                </div>
            <?php endif; ?>		
    	</div>
        <?php
    }
    wp_reset_postdata();
}
endif;

if( ! function_exists( 'coachpress_lite_site_branding' ) ) :
/**
 * Site Branding
*/
function coachpress_lite_site_branding(  $mobile = false ){
    $site_title       = get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    $header_text      = get_theme_mod( 'header_text', 1 );

    if( has_custom_logo() || $site_title || $site_description || $header_text ) :
        if( has_custom_logo() && ( $site_title || $site_description ) && $header_text ) {
            $branding_class = ' has-image-text';
        }else{
            $branding_class = '';
        }?>
        <div class="site-branding<?php echo esc_attr( $branding_class ); ?>" itemscope itemtype="http://schema.org/Organization">  
            <div class="site-logo">
                <?php 
                if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                    the_custom_logo();
                }  ?>
            </div>

            <?php 
            if( $site_title || $site_description ) :
                echo '<div class="site-title-wrap">';
                if( is_front_page() && !$mobile ){ ?>
                    <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php 
                }else{ ?>
                    <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                <?php }
                
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ){ ?>
                    <p class="site-description" itemprop="description"><?php echo $description; ?></p>
                <?php }
                echo '</div>';
            endif; ?>
        </div>    
    <?php endif;
}
endif;

if( ! function_exists( 'coachpress_lite_social_links' ) ) :
/**
 * Social Links 
*/
function coachpress_lite_social_links( $echo = true ){ 

    $social_links = get_theme_mod( 'social_links', '' );
    $ed_social    = get_theme_mod( 'ed_social_links', false ); 
    
    if( $ed_social && $social_links && $echo ){ ?>
    <ul class="social-list">
    	<?php 
        foreach( $social_links as $link ){
    	   if( $link['link'] ){ ?>
            <li>
                <a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="nofollow noopener">
                    <i class="<?php echo esc_attr( $link['font'] ); ?>"></i>
                </a>
            </li>    	   
            <?php
            } 
        } 
        ?>
	</ul>
    <?php    
    }elseif( $ed_social && $social_links ){
        return true;
    }else{
        return false;
    }
    ?>
    <?php                                
}
endif;

if( ! function_exists( 'coachpress_lite_form_section' ) ) :
/**
 * Form Icon
*/
function coachpress_lite_form_section(){ ?>
    <div class="form-section">
		<span id="btn-search" class="fas fa-search"></span>
	</div>
    <?php
}
endif;

if( ! function_exists( 'coachpress_lite_header_search' ) ) :
/**
 * Form 
*/
function coachpress_lite_header_search(){ ?>
<div class="header-search">
        <button class="search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="21.863" height="22" viewBox="0 0 21.863 22">
                <path
                d="M24.863,1170.255l-2.045,2.045L18,1167.482v-1.091l-.409-.409a8.674,8.674,0,0,1-5.727,2.046,8.235,8.235,0,0,1-6.273-2.591A8.993,8.993,0,0,1,3,1159.164a8.235,8.235,0,0,1,2.591-6.273,8.993,8.993,0,0,1,6.273-2.591,8.441,8.441,0,0,1,6.273,2.591,8.993,8.993,0,0,1,2.591,6.273,8.675,8.675,0,0,1-2.045,5.727l.409.409h.955ZM7.5,1163.664a5.76,5.76,0,0,0,4.364,1.773,5.969,5.969,0,0,0,4.364-1.773,6.257,6.257,0,0,0,0-8.727,5.76,5.76,0,0,0-4.364-1.773,5.969,5.969,0,0,0-4.364,1.773,5.76,5.76,0,0,0-1.773,4.364A6.308,6.308,0,0,0,7.5,1163.664Z"
                transform="translate(-3 -1150.3)" fill="#806e6a" /></svg>
        </button>
        <div class="header-search-wrap search-modal cover-modal" data-modal-target-string=".search-modal">
            <div class="header-search-inner">
                <?php get_search_form(); ?>
                <button class="close" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"><?php esc_html_e( 'Close', 'coachpress-lite' ); ?></button>
            </div>
        </div>
    </div><!-- .header-search -->
    <?php
}
endif;

if( ! function_exists( 'coachpress_lite_header_contact' ) ) :
/**
 * Form 
*/
function coachpress_lite_header_contact( $set_mob = false ){ 
    $phone = get_theme_mod( 'phone' );
    $email = get_theme_mod( 'email' );

    if( $phone || $email ) :
        echo ( $set_mob ) ? '<div class="header-center">':'<div class="header-left">';
        if( !empty( $phone ) ) echo '<div class="header-block"><i class="fas fa-phone"></i><a href="tel:' . preg_replace( '/[^\d+]/', '', $phone ) . '" class="phone">' . esc_html( $phone ) . '</a></div>';
        if( !empty( $email ) ) echo '<div class="header-block"><i class="fas fa-envelope"></i><a href="mailto:' . sanitize_email( $email ) . '" class="email">' . sanitize_email( $email ) . '</a></div>';
        echo '</div>';
    endif;
}
endif;

if( ! function_exists( 'coachpress_lite_primary_navigation' ) ) :
/**
 * Primary Navigation.
*/
function coachpress_lite_primary_navigation( $pn_id = true ){ 
    
    if( current_user_can( 'manage_options' ) || has_nav_menu( 'primary' ) ) { ?>
        <nav id="<?php echo ( ! $pn_id ) ? 'mobile-primary' : 'site'; ?>-navigation" class="main-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
            <button class="toggle-btn">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>
    		<?php
    			wp_nav_menu( array(
    				'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
    				'menu_id'        => 'primary-menu',
                    'fallback_cb'    => 'coachpress_lite_primary_menu_fallback',
    			) );
    		?>
    	</nav><!-- #site-navigation -->
    <?php
    }
}
endif;

if( ! function_exists( 'coachpress_lite_primary_menu_fallback' ) ) :
/**
 * Fallback for primary menu
*/
function coachpress_lite_primary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<div class="menu-primary-menu-container"><ul id="primary-menu" class="nav-menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'coachpress-lite' ) . '</a></li>';
        echo '</ul></div>';
    }
}
endif;

if( ! function_exists( 'coachpress_lite_secondary_navigation' ) ) :
/**
 * Secondary Navigation
*/
function coachpress_lite_secondary_navigation( $sn_id = true ){ 
    if( current_user_can( 'manage_options' ) || has_nav_menu( 'secondary' ) ) { ?>
    	<nav id="<?php echo ( ! $sn_id ) ? 'mobile-secondary' : 'secondary'; ?>-nav" class="secondary-menu">
            <button class="toggle-btn" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>
            <div class="secondary-menu-list menu-modal cover-modal" data-modal-target-string=".menu-modal">
                <button class="close close-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal"></button>
                <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'coachpress-lite' ); ?>">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'secondary',
                            'menu_class'     => 'nav-menu menu-modal',
                            'menu_id'        => 'secondary-menu',
                            'fallback_cb'    => 'coachpress_lite_secondary_menu_fallback',
                        ) );
                    ?>
                </div>
            </div>
    	</nav>
    <?php
    }
}
endif;

if( ! function_exists( 'coachpress_lite_secondary_menu_fallback' ) ) :
/**
 * Fallback for secondary menu
*/
function coachpress_lite_secondary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<div class="menu-secondary-menu-container"><ul id="secondary-menu" class="nav-menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'coachpress-lite' ) . '</a></li>';
        echo '</ul></div>';
    }
}
endif;

if( ! function_exists( 'coachpress_lite_footer_navigation' ) ) :
/**
 * Secondary Navigation
*/
function coachpress_lite_footer_navigation(){ ?>
    <nav class="footer-navigation">
        <?php
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'menu_class'     => 'nav-menu',
                'menu_id'        => 'footer-menu',
                'fallback_cb'    => 'coachpress_lite_footer_menu_fallback',
            ) );
        ?>
    </nav>
    <?php
}
endif;

if( ! function_exists( 'coachpress_lite_footer_menu_fallback' ) ) :
/**
 * Fallback for secondary menu
*/
function coachpress_lite_footer_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<ul id="footer-menu" class="nav-menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'coachpress-lite' ) . '</a></li>';
        echo '</ul>';
    }
}
endif;

if( ! function_exists( 'coachpress_lite_mobile_navigation' ) ) :
/**
 * Mobile Navigation
*/
function coachpress_lite_mobile_navigation(){ 
    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true );
    $ed_search = get_theme_mod( 'ed_header_search', true );

    ?>
    <div class="mobile-header">
        <div class="container">
            <?php coachpress_lite_site_branding( true ); ?>
            <button class="toggle-btn" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>
            <div class="mobile-header-popup">
                <div class="mbl-header-inner primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
                    <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"></button>
                    <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'coachpress-lite' ); ?>">
                        <div class="main-menu-modal">
                            <div class="mbl-header-top">
                                <div class="header-right">
                                    <?php if( $ed_search ) {
                                        echo '<div class="header-search">
                                                <button class="search-toggle" data-toggle-target=".mob-search-modal" data-toggle-body-class="showing-mob-search-modal" data-set-focus=".mob-search-modal .search-field" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="21.863" height="22" viewBox="0 0 21.863 22">
                                                        <path
                                                        d="M24.863,1170.255l-2.045,2.045L18,1167.482v-1.091l-.409-.409a8.674,8.674,0,0,1-5.727,2.046,8.235,8.235,0,0,1-6.273-2.591A8.993,8.993,0,0,1,3,1159.164a8.235,8.235,0,0,1,2.591-6.273,8.993,8.993,0,0,1,6.273-2.591,8.441,8.441,0,0,1,6.273,2.591,8.993,8.993,0,0,1,2.591,6.273,8.675,8.675,0,0,1-2.045,5.727l.409.409h.955ZM7.5,1163.664a5.76,5.76,0,0,0,4.364,1.773,5.969,5.969,0,0,0,4.364-1.773,6.257,6.257,0,0,0,0-8.727,5.76,5.76,0,0,0-4.364-1.773,5.969,5.969,0,0,0-4.364,1.773,5.76,5.76,0,0,0-1.773,4.364A6.308,6.308,0,0,0,7.5,1163.664Z"
                                                        transform="translate(-3 -1150.3)" fill="#806e6a" /></svg>
                                                </button>
                                                <div class="header-search-wrap mob-search-modal cover-modal" data-modal-target-string=".mob-search-modal">
                                                    <div class="header-search-inner">';
                                                        get_search_form();
                                                        echo '<button class="close" data-toggle-target=".mob-search-modal" data-toggle-body-class="showing-mob-search-modal" data-set-focus=".mob-search-modal .search-field" aria-expanded="false">' . __( 'Close', 'coachpress-lite' ) . '</button>
                                                    </div>
                                                </div>
                                            </div><!-- .header-search -->';
                                    } ?>
                                    <?php if( coachpress_lite_is_woocommerce_activated() && $ed_cart ) {
                                        echo '<div class="header-cart">';
                                        coachpress_lite_wc_cart_count();
                                        echo '</div>';
                                    } ?>
                                </div>
                            </div>
                            <div class="mbl-header-mid">
                                <?php coachpress_lite_primary_navigation( false ); ?>
                                <?php coachpress_lite_secondary_navigation( false ); ?>
                            </div>
                            <div class="mbl-header-bottom">
                                <?php coachpress_lite_header_contact( false ); ?>                                
                                <?php if( coachpress_lite_social_links( false ) ) {
                                    echo '<div class="header-social">';
                                    coachpress_lite_social_links();
                                    echo '</div>';
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php   
}
endif;

if( ! function_exists( 'coachpress_lite_page_header' ) ) :
/**
 * Page Header
*/
function coachpress_lite_page_header(){ 

    if( coachpress_lite_is_woocommerce_activated() && is_singular( 'product' ) ) return false;
    if( coachpress_lite_is_learndash_lms_activated() && ( is_singular( 'sfwd-courses' ) || is_singular( 'sfwd-lessons' ) || is_singular( 'sfwd-topic' ) || is_singular( 'sfwd-quiz' ) || is_singular( 'sfwd-certificates' ) || is_singular( 'sfwd-assignment' ) ) ) return false;
    if( coachpress_lite_is_tutor_lms_activated() && is_singular( 'courses' ) ) return false;

    global $wp_query; 
    $ed_newsletter  = get_theme_mod( 'ed_newsletter', false );
    $single_one_newsletter = get_theme_mod( 'newsletter_shortcode' );
    $blog_main_title = get_theme_mod( 'blog_main_title', __( 'Latest Articles', 'coachpress-lite' ) );
    $blog_main_content = get_theme_mod( 'blog_main_content', __( 'Read the articles to grow your business and career.', 'coachpress-lite' ) );
    
    if( is_single() ) { ?>
        <header class="page-header">
    <?php } else { 
        coachpress_lite_page_header_bg();
    } ?>
        <?php if( ! is_front_page() ) : ?>
            <div class="breadcrumb-wrapper">
                <div class="container">
                    <?php coachpress_lite_breadcrumb(); ?>
                </div>
            </div>   
        <?php endif; ?>
        <?php if( !( is_singular( 'post' ) || is_singular( 'blossom-portfolio' ) ) ) {
            echo '<div class="container">';
        }      

            if( is_home() ){
                echo '<h1 class="page-title">' . esc_html( $blog_main_title ) . '</h1>
                <div class="page-content">
                    ' . esc_html( $blog_main_content ) . '
                </div>';
            }   

            if( is_archive() ){ 
                if( is_author() ){
                    $author_title = get_the_author();
                    $author_description = get_the_author_meta( 'description' ); ?>
                    <div class="author-section">
                        <div class="author-img-title-wrap">
                            <figure class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 170 ); ?></figure>
                            <div class="author-title-wrap">
                                <?php 
                                    echo '<h1 class="author-name">' . esc_html( $author_title ) . '</h1>';
                                ?>      
                            </div>
                        </div>
                        <?php echo '<div class="author-content">' . wp_kses_post( wpautop( $author_description ) ) . '</div>'; ?>
                    </div>
                    <?php 
                }
                else{
                    the_archive_title();
                    the_archive_description( '<div class="archive-description">', '</div>' );
                }             
            }

            if( is_page() ){
                the_title( '<h1 class="entry-title">', '</h1>' );
            }
            
            if( is_search() ){ 
                echo '<span class="sub-title">' . esc_html__( 'Search For:', 'coachpress-lite' ) . '</span>';
                get_search_form();
            }
            
            if( is_singular( 'post' ) && $ed_newsletter && $single_one_newsletter ) {
                echo do_shortcode( $single_one_newsletter ); 
            }

            if( is_archive() || is_search() ) printf( esc_html__( '%1$s %2$s Results %3$s', 'coachpress-lite' ), '<span class="result-count">', esc_html( number_format_i18n( $wp_query->found_posts ) ), '</span>' ); 

        if( !( is_singular( 'post' ) || is_singular( 'blossom-portfolio' ) ) ) {
            echo '</div>';
        } ?>
    </header>
<?php
}
endif;

if( ! function_exists( 'coachpress_lite_breadcrumb' ) ) :
/**
 * Breadcrumbs
*/
function coachpress_lite_breadcrumb(){ 
    global $post;
    $post_page  = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front = get_option( 'show_on_front' ); //What to show on the front page    
    $home       = get_theme_mod( 'home_text', __( 'Home', 'coachpress-lite' ) ); // text for the 'Home' link
    $delimiter  = '<span class="separator"><span class="separator"><svg xmlns="http://www.w3.org/2000/svg" width="4.971" height="8.528" viewBox="0 0 4.971 8.528"><path d="M183.152,7972.6l3.557,3.557-3.557,3.558" transform="translate(-182.445 -7971.891)" fill="none" stroke="#7d6a91" stroke-linecap="round" stroke-width="1" opacity="0.56"/></svg></span>';
    $before     = '<span class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'; // tag before the current crumb
    $after      = '</span>'; // tag after the current crumb
    
    if( get_theme_mod( 'ed_breadcrumb', true ) ){
        $depth = 1;
        echo '<div id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="' . esc_url( home_url() ) . '" itemprop="item"><span itemprop="name">' . esc_html( $home ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
        
        if( is_home() ){ 
            $depth = 2;                       
            echo $before . '<a itemprop="item" href="'. esc_url( get_the_permalink() ) .'"><span itemprop="name">' . esc_html( single_post_title( '', false ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;            
        }elseif( is_category() ){  
            $depth = 2;          
            $thisCat = get_category( get_query_var( 'cat' ), false );            
            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                $depth++;  
            }            
            if( $thisCat->parent != 0 ){
                $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                $parent_categories = explode( ',', $parent_categories );
                foreach( $parent_categories as $parent_term ){
                    $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                    if( is_object( $parent_obj ) ){
                        $term_url  = get_term_link( $parent_obj->term_id );
                        $term_name = $parent_obj->name;
                        echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                        $depth++;
                    }
                }
            }
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $thisCat->term_id) ) . '"><span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;       
        }elseif( coachpress_lite_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
            $depth = 2;
            $current_term = $GLOBALS['wp_query']->get_queried_object();            
            if( wc_get_page_id( 'shop' ) ){ //Displaying Shop link in woocommerce archive page
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                $depth++;
            }
            if( is_product_category() ){
                $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, 'product_cat' );    
                    if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                        echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                        $depth++;
                    }
                }
            }
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $current_term->term_id ) ) . '"><span itemprop="name">' . esc_html( $current_term->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;
        }elseif( coachpress_lite_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
            $depth = 2;
            if( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ){
                return;
            }
            $_name    = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
            $shop_url = ( wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0 )  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
            if( ! $_name ){
                $product_post_type = get_post_type_object( 'product' );
                $_name             = $product_post_type->labels->singular_name;
            }
            echo $before . '<a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
        }elseif( is_tag() ){ 
            $depth          = 2;
            $queried_object = get_queried_object();
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( single_tag_title( '', false ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ). '" />'. $after;
        }elseif( is_author() ){  
            global $author;
            $depth    = 2;
            $userdata = get_userdata( $author );
            echo $before . '<a itemprop="item" href="' . esc_url( get_author_posts_url( $author ) ) . '"><span itemprop="name">' . esc_html( $userdata->display_name ) .'</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;     
        }elseif( is_search() ){ 
            $depth       = 2;
            $request_uri = $_SERVER['REQUEST_URI'];
            echo $before . '<a itemprop="item" href="'. esc_url( $request_uri ) . '"><span itemprop="name">' . sprintf( __( 'Search Results for "%s"', 'coachpress-lite' ), esc_html( get_search_query() ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;        
        }elseif( is_day() ){            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
            $depth++;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'coachpress-lite' ) ), get_the_time( __( 'm', 'coachpress-lite' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'coachpress-lite' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
            $depth++;
            echo $before . '<a itemprop="item" href="' . esc_url( get_day_link( get_the_time( __( 'Y', 'coachpress-lite' ) ), get_the_time( __( 'm', 'coachpress-lite' ) ), get_the_time( __( 'd', 'coachpress-lite' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'd', 'coachpress-lite' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;        
        }elseif( is_month() ){            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
            $depth++;
            echo $before . '<a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'coachpress-lite' ) ), get_the_time( __( 'm', 'coachpress-lite' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'coachpress-lite' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;        
        }elseif( is_year() ){ 
            $depth = 2;
            echo $before .'<a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'coachpress-lite' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;  
        }elseif( is_single() && !is_attachment() ){   
            $depth = 2;         
            if( coachpress_lite_is_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
                if( wc_get_page_id( 'shop' ) ){ //Displaying Shop link in woocommerce archive page
                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                    if ( ! $_name ) {
                        $product_post_type = get_post_type_object( 'product' );
                        $_name = $product_post_type->labels->singular_name;
                    }
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                    $depth++;                    
                }           
                if( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ){
                    $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                    $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                            $depth++;
                        }
                    }
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                    $depth++;
                }
                echo $before . '<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;
            }elseif( get_post_type() != 'post' ){                
                $post_type = get_post_type_object( get_post_type() );                
                if( $post_type->has_archive == true ){// For CPT Archive Link                   
                   // Add support for a non-standard label of 'archive_title' (special use case).
                   $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                   echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
                   $depth++;    
                }
                echo $before . '<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
            }else{ //For Post                
                $cat_object       = get_the_category();
                $potential_parent = 0;
                
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';  
                    $depth++; 
                }
                
                if( $cat_object ){ //Getting category hierarchy if any        
                    //Now try to find the deepest term of those that we know of
                    $use_term = key( $cat_object );
                    foreach( $cat_object as $key => $object ){
                        //Can't use the next($cat_object) trick since order is unknown
                        if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                            $use_term         = $key;
                            $potential_parent = $object->term_id;
                        }
                    }                    
                    $cat  = $cat_object[$use_term];              
                    $cats = get_category_parents( $cat, false, ',' );
                    $cats = explode( ',', $cats );
                    foreach ( $cats as $cat ) {
                        $cat_obj = get_term_by( 'name', $cat, 'category' );
                        if( is_object( $cat_obj ) ){
                            $term_url  = get_term_link( $cat_obj->term_id );
                            $term_name = $cat_obj->name;
                            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ). '" />' . $delimiter . '</span>';
                            $depth++;
                        }
                    }
                }
                echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;   
            }        
        }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ){ //For Custom Post Archive
            $depth     = 2;
            $post_type = get_post_type_object( get_post_type() );
            if( get_query_var('paged') ){
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '/</span>';
                echo $before . sprintf( __('Page %s', 'coachpress-lite'), get_query_var('paged') ) . $after; //@todo need to check this
            }else{
                echo $before . '<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ). '" />' . $after;
            }    
        }elseif( is_attachment() ){ 
            $depth = 2;           
            echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
        }elseif( is_page() && !$post->post_parent ){            
            $depth = 2;
            echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
        }elseif( is_page() && $post->post_parent ){            
            $depth       = 2;
            $parent_id   = $post->post_parent;
            $breadcrumbs = array();
            while( $parent_id ){
                $current_page  = get_post( $parent_id );
                $breadcrumbs[] = $current_page->ID;
                $parent_id     = $current_page->post_parent;
            }
            $breadcrumbs = array_reverse( $breadcrumbs );
            for ( $i = 0; $i < count( $breadcrumbs) ; $i++ ){
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $breadcrumbs[$i] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[$i] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $delimiter . '</span>';
                $depth++;
            }
            echo $before . '<a href="' . get_permalink() . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" /></span>' . $after;
        }elseif( is_404() ){
            $depth = 2;
            echo $before . '<a itemprop="item" href="' . esc_url( home_url() ) . '"><span itemprop="name">' . esc_html__( '404 Error - Page Not Found', 'coachpress-lite' ) . '</span></a><meta itemprop="position" content="' . absint( $depth ). '" />' . $after;
        }
        
        if( get_query_var('paged') ) printf( __( ' (Page %s)', 'coachpress-lite' ), get_query_var('paged') );
        
        echo '</div><!-- .crumbs -->';
        
    }                
}
endif;

if( ! function_exists( 'coachpress_lite_theme_comment' ) ) :
/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function coachpress_lite_theme_comment( $comment, $args, $depth ){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
    <?php if ( 'div' != $args['style'] ) : ?>
    <article id="div-comment-<?php comment_ID() ?>" class="comment-body" itemscope itemtype="https://schema.org/UserComments">
	<?php endif; ?>
    	
        <footer class="comment-meta">
            <div class="comment-author vcard">
        	   <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
               <?php printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person">%s <span class="says">says:</span></b>', 'coachpress-lite' ), get_comment_author_link() ); ?>
        	</div><!-- .comment-author vcard -->
            <div class="comment-metadata commentmetadata">
                <?php esc_html_e( 'Posted on', 'coachpress-lite' );?>
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                    <time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'coachpress-lite' ), get_comment_date(),  get_comment_time() ); ?></time>
                </a>
            </div>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'coachpress-lite' ); ?></em>
                <br />
            <?php endif; ?>
        </footer>
        <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>
        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>        
	<?php if ( 'div' != $args['style'] ) : ?>
    </article><!-- .comment-body -->
	<?php endif;
}
endif;

if( ! function_exists( 'coachpress_lite_sidebar' ) ) :
/**
 * Return sidebar layouts for pages/posts
*/
function coachpress_lite_sidebar( $class = false ){
    global $post;

    if( ( is_front_page() && ! is_home() ) ) return false;

    $return = false;
    $page_layout = get_theme_mod( 'page_sidebar_layout', 'no-sidebar' ); //Default Layout Style for Pages
    $post_layout = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Posts
    $layout      = get_theme_mod( 'layout_style', 'right-sidebar' ); //Default Layout Style for Styling Settings
    

    if( is_singular( array( 'page', 'post' ) ) ){         
        if( get_post_meta( $post->ID, '_coachpress_lite_sidebar_layout', true ) ){
            $sidebar_layout = get_post_meta( $post->ID, '_coachpress_lite_sidebar_layout', true );
        }else{
            $sidebar_layout = 'default-sidebar';
        }
        
        if( is_page() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ) ){
                    $return = $class ? 'fullwidth-centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }elseif( is_single() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ){
                    $return = $class ? 'fullwidth-centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }
    }elseif( coachpress_lite_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || get_post_type() == 'product' ) ){
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'shop-sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'shop-sidebar';    
            }           
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }elseif( is_404() ){
        $return = $class ? 'full-width' : false;
    }else{
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( $layout == 'centered' ){
            $return = $class ? 'fullwidth-centered' : false;
        }elseif( is_active_sidebar( 'sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'sidebar';    
            }                         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }    
    return $return; 
}
endif;

if( ! function_exists( 'coachpress_lite_get_posts' ) ) :
/**
 * Fuction to list Custom Post Type
*/
function coachpress_lite_get_posts( $post_type = 'post', $slug = false ){    
    $args = array(
    	'posts_per_page'   => -1,
    	'post_type'        => $post_type,
    	'post_status'      => 'publish',
    	'suppress_filters' => true 
    );
    $posts_array = get_posts( $args );
    
    // Initate an empty array
    $post_options = array();
    $post_options[''] = __( ' -- Choose -- ', 'coachpress-lite' );
    if ( ! empty( $posts_array ) ) {
        foreach ( $posts_array as $posts ) {
            if( $slug ){
                $post_options[ $posts->post_title ] = $posts->post_title;
            }else{
                $post_options[ $posts->ID ] = $posts->post_title;    
            }
        }
    }
    return $post_options;
    wp_reset_postdata();
}
endif;

if( ! function_exists( 'coachpress_lite_get_categories' ) ) :
/**
 * Function to list post categories in customizer options
*/
function coachpress_lite_get_categories( $select = true, $taxonomy = 'category', $slug = false ){    
    /* Option list of all categories */
    $categories = array();
    
    $args = array( 
        'hide_empty' => false,
        'taxonomy'   => $taxonomy 
    );
    
    $catlists = get_terms( $args );
    if( $select ) $categories[''] = __( 'Choose Category', 'coachpress-lite' );
    foreach( $catlists as $category ){
        if( $slug ){
            $categories[$category->slug] = $category->name;
        }else{
            $categories[$category->term_id] = $category->name;    
        }        
    }
    
    return $categories;
}
endif;

if( ! function_exists( 'coachpress_lite_get_home_sections' ) ) :
/**
 * Returns Home Sections 
*/
function coachpress_lite_get_home_sections(){
    
    $ed_banner = get_theme_mod( 'ed_banner_section', 'static_banner' );
    $sections = array( 
        'newsletter'    => array( 'sidebar' => 'newsletter' ),
        'promo'         => array( 'sidebar' => 'promo' ),
        'about'         => array( 'sidebar' => 'about' ),
        'service'       => array( 'sidebar' => 'service' ),
        'wheeloflife'   => array( 'section' => 'wheeloflife'),
        'testimonial'   => array( 'sidebar' => 'testimonial' ),
        'client'        => array( 'sidebar' => 'client' ),
        'cta'           => array( 'sidebar' => 'cta' ),
        'blog'          => array( 'section' => 'blog' )
    );
    
    $enabled_section = array();
    
    if( $ed_banner == 'static_banner' || $ed_banner == 'slider_banner' || $ed_banner == 'static_nl_banner' || $ed_banner == 'static_ap_banner' ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', false ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    return apply_filters( 'coachpress_lite_home_sections', $enabled_section );
}
endif;

if( ! function_exists( 'coachpress_lite_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function coachpress_lite_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if ( ! function_exists( 'coachpress_lite_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function coachpress_lite_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = coachpress_lite_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;

if ( ! function_exists( 'coachpress_lite_comment_toggle' ) ):
/**
 * Function toggle comment section position
*/
function coachpress_lite_comment_toggle(){
    $comment_postion = get_theme_mod( 'toggle_comments', false );

    if ( $comment_postion ) {
        $priority = 5;
    }else{
        $priority = 45;
    }
    return absint( $priority ) ;
}
endif;

/**
 * Is Blossom Theme Toolkit active or not
*/
function coachpress_lite_is_bttk_activated(){
    return class_exists( 'Blossomthemes_Toolkit' ) ? true : false;
}

/**
 * Is BlossomThemes Email Newsletters active or not
*/
function coachpress_lite_is_btnw_activated(){
    return class_exists( 'Blossomthemes_Email_Newsletter' ) ? true : false;        
}

/**
 * Query WooCommerce activation
 */
function coachpress_lite_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Check if Contact Form 7 Plugin is installed
*/
function coachpress_lite_is_cf7_activated(){
    return class_exists( 'WPCF7' ) ? true : false;
}

/**
 * Query Jetpack activation
*/
function coachpress_lite_is_jetpack_activated( $gallery = false ){
	if( $gallery ){
        return ( class_exists( 'jetpack' ) && Jetpack::is_module_active( 'tiled-gallery' ) ) ? true : false;
	}else{
        return class_exists( 'jetpack' ) ? true : false;
    }           
}

/**
 * Checks if elementor is active or not
*/
function coachpress_lite_is_elementor_activated(){
    return class_exists( 'Elementor\\Plugin' ) ? true : false; 
}

/**
 * Checks if elementor has override that particular page/post or not
*/
function coachpress_lite_is_elementor_activated_post(){
    if( coachpress_lite_is_elementor_activated() ){
        global $post;
        if( $post ){
            $post_id = $post->ID;
            return \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() ? true : false;
        }
    }else{
        return false;
    }
}

/**
 * Checks if classic editor is active or not
*/
function coachpress_lite_is_classic_editor_activated(){
    return class_exists( 'Classic_Editor' ) ? true : false; 
}

/**
 * Checks if LearnDash LMS is active or not
*/
function coachpress_lite_is_learndash_lms_activated(){
    return class_exists( 'SFWD_LMS' ) ? true : false; 
}

/**
 * Checks if Tutor LMS is active or not
*/
function coachpress_lite_is_tutor_lms_activated(){
    return class_exists( '\TUTOR\Tutor' ) ? true : false; 
}

/**
 * Is Wheel of life activated
 */
function coachpress_lite_is_wheel_of_life_activated() {
    return class_exists( 'WheelOfLife\Wheel_Of_Life' ) ? true : false;
}



if ( ! function_exists( 'coachpress_lite_blog_image_size' ) ) :
/**
 * Blog Layout Image Size
*/
function coachpress_lite_blog_image_size() {

    $blog_layout  = get_theme_mod( 'blog_page_layout', 'classic' );
    $sidebar      = coachpress_lite_sidebar( true );

    if( $blog_layout == 'classic') { 
        $image_size = ( $sidebar == 'full-width' ) ? 'coachpress-lite-classic-full' : 'coachpress-lite-classic';    
    }elseif( $blog_layout == 'grid' ){ 
        $image_size = ( $sidebar == 'full-width' ) ? 'coachpress-lite-grid-full' : 'coachpress-lite-grid';        
    }else{
        $image_size = ( $sidebar == 'full-width' ) ? 'coachpress-lite-classic-full' : 'coachpress-lite-classic';    
    }  

    return $image_size;
}
endif;

if( ! function_exists( 'coachpress_lite_single_entry_header' ) ) :
/**
 * Entry Header
*/
function coachpress_lite_single_entry_header(){     
    ?>
    <header class="entry-header">
        <?php 
            coachpress_lite_category();
            the_title( '<h1 class="entry-title">', '</h1>' );

            if( 'post' === get_post_type() ){
                echo '<div class="entry-meta">';
                coachpress_lite_posted_on();                 
                echo '</div>';
            }
        ?>
    </header>         
    <?php
}
endif;

if( ! function_exists( 'coachpress_lite_single_article_meta' ) ) :
/**
 * Entry Header
*/
function coachpress_lite_single_article_meta(){ ?>
    <div class="article-meta">
        <div class="article-meta-inner">
        <?php 
            if( is_singular( 'post' ) ) coachpress_lite_posted_by();
            if( is_singular( 'post' ) ) coachpress_lite_comment_count();
        ?>
        </div>         
    </div>         
    <?php    
}
endif;

if ( ! function_exists( 'coachpress_lite_page_header_bg' ) ) :
/**
 * Blog Layout Image Size
*/
function coachpress_lite_page_header_bg() {

    if( is_home() || is_archive() || is_search() || is_page_template( 'templates/blossom-portfolio.php' ) ){
        $background_image = get_theme_mod( 'blog_background_image', esc_url( get_template_directory_uri() .'/images/page-header-bg.jpg' ) );        
    }
    
    if( is_page() ){
        $background_image = get_the_post_thumbnail_url( get_the_id(), 'full' );;
    } ?>

    <header class="page-header has-bg-img" style="background-image: url( '<?php echo esc_url( $background_image ); ?>' );">
<?php
}
endif;