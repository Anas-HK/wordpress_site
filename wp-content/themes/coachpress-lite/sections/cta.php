<?php
/**
 * CTA Section
 * 
 * @package CoachPress_Lite
 */
if( is_active_sidebar( 'cta' ) ){ ?>
<section id="cta_section" class="cta-section">
	<div class="container">
	    <?php dynamic_sidebar( 'cta' ); ?>
	</div>
</section> <!-- .bg-cta-section -->
<?php
}