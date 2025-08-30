<?php
/**
 * About Section
 * 
 * @package CoachPress_Lite
 */
if( is_active_sidebar( 'about' ) ){ ?>
<section id="about_section" class="about-section">
	<div class="section-grid">
	    <?php dynamic_sidebar( 'about' ); ?>
	</div>
</section><!-- .about-section -->
<?php
}