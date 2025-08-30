<?php
/**
 * Template Name: Services Page
 * 
 * The template for displaying the Services page with dynamic content
 * from Services custom post type and WordPress native editing compatibility.
 *
 * @package Leadership Coach
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'services-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    
                    <!-- Main Content Section -->
                    <div class="services-intro">
                        <?php
                        the_content();
                        
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leadership-coach' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <!-- Services Display Section -->
                    <div class="services-grid">
                        <?php
                        // Query for Services custom post type
                        $services_query = new WP_Query( array(
                            'post_type'      => 'coaching_service',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'menu_order',
                            'order'          => 'ASC'
                        ) );

                        if ( $services_query->have_posts() ) :
                            while ( $services_query->have_posts() ) : $services_query->the_post();
                                
                                // Get custom fields
                                $price = get_post_meta( get_the_ID(), '_service_price', true );
                                $duration = get_post_meta( get_the_ID(), '_service_duration', true );
                                $booking_type = get_post_meta( get_the_ID(), '_service_booking_type', true );
                                $is_bookable = get_post_meta( get_the_ID(), '_service_is_bookable', true );
                                ?>
                                
                                <div class="service-card">
                                    
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="service-image">
                                            <?php the_post_thumbnail( 'medium', array( 'class' => 'service-thumbnail' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="service-content">
                                        <h3 class="service-title"><?php the_title(); ?></h3>
                                        
                                        <div class="service-meta">
                                            <?php if ( ! empty( $duration ) ) : ?>
                                                <span class="service-duration">
                                                    <i class="fas fa-clock"></i>
                                                    <?php echo esc_html( $duration ); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $price ) && $booking_type !== 'free' ) : ?>
                                                <span class="service-price">
                                                    <i class="fas fa-tag"></i>
                                                    <?php echo esc_html( $price ); ?>
                                                </span>
                                            <?php elseif ( $booking_type === 'free' ) : ?>
                                                <span class="service-price free">
                                                    <i class="fas fa-gift"></i>
                                                    <?php esc_html_e( 'Free', 'leadership-coach' ); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="service-description">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <div class="service-actions">
                                            <?php if ( $is_bookable && $booking_type === 'free' ) : ?>
                                                <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>?service=<?php echo get_the_ID(); ?>" class="btn-primary service-book-btn">
                                                    <?php esc_html_e( 'Book Free Session', 'leadership-coach' ); ?>
                                                </a>
                                            <?php elseif ( $is_bookable && $booking_type === 'paid' ) : ?>
                                                <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>?service=<?php echo get_the_ID(); ?>" class="btn-primary service-book-btn">
                                                    <?php esc_html_e( 'Book Now', 'leadership-coach' ); ?>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="<?php the_permalink(); ?>" class="btn-secondary service-learn-btn">
                                                <?php esc_html_e( 'Learn More', 'leadership-coach' ); ?>
                                            </a>
                                        </div>
                                    </div>
                                    
                                </div><!-- .service-card -->
                                
                            <?php endwhile;
                            wp_reset_postdata();
                        else : ?>
                            
                            <div class="no-services">
                                <h3><?php esc_html_e( 'Services Coming Soon', 'leadership-coach' ); ?></h3>
                                <p><?php esc_html_e( 'We are currently preparing our coaching services. Please check back soon or contact us for more information.', 'leadership-coach' ); ?></p>
                                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-primary">
                                    <?php esc_html_e( 'Contact Us', 'leadership-coach' ); ?>
                                </a>
                            </div>
                            
                        <?php endif; ?>
                    </div><!-- .services-grid -->

                    <!-- Services CTA Section -->
                    <div class="services-cta">
                        <div class="cta-content">
                            <h2><?php esc_html_e( 'Not Sure Which Service is Right for You?', 'leadership-coach' ); ?></h2>
                            <p><?php esc_html_e( 'Schedule a free discovery call to discuss your goals and find the perfect coaching solution for your needs.', 'leadership-coach' ); ?></p>
                            <div class="cta-buttons">
                                <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>" class="btn-primary">
                                    <?php esc_html_e( 'Book Discovery Call', 'leadership-coach' ); ?>
                                </a>
                                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-secondary">
                                    <?php esc_html_e( 'Ask Questions', 'leadership-coach' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonials Section -->
                    <?php
                    $testimonials_query = new WP_Query( array(
                        'post_type'      => 'testimonial',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'orderby'        => 'rand'
                    ) );

                    if ( $testimonials_query->have_posts() ) : ?>
                        <div class="services-testimonials">
                            <h2><?php esc_html_e( 'What Our Clients Say', 'leadership-coach' ); ?></h2>
                            <div class="testimonials-grid">
                                <?php while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
                                    $client_name = get_post_meta( get_the_ID(), '_testimonial_client_name', true );
                                    $client_rating = get_post_meta( get_the_ID(), '_testimonial_rating', true );
                                    ?>
                                    <div class="testimonial-card">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <div class="testimonial-image">
                                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'client-photo' ) ); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="testimonial-content">
                                            <?php if ( ! empty( $client_rating ) ) : ?>
                                                <div class="testimonial-rating">
                                                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                                        <span class="star <?php echo ( $i <= $client_rating ) ? 'filled' : ''; ?>">★</span>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <blockquote>
                                                <?php the_content(); ?>
                                            </blockquote>
                                            
                                            <?php if ( ! empty( $client_name ) ) : ?>
                                                <cite class="client-name"><?php echo esc_html( $client_name ); ?></cite>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();