<?php
/**
 * Template Name: Contact Page
 * 
 * The template for displaying the Contact page with contact form integration
 * and WordPress native editing compatibility.
 *
 * @package Leadership Coach
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'contact-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    
                    <!-- Main Content Section -->
                    <div class="contact-intro">
                        <?php
                        the_content();
                        
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leadership-coach' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <div class="contact-content-wrapper">
                        
                        <!-- Contact Information Section -->
                        <div class="contact-info">
                            <h2><?php esc_html_e( 'Get in Touch', 'leadership-coach' ); ?></h2>
                            
                            <div class="contact-details">
                                <?php
                                $phone = get_theme_mod( 'phone' );
                                $email = get_theme_mod( 'email' );
                                $address = get_theme_mod( 'address' );
                                ?>
                                
                                <?php if ( ! empty( $phone ) ) : ?>
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <div class="contact-item-content">
                                            <h3><?php esc_html_e( 'Phone', 'leadership-coach' ); ?></h3>
                                            <a href="tel:<?php echo preg_replace( '/[^\d+]/', '', $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $email ) ) : ?>
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <div class="contact-item-content">
                                            <h3><?php esc_html_e( 'Email', 'leadership-coach' ); ?></h3>
                                            <a href="mailto:<?php echo sanitize_email( $email ); ?>"><?php echo sanitize_email( $email ); ?></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $address ) ) : ?>
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="contact-item-content">
                                            <h3><?php esc_html_e( 'Address', 'leadership-coach' ); ?></h3>
                                            <p><?php echo esc_html( $address ); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Business Hours -->
                            <div class="business-hours">
                                <h3><?php esc_html_e( 'Business Hours', 'leadership-coach' ); ?></h3>
                                <div class="hours-list">
                                    <div class="hours-item">
                                        <span class="day"><?php esc_html_e( 'Monday - Friday', 'leadership-coach' ); ?></span>
                                        <span class="time"><?php esc_html_e( '9:00 AM - 6:00 PM', 'leadership-coach' ); ?></span>
                                    </div>
                                    <div class="hours-item">
                                        <span class="day"><?php esc_html_e( 'Saturday', 'leadership-coach' ); ?></span>
                                        <span class="time"><?php esc_html_e( '10:00 AM - 4:00 PM', 'leadership-coach' ); ?></span>
                                    </div>
                                    <div class="hours-item">
                                        <span class="day"><?php esc_html_e( 'Sunday', 'leadership-coach' ); ?></span>
                                        <span class="time"><?php esc_html_e( 'Closed', 'leadership-coach' ); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form Section -->
                        <div class="contact-form-section">
                            <h2><?php esc_html_e( 'Send a Message', 'leadership-coach' ); ?></h2>
                            
                            <?php
                            // Display success/error messages
                            if ( isset( $_GET['contact'] ) ) {
                                if ( $_GET['contact'] === 'success' ) {
                                    echo '<div class="contact-message success">';
                                    echo '<p>' . esc_html__( 'Thank you for your message! We will get back to you soon.', 'leadership-coach' ) . '</p>';
                                    echo '</div>';
                                } elseif ( $_GET['contact'] === 'error' ) {
                                    echo '<div class="contact-message error">';
                                    echo '<p>' . esc_html__( 'Sorry, there was an error sending your message. Please try again.', 'leadership-coach' ) . '</p>';
                                    echo '</div>';
                                }
                            }
                            ?>
                            
                            <form id="contact-form" class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                <?php wp_nonce_field( 'leadership_coach_contact_form', 'contact_nonce' ); ?>
                                <input type="hidden" name="action" value="leadership_coach_contact_form">
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contact_name"><?php esc_html_e( 'Full Name', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                        <input type="text" id="contact_name" name="contact_name" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact_email"><?php esc_html_e( 'Email Address', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                        <input type="email" id="contact_email" name="contact_email" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contact_phone"><?php esc_html_e( 'Phone Number', 'leadership-coach' ); ?></label>
                                        <input type="tel" id="contact_phone" name="contact_phone">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact_subject"><?php esc_html_e( 'Subject', 'leadership-coach' ); ?></label>
                                        <select id="contact_subject" name="contact_subject">
                                            <option value=""><?php esc_html_e( 'Select a subject', 'leadership-coach' ); ?></option>
                                            <option value="general"><?php esc_html_e( 'General Inquiry', 'leadership-coach' ); ?></option>
                                            <option value="coaching"><?php esc_html_e( 'Coaching Services', 'leadership-coach' ); ?></option>
                                            <option value="booking"><?php esc_html_e( 'Appointment Booking', 'leadership-coach' ); ?></option>
                                            <option value="other"><?php esc_html_e( 'Other', 'leadership-coach' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_message"><?php esc_html_e( 'Message', 'leadership-coach' ); ?> <span class="required">*</span></label>
                                    <textarea id="contact_message" name="contact_message" rows="6" required placeholder="<?php esc_attr_e( 'Tell us about your goals and how we can help you...', 'leadership-coach' ); ?>"></textarea>
                                </div>
                                
                                <!-- Honeypot field for spam protection -->
                                <div class="honeypot" style="display: none;">
                                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn-primary contact-submit">
                                        <?php esc_html_e( 'Send Message', 'leadership-coach' ); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    </div><!-- .contact-content-wrapper -->

                    <!-- Map Integration Placeholder -->
                    <div class="contact-map-placeholder">
                        <h2><?php esc_html_e( 'Find Us', 'leadership-coach' ); ?></h2>
                        <div class="map-container">
                            <div class="map-placeholder">
                                <i class="fas fa-map-marked-alt"></i>
                                <p><?php esc_html_e( 'Interactive map will be integrated here in future updates.', 'leadership-coach' ); ?></p>
                                <p><?php esc_html_e( 'For now, please use the address above for directions.', 'leadership-coach' ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="contact-quick-actions">
                        <h2><?php esc_html_e( 'Ready to Get Started?', 'leadership-coach' ); ?></h2>
                        <p><?php esc_html_e( 'Take the first step towards your leadership transformation today.', 'leadership-coach' ); ?></p>
                        <div class="quick-action-buttons">
                            <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>" class="btn-primary">
                                <?php esc_html_e( 'Book Free Discovery Call', 'leadership-coach' ); ?>
                            </a>
                            <a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="btn-secondary">
                                <?php esc_html_e( 'View Our Services', 'leadership-coach' ); ?>
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