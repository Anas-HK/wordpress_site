<?php
/**
 * The main template file
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php if ( is_home() && is_front_page() ) : ?>
            <!-- Homepage Content -->
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
                            <a href="<?php echo home_url('/contact'); ?>" class="btn btn-secondary">Get in Touch</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="services-preview">
                <div class="container">
                    <h2>Our Coaching Services</h2>
                    <div class="services-grid">
                        <div class="service-card">
                            <h3>Executive Coaching</h3>
                            <p>One-on-one coaching for senior leaders and executives.</p>
                        </div>
                        <div class="service-card">
                            <h3>Team Leadership</h3>
                            <p>Build stronger, more effective teams through better leadership.</p>
                        </div>
                        <div class="service-card">
                            <h3>Strategic Planning</h3>
                            <p>Develop clear vision and actionable strategies for growth.</p>
                        </div>
                    </div>
                </div>
            </section>
            
        <?php else : ?>
            <!-- Regular blog/archive content -->
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </header>
                        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No content found.</p>
            <?php endif; ?>
        <?php endif; ?>
        
    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>