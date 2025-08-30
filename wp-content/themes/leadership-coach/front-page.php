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
                    <h1 class="hero-title">Welcome to EMBRACED Parenting</h1>
                    <p class="hero-description">
                        Parenting is about connection, not perfection. Discover a compassionate approach to parenting 
                        that fosters understanding, emotional growth, and lasting bonds with your children.
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Join Me in This Journey</a>
                        <a href="<?php echo home_url('/about'); ?>" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- EMBRACED Framework Section -->
        <section class="embraced-section">
            <div class="container">
                <h2 class="section-title">The EMBRACED Framework</h2>
                <p class="framework-intro">At the heart of my coaching is EMBRACED, a framework that reflects the values I hold close as both a parent and a coach:</p>
                <div class="embraced-grid">
                    <div class="embraced-card">
                        <div class="embraced-icon">E</div>
                        <h3>Empathy</h3>
                        <p>Seeing and feeling with your child</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">M</div>
                        <h3>Mindful Presence</h3>
                        <p>Parenting with awareness and calm</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">B</div>
                        <h3>Bonding & Belonging</h3>
                        <p>Creating secure attachment and safety</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">R</div>
                        <h3>Respect</h3>
                        <p>Honoring your child's voice and individuality</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">A</div>
                        <h3>Attunement</h3>
                        <p>Tuning into emotions beneath behavior</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">C</div>
                        <h3>Compassion</h3>
                        <p>Responding with mercy and kindness</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">E</div>
                        <h3>Empowerment</h3>
                        <p>Nurturing resilience and growth in both parent and child</p>
                    </div>
                    <div class="embraced-card">
                        <div class="embraced-icon">D</div>
                        <h3>Development</h3>
                        <p>Supporting lifelong learning for you and your family</p>
                    </div>
                </div>
                <div class="framework-conclusion">
                    <p>This approach is not about striving for perfection—it's about walking the journey with intention, heart, and connection.</p>
                    <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Join Me in This Journey</a>
                </div>
            </div>
        </section>
        
        <!-- Why Parenting Can Feel Overwhelming Section -->
        <section class="parenting-challenges">
            <div class="container">
                <h2 class="section-title">Why Parenting Can Feel Overwhelming</h2>
                <div class="challenges-content">
                    <p>Parenting can often feel like an uphill battle—especially when the old "do as I say" approach leaves both you and your child frustrated. It's easy to feel like nothing you do is right, or to worry that you're failing your child, even though you're doing your best.</p>
                    <p>The truth is, most of us were never shown how to parent in a way that fosters connection, understanding, and emotional growth. Instead, we rely on the patterns we grew up with—which don't always serve us or our children.</p>
                    <p>Seeking support can feel vulnerable or even intimidating, but it doesn't have to be. When you reach out, you'll find a compassionate, judgment-free space where your challenges are understood. Together, we'll create a personalized approach that works for your family, guiding you step by step toward calmer, more confident, and more connected parenting.</p>
                    <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Start Your Discovery Call</a>
                </div>
            </div>
        </section>
        
        <!-- About Preview Section -->
        <section class="about-preview">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>About Me</h2>
                        <p>I'm a Certified Parent Coach and a mother to two wonderful children, ages 6 and 3. Parenting has been the most meaningful journey of my life, and it continues to teach me every day.</p>
                        <p>My own experiences have shown me how easy it is to feel overwhelmed, frustrated, or unsure in the middle of parenting challenges. I don't claim to have all the answers—but what I do bring is compassion, curiosity, and a deep passion for helping parents find calm, connection, and confidence.</p>
                        <a href="<?php echo home_url('/about'); ?>" class="btn btn-outline">Connect With Me</a>
                    </div>
                    <div class="about-image">
                        <?php 
                        $teacher_img_path = get_stylesheet_directory() . '/assets/images/teacher.jpg';
                        $teacher_img_url  = get_stylesheet_directory_uri() . '/assets/images/teacher.jpg';
                        if ( file_exists( $teacher_img_path ) ) : ?>
                            <img src="<?php echo esc_url( $teacher_img_url ); ?>" alt="Your Coach" class="about-photo" />
                        <?php else : ?>
                            <div class="placeholder-image">
                                <span>Coach Photo</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- How We Can Work Together Section -->
        <section class="work-together-section">
            <div class="container">
                <h2 class="section-title">How We Can Work Together</h2>
                <div class="work-together-grid">
                    <div class="work-card">
                        <div class="work-icon">🌱</div>
                        <h3>Free Discovery Call</h3>
                        <p>Every coaching journey begins with a complimentary call. This is a chance for us to connect, talk about your goals, and explore how coaching might support your family.</p>
                        <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Book Your Free Discovery Call</a>
                    </div>
                    <div class="work-card">
                        <div class="work-icon">✨</div>
                        <h3>Tailored 1:1 Coaching</h3>
                        <p>If it feels like the right fit, we'll move forward with personalized coaching sessions designed around your family's unique needs. Together, we'll explore tools and approaches that are practical, compassionate, and rooted in connection.</p>
                        <a href="<?php echo home_url('/services'); ?>" class="btn btn-secondary">Learn More</a>
                    </div>
                    <div class="work-card">
                        <div class="work-icon">📚</div>
                        <h3>Courses (Coming Soon!)</h3>
                        <p>Stay tuned for some amazing, informative, and heart-centered courses designed to support and inspire you on your parenting journey. These will provide practical tools and meaningful insights.</p>
                        <a href="#newsletter" class="btn btn-outline">Stay Updated</a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <h2 class="section-title">Testimonials / Reviews</h2>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <blockquote>"Working with [Your Name] completely changed how I respond to my child. I feel calmer, more confident, and more connected than ever."</blockquote>
                        <cite>— Parent, 2024</cite>
                    </div>
                    <div class="testimonial-card">
                        <blockquote>"I used to feel so frustrated every time my child acted out. The strategies and insights I learned have made such a difference for both of us."</blockquote>
                        <cite>— Parent, 2024</cite>
                    </div>
                    <div class="testimonial-card">
                        <blockquote>"Her support and guidance helped me understand my triggers and respond with compassion instead of frustration. I can't recommend her enough!"</blockquote>
                        <cite>— Parent, 2024</cite>
                    </div>
                </div>
                <p class="testimonials-note">💬 More testimonials coming soon!</p>
                <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary">Join Me on This Transformative Journey</a>
            </div>
        </section>
        
        <!-- Newsletter Section (temporarily disabled)
        <section class="newsletter-section" id="newsletter">
            <div class="container">
                <h2 class="section-title">Stay Updated</h2>
                <p>Want to be the first to know when new courses or blogs are released? Sign up for our mailing list and stay connected:</p>
                <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('newsletter_signup', 'newsletter_nonce'); ?>
                    <input type="hidden" name="action" value="newsletter_signup">
                    <div class="form-group">
                        <input type="text" name="newsletter_name" placeholder="Name (optional)" class="form-control">
                        <input type="email" name="newsletter_email" placeholder="Email Address (required)" required class="form-control">
                        <button type="submit" class="btn btn-primary">
                            👉 Join Me in Learning & Growing
                        </button>
                    </div>
                </form>
            </div>
        </section>
        -->
        
        <!-- Final CTA Section -->
        <section class="final-cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>From My Heart to Yours</h2>
                    <p>Parenting is not about perfection—it is about connection, compassion, and growth. None of us have it all figured out, and that's okay. What matters most is showing up with love, presence, and the willingness to grow alongside our children.</p>
                    <p><strong>Together, let us embrace this journey with intention, humility, and heart.</strong></p>
                    <a href="<?php echo home_url('/calendar'); ?>" class="btn btn-primary btn-large">Let's Embrace This Journey Together</a>
                </div>
            </div>
        </section>
        
    </main>
</div>

<?php get_footer(); ?>