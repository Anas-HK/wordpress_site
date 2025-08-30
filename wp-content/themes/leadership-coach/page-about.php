<?php
/**
 * Template Name: About Me Page
 * 
 * The template for displaying the About Me page with custom fields
 * and WordPress native editing compatibility.
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
                        <?php the_post_thumbnail( 'large', array( 'class' => 'about-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    
                    <!-- Main Content Section -->
                    <div class="about-main-content">
                        <?php
                        the_content();
                        
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leadership-coach' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <!-- Coach Credentials Section -->
                    <?php 
                    $credentials = get_post_meta( get_the_ID(), '_coach_credentials', true );
                    if ( ! empty( $credentials ) ) : ?>
                        <div class="coach-credentials">
                            <h2><?php esc_html_e( 'Credentials & Certifications', 'leadership-coach' ); ?></h2>
                            <div class="credentials-content">
                                <?php echo wp_kses_post( wpautop( $credentials ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Experience Section -->
                    <?php 
                    $experience = get_post_meta( get_the_ID(), '_coach_experience', true );
                    if ( ! empty( $experience ) ) : ?>
                        <div class="coach-experience">
                            <h2><?php esc_html_e( 'Professional Experience', 'leadership-coach' ); ?></h2>
                            <div class="experience-content">
                                <?php echo wp_kses_post( wpautop( $experience ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Specializations Section -->
                    <?php 
                    $specializations = get_post_meta( get_the_ID(), '_coach_specializations', true );
                    if ( ! empty( $specializations ) ) : ?>
                        <div class="coach-specializations">
                            <h2><?php esc_html_e( 'Areas of Specialization', 'leadership-coach' ); ?></h2>
                            <div class="specializations-content">
                                <?php echo wp_kses_post( wpautop( $specializations ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Personal Philosophy Section -->
                    <?php 
                    $philosophy = get_post_meta( get_the_ID(), '_coach_philosophy', true );
                    if ( ! empty( $philosophy ) ) : ?>
                        <div class="coach-philosophy">
                            <h2><?php esc_html_e( 'Coaching Philosophy', 'leadership-coach' ); ?></h2>
                            <div class="philosophy-content">
                                <?php echo wp_kses_post( wpautop( $philosophy ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Call to Action Section -->
                    <div class="about-cta">
                        <h2><?php esc_html_e( 'Ready to Start Your Leadership Journey?', 'leadership-coach' ); ?></h2>
                        <p><?php esc_html_e( 'Let\'s work together to unlock your leadership potential and achieve your goals.', 'leadership-coach' ); ?></p>
                        <div class="cta-buttons">
                            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-primary">
                                <?php esc_html_e( 'Get in Touch', 'leadership-coach' ); ?>
                            </a>
                            <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>" class="btn-secondary">
                                <?php esc_html_e( 'Book Discovery Call', 'leadership-coach' ); ?>
                            </a>
                        </div>
                    </div>

                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();