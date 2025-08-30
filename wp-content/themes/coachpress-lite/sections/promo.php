<?php
/**
 * Promo Section
 * 
 * @package CoachPress_Lite
 */
if( is_active_sidebar( 'promo' ) ){ ?>
<section id="promo_section" class="promo-section">
	<div class="container">
	    <?php dynamic_sidebar( 'promo' ); ?>
	</div>
</section> <!-- .promo-section -->
<?php
}