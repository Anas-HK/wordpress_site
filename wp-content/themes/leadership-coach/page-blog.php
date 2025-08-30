<?php
/**
 * Template Name: Blog Page
 * 
 * The template for displaying the blog page with posts
 *
 * @package Leadership_Coach
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main blog-main">
        
        <div class="blog-header">
            <div class="container">
                <h1 class="blog-title"><?php esc_html_e( 'Leadership Insights & Tips', 'leadership-coach' ); ?></h1>
                <p class="blog-description"><?php esc_html_e( 'Discover strategies, insights, and practical tips to enhance your leadership journey and drive meaningful change in your organization.', 'leadership-coach' ); ?></p>
            </div>
        </div>

        <div class="blog-content">
            <div class="container">
                
                <!-- Featured Post Section -->
                <?php
                $featured_post = get_posts(array(
                    'numberposts' => 1,
                    'post_status' => 'publish',
                    'meta_key' => '_featured_post',
                    'meta_value' => '1'
                ));
                
                if (empty($featured_post)) {
                    $featured_post = get_posts(array(
                        'numberposts' => 1,
                        'post_status' => 'publish'
                    ));
                }
                
                if (!empty($featured_post)) :
                    $post = $featured_post[0];
                    setup_postdata($post);
                ?>
                    <section class="featured-post-section">
                        <h2 class="section-title"><?php esc_html_e( 'Featured Article', 'leadership-coach' ); ?></h2>
                        <article class="featured-post">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="featured-post-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="featured-post-content">
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                    <span class="post-category">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo esc_html($categories[0]->name);
                                        }
                                        ?>
                                    </span>
                                </div>
                                
                                <h3 class="featured-post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="featured-post-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                    <?php esc_html_e( 'Read Full Article', 'leadership-coach' ); ?>
                                    <span class="arrow">→</span>
                                </a>
                            </div>
                        </article>
                    </section>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <!-- Blog Posts Grid -->
                <section class="blog-posts-section">
                    <h2 class="section-title"><?php esc_html_e( 'Latest Articles', 'leadership-coach' ); ?></h2>
                    
                    <div class="blog-posts-grid">
                        <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $blog_posts = new WP_Query(array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'posts_per_page' => 9,
                            'paged' => $paged,
                            'post__not_in' => !empty($featured_post) ? array($featured_post[0]->ID) : array()
                        ));
                        
                        if ($blog_posts->have_posts()) :
                            while ($blog_posts->have_posts()) : $blog_posts->the_post();
                        ?>
                                <article class="blog-post-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('class' => 'post-image')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span class="post-date"><?php echo get_the_date(); ?></span>
                                            <span class="post-category">
                                                <?php
                                                $categories = get_the_category();
                                                if (!empty($categories)) {
                                                    echo esc_html($categories[0]->name);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <h3 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" class="read-more">
                                            <?php esc_html_e( 'Read More', 'leadership-coach' ); ?>
                                        </a>
                                    </div>
                                </article>
                        <?php
                            endwhile;
                        else :
                        ?>
                            <div class="no-posts">
                                <h3><?php esc_html_e( 'No Articles Yet', 'leadership-coach' ); ?></h3>
                                <p><?php esc_html_e( 'We\'re working on creating valuable content for you. Check back soon for leadership insights and tips!', 'leadership-coach' ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($blog_posts->max_num_pages > 1) : ?>
                        <div class="blog-pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $blog_posts->max_num_pages,
                                'current' => $paged,
                                'prev_text' => '← ' . esc_html__('Previous', 'leadership-coach'),
                                'next_text' => esc_html__('Next', 'leadership-coach') . ' →',
                                'type' => 'list'
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </section>

                <!-- Newsletter Signup -->
                <section class="newsletter-section">
                    <div class="newsletter-content">
                        <h2><?php esc_html_e( 'Stay Updated', 'leadership-coach' ); ?></h2>
                        <p><?php esc_html_e( 'Get the latest leadership insights and tips delivered directly to your inbox.', 'leadership-coach' ); ?></p>
                        
                        <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('newsletter_signup', 'newsletter_nonce'); ?>
                            <input type="hidden" name="action" value="newsletter_signup">
                            
                            <div class="form-group">
                                <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Enter your email address', 'leadership-coach'); ?>" required>
                                <button type="submit" class="btn-primary">
                                    <?php esc_html_e('Subscribe', 'leadership-coach'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
        
    </main>
</div>

<?php get_footer(); ?>