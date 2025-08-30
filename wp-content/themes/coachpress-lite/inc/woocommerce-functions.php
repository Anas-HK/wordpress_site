<?php
/**
 * CoachPress Lite Woocommerce hooks and functions.
 *
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 *
 * @package CoachPress_Lite
 */

/**
 * Woocommerce related hooks
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );

/**
 * Declare Woocommerce Support
*/
function coachpress_lite_woocommerce_support() {
    global $woocommerce;
    
    add_theme_support( 'woocommerce' );
    
    if( version_compare( $woocommerce->version, '3.0', ">=" ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'coachpress_lite_woocommerce_support');

/**
 * Woocommerce Sidebar
*/
function coachpress_lite_wc_widgets_init(){
    register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'coachpress-lite' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Sidebar displaying only in woocommerce pages.', 'coachpress-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );    
}
add_action( 'widgets_init', 'coachpress_lite_wc_widgets_init' );

/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
*/
function coachpress_lite_wc_wrapper(){    
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}
add_action( 'woocommerce_before_main_content', 'coachpress_lite_wc_wrapper' );

/**
 * After Content
 * Closes the wrapping divs
*/
function coachpress_lite_wc_wrapper_end(){
    ?>
        </main>
    </div>
    <?php
    do_action( 'coachpress_lite_wo_sidebar' );
}
add_action( 'woocommerce_after_main_content', 'coachpress_lite_wc_wrapper_end' );

/**
 * Callback function for Shop sidebar
*/
function coachpress_lite_wc_sidebar_cb(){
    if( is_active_sidebar( 'shop-sidebar' ) ){
        echo '<aside id="secondary" class="widget-area" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">';
        dynamic_sidebar( 'shop-sidebar' );
        echo '</aside>'; 
    }
}
add_action( 'coachpress_lite_wo_sidebar', 'coachpress_lite_wc_sidebar_cb' );

/**
 * Removes the "shop" title on the main shop page
*/
add_filter( 'woocommerce_show_page_title' , '__return_false' );

if( ! function_exists( 'coachpress_lite_wc_cart_count' ) ) :
/**
 * Woocommerce Cart Count
 * 
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header 
*/
function coachpress_lite_wc_cart_count(){
    $cart_page = get_option( 'woocommerce_cart_page_id' );
    $count = WC()->cart->cart_contents_count; 
    if( $cart_page ){ ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'coachpress-lite' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path id="Path_4343" data-name="Path 4343" d="M5.1,13.6a1.7,1.7,0,1,0,1.7,1.7A1.7,1.7,0,0,0,5.1,13.6ZM0,0V1.7H1.7L4.76,8.16,3.57,10.2a3.02,3.02,0,0,0-.17.85,1.7,1.7,0,0,0,1.7,1.7H15.3v-1.7H5.44a.183.183,0,0,1-.17-.17v-.085L6.035,9.35h6.29A1.55,1.55,0,0,0,13.77,8.5l3.06-5.525A.468.468,0,0,0,17,2.55a.8.8,0,0,0-.85-.85H3.57L2.8,0ZM13.6,13.6a1.7,1.7,0,1,0,1.7,1.7A1.7,1.7,0,0,0,13.6,13.6Z" fill="#806e6a" /></svg>
        <span class="cart-count"><?php echo absint( $count ); ?></span>
    </a>
    <?php
    }
}
endif;

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 * 
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header
 */
function coachpress_lite_add_to_cart_fragment( $fragments ){
    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'coachpress-lite' ); ?>">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count"><?php echo absint( $count ); ?></span>
    </a>
    <?php
 
    $fragments['a.cart'] = ob_get_clean();
     
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'coachpress_lite_add_to_cart_fragment' );

/**
 * Ajax Callback for adding product in cart
 * 
*/
function coachpress_lite_add_cart_ajax() {
	global $woocommerce;
    
    $product_id = $_POST['product_id'];

	WC()->cart->add_to_cart( $product_id, 1 );
	$count = WC()->cart->cart_contents_count;
	$cart_url = $woocommerce->cart->get_cart_url(); 
    
    ?>
    <a href="<?php echo esc_url( $cart_url ); ?>" rel="bookmark" class="btn-add-to-cart"><?php esc_html_e( 'View Cart', 'coachpress-lite' ); ?></a>
    <input type="hidden" id="<?php echo esc_attr( 'cart-' . $product_id ); ?>" value="<?php echo esc_attr( $count ); ?>" />
    <?php 
    die();
}
add_action( 'wp_ajax_coachpress_lite_add_cart_single', 'coachpress_lite_add_cart_ajax' );
add_action( 'wp_ajax_nopriv_coachpress_lite_add_cart_single', 'coachpress_lite_add_cart_ajax' );