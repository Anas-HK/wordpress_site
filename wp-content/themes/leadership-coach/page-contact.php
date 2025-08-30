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
                            
                            <?php /* Removed old success/error banner logic; mailto flow does not use server posting. */ ?>
                            <?php $to_email = sanitize_email( get_theme_mod('email', get_option('admin_email')) ); ?>
                            
                            <form id="contact-form" class="contact-form" method="get" action="#" data-mailto="<?php echo esc_attr( $to_email ); ?>">
                                <!-- No nonce or server action needed for mailto compose -->
                                
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
                                        <?php esc_html_e( 'Open Email Composer', 'leadership-coach' ); ?>
                                    </button>
                                </div>
                            </form>

                            <script type="text/javascript">
                            (function(){
                                var form = document.getElementById('contact-form');
                                if (!form) return;
                                form.addEventListener('submit', function(e){
                                    e.preventDefault();
                                    var to = form.getAttribute('data-mailto') || '';
                                    if (!to) { to = '<?php echo esc_js( get_option('admin_email') ); ?>'; }
                                    var name = (document.getElementById('contact_name') || {}).value || '';
                                    var email = (document.getElementById('contact_email') || {}).value || '';
                                    var phone = (document.getElementById('contact_phone') || {}).value || '';
                                    var subjectSel = document.getElementById('contact_subject');
                                    var subject = subjectSel && subjectSel.value ? subjectSel.options[subjectSel.selectedIndex].text : 'Website Contact';
                                    var message = (document.getElementById('contact_message') || {}).value || '';

                                    var fullSubject = encodeURIComponent(subject + ' - ' + name);
                                    var bodyLines = [];
                                    if (name) bodyLines.push('Name: ' + name);
                                    if (email) bodyLines.push('Email: ' + email);
                                    if (phone) bodyLines.push('Phone: ' + phone);
                                    bodyLines.push('');
                                    bodyLines.push('Message:');
                                    bodyLines.push(message);
                                    var body = encodeURIComponent(bodyLines.join('\n'));

                                    var mailto = 'mailto:' + to + '?subject=' + fullSubject + '&body=' + body;
                                    window.location.href = mailto;
                                });
                            })();
                            </script>
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