<?php
/**
 * Template Name: EMBRACED Framework Page
 * 
 * The template for displaying the detailed EMBRACED Framework methodology
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
                        <?php the_post_thumbnail( 'large', array( 'class' => 'framework-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    
                    <!-- Framework Introduction -->
                    <div class="framework-intro">
                        <h1 class="framework-title">The EMBRACED Framework</h1>
                        <p class="framework-subtitle">A compassionate approach to parenting that reflects the values I hold close as both a parent and a coach</p>
                        
                        <?php
                        the_content();
                        
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leadership-coach' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <!-- Detailed Framework Components -->
                    <div class="framework-components">
                        
                        <div class="component-card" id="empathy">
                            <div class="component-header">
                                <div class="component-letter">E</div>
                                <h2>Empathy</h2>
                                <p class="component-tagline">Seeing and feeling with your child</p>
                            </div>
                            <div class="component-content">
                                <p>Empathy forms the foundation of connected parenting. It's about stepping into your child's shoes, understanding their perspective, and validating their emotions. When we approach our children with genuine empathy, we create a safe space for them to express themselves authentically.</p>
                                <p>This doesn't mean agreeing with all behaviors, but rather understanding the emotions and needs beneath them. Empathy helps bridge the gap between parent and child, fostering deeper understanding and connection.</p>
                            </div>
                        </div>

                        <div class="component-card" id="mindful-presence">
                            <div class="component-header">
                                <div class="component-letter">M</div>
                                <h2>Mindful Presence</h2>
                                <p class="component-tagline">Parenting with awareness and calm</p>
                            </div>
                            <div class="component-content">
                                <p>Mindful presence is about being fully present with your child in each moment. It means setting aside distractions, tuning into your own emotional state, and responding rather than reacting to challenging situations.</p>
                                <p>When we parent from a place of mindfulness, we model emotional regulation for our children and create moments of genuine connection. This presence allows us to respond to our children's needs with intention rather than impulse.</p>
                            </div>
                        </div>

                        <div class="component-card" id="bonding-belonging">
                            <div class="component-header">
                                <div class="component-letter">B</div>
                                <h2>Bonding & Belonging</h2>
                                <p class="component-tagline">Creating secure attachment and safety</p>
                            </div>
                            <div class="component-content">
                                <p>Every child needs to feel they belong and are unconditionally loved. Bonding and belonging create the secure attachment that helps children develop confidence, resilience, and healthy relationships throughout their lives.</p>
                                <p>This component focuses on building trust, creating predictable routines, and ensuring your child knows they are valued for who they are, not just what they do. It's about creating a family culture where everyone feels seen, heard, and valued.</p>
                            </div>
                        </div>

                        <div class="component-card" id="respect">
                            <div class="component-header">
                                <div class="component-letter">R</div>
                                <h2>Respect</h2>
                                <p class="component-tagline">Honoring your child's voice and individuality</p>
                            </div>
                            <div class="component-content">
                                <p>Respect means honoring your child as a unique individual with their own thoughts, feelings, and perspectives. It involves listening to their voice, considering their input in age-appropriate ways, and treating them with the same courtesy you would offer any person you care about.</p>
                                <p>Respectful parenting doesn't mean permissive parenting. It means setting boundaries while maintaining dignity for both parent and child. It's about guiding with kindness while still maintaining your role as the caring, responsible adult.</p>
                            </div>
                        </div>

                        <div class="component-card" id="attunement">
                            <div class="component-header">
                                <div class="component-letter">A</div>
                                <h2>Attunement</h2>
                                <p class="component-tagline">Tuning into emotions beneath behavior</p>
                            </div>
                            <div class="component-content">
                                <p>Attunement is the ability to sense and respond to your child's emotional needs. It's about looking beyond the surface behavior to understand the underlying feelings, needs, or struggles your child might be experiencing.</p>
                                <p>When we're attuned to our children, we can address the root cause rather than just managing the symptoms. This deeper understanding allows us to respond with compassion and provide the support our children truly need.</p>
                            </div>
                        </div>

                        <div class="component-card" id="compassion">
                            <div class="component-header">
                                <div class="component-letter">C</div>
                                <h2>Compassion</h2>
                                <p class="component-tagline">Responding with mercy and kindness</p>
                            </div>
                            <div class="component-content">
                                <p>Compassion involves responding to our children (and ourselves) with kindness, especially during difficult moments. It means offering grace when mistakes are made and approaching challenges as opportunities for growth rather than moments for punishment.</p>
                                <p>Compassionate parenting models forgiveness, resilience, and the understanding that everyone is human. It teaches children that making mistakes doesn't diminish their worth and that growth comes through understanding, not shame.</p>
                            </div>
                        </div>

                        <div class="component-card" id="empowerment">
                            <div class="component-header">
                                <div class="component-letter">E</div>
                                <h2>Empowerment</h2>
                                <p class="component-tagline">Nurturing resilience and growth in both parent and child</p>
                            </div>
                            <div class="component-content">
                                <p>Empowerment is about helping your child develop the skills, confidence, and resilience they need to navigate life's challenges. It involves providing age-appropriate choices, encouraging problem-solving, and celebrating growth and effort.</p>
                                <p>This component also focuses on empowering you as a parent—building your confidence, helping you trust your instincts, and providing you with practical tools to handle parenting challenges with grace and effectiveness.</p>
                            </div>
                        </div>

                        <div class="component-card" id="development">
                            <div class="component-header">
                                <div class="component-letter">D</div>
                                <h2>Development</h2>
                                <p class="component-tagline">Supporting lifelong learning for you and your family</p>
                            </div>
                            <div class="component-content">
                                <p>Development recognizes that both parents and children are constantly growing and learning. It's about understanding developmental stages, celebrating progress, and maintaining a growth mindset that embraces challenges as opportunities.</p>
                                <p>This component emphasizes that parenting is a journey of continuous learning. It encourages parents to be patient with themselves, to seek support when needed, and to remember that growth takes time for everyone in the family.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Framework Summary -->
                    <div class="framework-summary">
                        <h2>Living the EMBRACED Approach</h2>
                        <div class="summary-content">
                            <p>The EMBRACED framework isn't about perfection—it's about intention. It's about showing up for your children with awareness, compassion, and connection. Some days you'll embody all these principles beautifully, and other days you might struggle with just one or two. That's perfectly normal and human.</p>
                            
                            <p>What matters is the intention to grow, the willingness to reconnect when things go off track, and the commitment to approaching your children with love and respect, even in challenging moments.</p>
                            
                            <blockquote class="framework-quote">
                                <p><strong>"This approach is not about striving for perfection—it's about walking the journey with intention, heart, and connection."</strong></p>
                            </blockquote>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="framework-cta">
                        <h2><?php esc_html_e( 'Ready to Embrace This Approach?', 'leadership-coach' ); ?></h2>
                        <p><?php esc_html_e( 'If the EMBRACED framework resonates with you and you\'re ready to explore how it can transform your family relationships, I\'d love to connect with you.', 'leadership-coach' ); ?></p>
                        <div class="cta-buttons">
                            <a href="<?php echo esc_url( home_url( '/calendar' ) ); ?>" class="btn-primary">
                                <?php esc_html_e( 'Book Your Free Discovery Call', 'leadership-coach' ); ?>
                            </a>
                            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-secondary">
                                <?php esc_html_e( 'Learn More', 'leadership-coach' ); ?>
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
