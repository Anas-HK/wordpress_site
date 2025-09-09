<?php

/**
 * Template Name: Book Appointment
 * 
 * The template for displaying the appointment booking page with Calendly integration
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


                <div class="entry-content">

                    <?php the_content(); ?>

                    <!-- Critical CSS for Hybrid Calendar Page -->
                    <style>
                    /* Force full width layout */
                    .entry-content { max-width: none !important; width: 100% !important; }
                    .content-area { max-width: none !important; width: 100% !important; }
                    .site-main { max-width: none !important; }
                    
                    /* Hybrid Booking Layout */
                    .hybrid-booking-wrapper {
                        max-width: 1200px;
                        margin: 0 auto;
                        padding: 2rem;
                    }
                    
                    .booking-intro {
                        text-align: center;
                        margin-bottom: 3rem;
                    }
                    
                    .booking-intro h2 {
                        color: var(--primary-purple);
                        font-size: 2.5rem;
                        margin-bottom: 1rem;
                        font-weight: 700;
                    }
                    
                    .booking-intro p {
                        font-size: 1.2rem;
                        color: #666;
                        max-width: 600px;
                        margin: 0 auto;
                        line-height: 1.6;
                    }
                    
                    .hybrid-booking-layout {
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 2rem;
                        margin-top: 3rem;
                    }
                    
                    /* Left Column - Calendly */
                    .booking-left {
                        background: white;
                        border-radius: 16px;
                        padding: 2rem;
                        box-shadow: 0 8px 30px rgba(var(--primary-purple-rgb), 0.1);
                        border: 1px solid rgba(var(--primary-purple-rgb), 0.1);
                    }

                    /* Calendly Embed Container ensures large height */
                    #calendly-embed {
                        width: 100%;
                        min-height: 1000px;
                    }
                    #calendly-embed .calendly-inline-widget,
                    .booking-left iframe[src*="calendly.com"] {
                        height: 1200px !important;
                        min-height: 1000px !important;
                        max-height: none !important;
                        width: 100% !important;
                    }
                    
                    .booking-left h3 {
                        color: var(--primary-purple);
                        font-size: 1.5rem;
                        margin-bottom: 1rem;
                        font-weight: 600;
                    }
                    
                    .booking-left p {
                        color: #666;
                        margin-bottom: 1.5rem;
                        line-height: 1.6;
                    }
                    
                    .calendly-inline-widget {
                        width: 100% !important;
                        height: 1000px !important;
                        min-height: 900px;
                        border-radius: 12px;
                        overflow: hidden;
                        border: 1px solid #e0e0e0;
                    }
                    
                    .calendly-fallback {
                        text-align: center;
                        padding: 3rem 2rem;
                        background: var(--light-background);
                        border-radius: 12px;
                        border: 2px dashed var(--secondary-lilac);
                    }
                    
                    .calendly-fallback h4 {
                        color: var(--primary-purple);
                        margin-bottom: 1rem;
                    }
                    
                    .calendly-fallback p {
                        margin-bottom: 1.5rem;
                    }
                    
                    /* Button Styles (reverted): primary filled gradient, secondary outline */
                    .btn-primary, .btn-secondary {
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.75rem 1.5rem;
                        border-radius: 25px;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 1rem;
                        transition: all 0.3s ease;
                        border: none;
                        cursor: pointer;
                    }
                    
                    /* Both buttons now use identical filled style with white text */
                    .btn-primary,
                    .btn-secondary {
                        background: var(--primary-purple);
                        color: white !important;
                        border: 2px solid var(--primary-purple);
                        box-shadow: none;
                    }
                    
                    /* Subtle hover for both buttons - keep purple background, add glow */
                    .btn-primary:hover,
                    .btn-secondary:hover {
                        background: var(--secondary-lilac) !important;
                        color: white !important;
                        border-color: var(--secondary-lilac) !important;
                        transform: translateY(-2px) !important;
                        text-decoration: none !important;
                        box-shadow: 0 8px 25px rgba(var(--primary-purple-rgb), 0.4) !important;
                    }

                    /* Hide any external icon glyphs inside the button, if present */
                    .calendly-link .external-icon { display: none !important; }
                    .calendly-fallback .btn-primary span { display: none !important; }
                    
                    /* Ensure all buttons stay visible in all states */
                    .btn-primary,
                    .btn-secondary,
                    .alternative-booking .btn-primary,
                    .alternative-booking .btn-secondary,
                    .calendly-fallback .btn-primary {
                        opacity: 1 !important;
                        visibility: visible !important;
                        display: inline-flex !important;
                    }
                    
                    .btn-primary:hover,
                    .btn-secondary:hover,
                    .alternative-booking .btn-primary:hover,
                    .alternative-booking .btn-secondary:hover,
                    .calendly-fallback .btn-primary:hover {
                        opacity: 1 !important;
                        visibility: visible !important;
                        display: inline-flex !important;
                    }
                    <style>
                    /* Success Error Messages */
                    .form-message {
                        padding: 1rem;
                        border-radius: 8px;
                        margin-bottom: 1.5rem;
                        font-weight: 500;
                    }
                    
                    .form-message.success {
                        background: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                    }
                    
                    .form-message.error {
                        background: #f8d7da;
                        color: #721c24;
                        border: 1px solid #f5c6cb;
                    }

                    /* Icon color inherit for inline SVGs */
                    .session-detail .detail-icon {
                        color: var(--primary-purple);
                    }

                    /* Hide any Calendly loader text/spinner injected above iframe */
                    #calendly-embed .calendly-loading,
                    #calendly-embed .calendly-spinner,
                    #calendly-embed .loading,
                    #calendly-embed [aria-busy='true'] {
                        display: none !important;
                    }

                    /* Ensure Contact Us button stays outlined on hover */
                    .alternative-booking .btn-secondary:hover {
                        background: #ffffff !important;
                        color: var(--primary-purple) !important;
                        border-color: var(--secondary-lilac) !important;
                        text-decoration: none !important;
                    }
                    </style>
                    
                    <!-- Hybrid Booking Layout -->
                    <div class="hybrid-booking-wrapper">
                        <div class="booking-intro">
                            <h2><?php esc_html_e('Schedule Your Leadership Coaching Session', 'leadership-coach'); ?></h2>
                            <p><?php esc_html_e('Use the calendar below to pick a time that works for you. It will automatically detect your timezone.', 'leadership-coach'); ?></p>
                        </div>

                        <div class="hybrid-booking-layout">
                            <!-- Left Column - Calendly Widget -->
                            <div class="booking-left">
                                <h3><?php esc_html_e('📅 Book Instantly with Calendly', 'leadership-coach'); ?></h3>
                                <p><?php esc_html_e('See real-time availability and book your session immediately.', 'leadership-coach'); ?></p>
                                
                                <!-- Calendly Embed Container (initialized via JS) -->
                                <div id="calendly-embed"></div>
                                
                                <!-- Calendly Fallback -->
                                <div class="calendly-fallback" style="display: none;">
                                    <h4><?php esc_html_e('Unable to load calendar', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e('Please use the appointment form on the right or visit our Calendly page directly.', 'leadership-coach'); ?></p>
                                    <a href="https://calendly.com/laraibsshaikh10/30min?hide_gdpr_banner=1&background_color=ffffff&text_color=2e2c38&primary_color=d4a5a5" target="_blank" rel="noopener" class="btn-primary">
                                        <?php esc_html_e('Open Calendly Page', 'leadership-coach'); ?>
                                    </a>
                                </div>
                            </div>

                            <script type="text/javascript">
                            (function() {
var baseUrl = 'https://calendly.com/laraibsshaikh10/30min?hide_gdpr_banner=1&background_color=ffffff&text_color=2e2c38&primary_color=d4a5a5';
                                var parent = document.getElementById('calendly-embed');
                                var fallback = document.querySelector('.calendly-fallback');
                                function forceIframeHeight() {
                                    var iframe = parent && parent.querySelector('iframe');
                                    if (iframe) {
                                        iframe.style.height = '1200px';
                                        iframe.style.minHeight = '1000px';
                                        iframe.removeAttribute('scrolling');
                                    }
                                }
                                function initCalendly(prefill) {
                                    if (!window.Calendly || !parent) return;
                                    parent.innerHTML = '';
                                    try {
                                        window.Calendly.initInlineWidget({
                                            url: baseUrl,
                                            parentElement: parent,
                                            prefill: prefill || {}
                                        });
                                        forceIframeHeight();
                                        if (fallback) fallback.style.display = 'none';
                                    } catch(e) { if (fallback) fallback.style.display = 'block'; }
                                }
                                function whenCalendlyReady(cb){
                                    if (window.Calendly) { cb(); return; }
                                    var tries = 0;
                                    var iv = setInterval(function(){
                                        tries++;
                                        if (window.Calendly) { clearInterval(iv); cb(); }
                                        if (tries > 200) { clearInterval(iv); if (fallback) fallback.style.display = 'block'; }
                                    }, 50);
                                }
                                window.addEventListener('load', function(){
                                    whenCalendlyReady(function(){ initCalendly({}); });
                                    setTimeout(forceIframeHeight, 1500);
                                });
                                window.addEventListener('message', function(event){
                                    if (!event || !event.data) return;
                                    if (event.data.event && event.origin && event.origin.indexOf('calendly.com') !== -1) { forceIframeHeight(); }
                                });
                            })();
                            </script>

                        </div>

                        <!-- Alternative Booking Options -->
                        <div class="alternative-booking">
                            <h3><?php esc_html_e('Prefer to Book Differently?', 'leadership-coach'); ?></h3>
                            <p><?php esc_html_e('If you\'re having trouble with the calendar above, you can also:', 'leadership-coach'); ?></p>

                            <div class="booking-options">
                                <div class="booking-option">
                                    <h4><?php esc_html_e('Direct Link', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e('Visit our Calendly page directly:', 'leadership-coach'); ?></p>
                                    <a href="https://calendly.com/laraibsshaikh10/30min?hide_gdpr_banner=1&background_color=ffffff&text_color=2e2c38&primary_color=d4a5a5" target="_blank" rel="noopener" class="btn-primary calendly-link">
                                        <?php esc_html_e('Open Calendly', 'leadership-coach'); ?>
                                    </a>
                                </div>

                                <div class="booking-option">
                                    <h4><?php esc_html_e('Contact Us', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e('Get in touch and we\'ll help you schedule:', 'leadership-coach'); ?></p>
                                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-secondary">
                                        <?php esc_html_e('Contact Us', 'leadership-coach'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- What to Expect (Updated with Better Icons) -->
                        <div class="session-info">
                            <h3><?php esc_html_e('What to Expect', 'leadership-coach'); ?></h3>
                            <div class="session-details">
                                <div class="session-detail">
<div class="detail-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12.5 7H11V13L16.2 16.2L17 14.9L12.5 12.2V7Z" fill="currentColor"/></svg></div>
                                    <div class="detail-content">
                                        <h4><?php esc_html_e('30-Minute Session', 'leadership-coach'); ?></h4>
                                        <p><?php esc_html_e('A focused conversation about your leadership goals and challenges.', 'leadership-coach'); ?></p>
                                    </div>
                                </div>

                                <div class="session-detail">
<div class="detail-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V8L12 13L20 8V18ZM12 11L4 6H20L12 11Z" fill="currentColor"/></svg></div>
                                    <div class="detail-content">
                                        <h4><?php esc_html_e('Virtual Meeting', 'leadership-coach'); ?></h4>
                                        <p><?php esc_html_e('Conducted via video call for your convenience.', 'leadership-coach'); ?></p>
                                    </div>
                                </div>

                                <div class="session-detail">
<div class="detail-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z" fill="currentColor"/></svg></div>
                                    <div class="detail-content">
                                        <h4><?php esc_html_e('Personalized Approach', 'leadership-coach'); ?></h4>
                                        <p><?php esc_html_e('Tailored discussion based on your specific needs and objectives.', 'leadership-coach'); ?></p>
                                    </div>
                                </div>

                                <div class="session-detail">
<div class="detail-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 3H14.82C14.4 1.84 13.3 1 12 1C10.7 1 9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM12 3C12.55 3 13 3.45 13 4C13 4.55 12.55 5 12 5C11.45 5 11 4.55 11 4C11 3.45 11.45 3 12 3ZM19 19H5V5H7V8H17V5H19V19ZM10 11V13H7V11H10ZM10 15V17H7V15H10ZM10 7V9H7V7H10ZM17 11V13H12V11H17ZM17 15V17H12V15H17ZM17 7V9H12V7H17Z" fill="currentColor"/></svg></div>
                                    <div class="detail-content">
                                        <h4><?php esc_html_e('Action Plan', 'leadership-coach'); ?></h4>
                                        <p><?php esc_html_e('Leave with clear next steps and recommendations.', 'leadership-coach'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonials Preview -->
                        <div class="calendar-testimonials">
                            <h3><?php esc_html_e('What Clients Say', 'leadership-coach'); ?></h3>
                            <div class="testimonial-slider">
                                <div class="testimonial-item">
                                    <div class="testimonial-content">
                                        <p>"The coaching sessions have transformed my approach to leadership. I'm now more confident in my decision-making and better at empowering my team."</p>
                                    </div>
                                    <div class="testimonial-author">
                                        <div class="author-name">Sarah Johnson</div>
                                        <div class="author-title">Marketing Director</div>
                                    </div>
                                </div>
                                <div class="testimonial-item">
                                    <div class="testimonial-content">
                                        <p>"I appreciate how the sessions are structured - focused yet flexible. The action plan at the end of each meeting gives me clear direction."</p>
                                    </div>
                                    <div class="testimonial-author">
                                        <div class="author-name">Michael Chen</div>
                                        <div class="author-title">Senior Manager</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Section -->
                        <div class="calendar-faq">
                            <h3><?php esc_html_e('Frequently Asked Questions', 'leadership-coach'); ?></h3>
                            <div class="faq-items">
                                <div class="faq-item">
                                    <h4><?php esc_html_e('How should I prepare for my first session?', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e('Consider your leadership goals and challenges beforehand. Come ready to share your experiences and what you hope to achieve through coaching.', 'leadership-coach'); ?></p>
                                </div>
                                <div class="faq-item">
                                    <h4><?php esc_html_e('What happens after the initial consultation?', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e("We'll discuss your needs and goals, then recommend a coaching program tailored to your specific situation. There's no obligation to continue beyond this first session.", 'leadership-coach'); ?></p>
                                </div>
                                <div class="faq-item">
                                    <h4><?php esc_html_e('How do I reschedule if needed?', 'leadership-coach'); ?></h4>
                                    <p><?php esc_html_e("You can easily reschedule through the confirmation email you'll receive after booking. We ask for at least 24 hours notice for any changes.", 'leadership-coach'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>
