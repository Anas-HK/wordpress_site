<?php
/**
 * The front page template file
 * 
 * This template is used for the front page of the site
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Transform Your Leadership Journey</h1>
                    <p class="hero-description">
                        Unlock your potential with personalized coaching designed to elevate your leadership skills 
                        and drive meaningful change in your organization.
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Book a Session</a>
                        <a href="<?php echo home_url('/about'); ?>" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Services Section -->
        <section class="services-section">
            <div class="container">
                <h2 class="section-title">Our Coaching Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">🎯</div>
                        <h3>Executive Coaching</h3>
                        <p>One-on-one coaching sessions designed for senior leaders and executives looking to enhance their leadership capabilities.</p>
                        <a href="<?php echo home_url('/services'); ?>" class="service-link">Learn More</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">👥</div>
                        <h3>Team Leadership</h3>
                        <p>Build stronger, more effective teams through better leadership practices and communication strategies.</p>
                        <a href="<?php echo home_url('/services'); ?>" class="service-link">Learn More</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">📈</div>
                        <h3>Strategic Planning</h3>
                        <p>Develop clear vision and actionable strategies for sustainable growth and organizational success.</p>
                        <a href="<?php echo home_url('/services'); ?>" class="service-link">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- About Preview Section -->
        <section class="about-preview">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>About Your Coach</h2>
                        <p>With over 15 years of experience in leadership development and organizational transformation, I'm passionate about helping leaders unlock their full potential.</p>
                        <p>My approach combines proven methodologies with personalized strategies to create lasting change in both individual leaders and their organizations.</p>
                        <a href="<?php echo home_url('/about'); ?>" class="btn btn-outline">Read My Story</a>
                    </div>
                    <div class="about-image">
                        <div class="placeholder-image">
                            <span>Coach Photo</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Transform Your Leadership?</h2>
                    <p>Book a complimentary consultation to discuss your leadership goals and how we can work together to achieve them.</p>
                    <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary btn-large">Schedule Your Free Consultation</a>
                </div>
            </div>
        </section>
        
    </main>
</div>

<?php get_footer(); ?>