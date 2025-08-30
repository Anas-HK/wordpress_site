jQuery(document).ready(function($){
    /* Move Fornt page widgets to frontpage panel */
	wp.customize.section( 'sidebar-widgets-newsletter' ).panel( 'frontpage_settings' );
	wp.customize.section( 'sidebar-widgets-newsletter' ).priority( '20' );
    wp.customize.section( 'sidebar-widgets-promo' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-promo' ).priority( '30' );
    wp.customize.section( 'sidebar-widgets-about' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-about' ).priority( '40' );
    wp.customize.section( 'sidebar-widgets-service' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-service' ).priority( '50' );
    wp.customize.section( 'sidebar-widgets-testimonial' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-testimonial' ).priority( '60' );    
    wp.customize.section( 'sidebar-widgets-client' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-client' ).priority( '70' );    
    wp.customize.section( 'sidebar-widgets-cta' ).panel( 'frontpage_settings' );
	wp.customize.section( 'sidebar-widgets-cta' ).priority( '80' );
    
    
    //Scroll to front page section
    $('body').on('click', '#sub-accordion-panel-frontpage_settings .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        CoachPressLitescrollToSection( section_id );
    }); 
    
    /* Home page preview url */
    wp.customize.panel( 'frontpage_settings', function( section ){
        section.expanded.bind( function( isExpanded ) {
            if( isExpanded ){
                wp.customize.previewer.previewUrl.set( coachpress_lite_cdata.home );
            }
        });
    });
    
    $('#sub-accordion-section-blog_layout').on( 'click', '.blog_sidebar_text', function(e){
        e.preventDefault();
        wp.customize.control( 'layout_style' ).focus();        
    });

    $( 'input[name=coachpress-lite-flush-local-fonts-button]' ).on( 'click', function( e ) {
        var data = {
            wp_customize: 'on',
            action: 'coachpress_lite_flush_fonts_folder',
            nonce: coachpress_lite_cdata.flushFonts
        };  
        $( 'input[name=coachpress-lite-flush-local-fonts-button]' ).attr('disabled', 'disabled');

        $.post( ajaxurl, data, function ( response ) {
            if ( response && response.success ) {
                $( 'input[name=coachpress-lite-flush-local-fonts-button]' ).val( 'Successfully Flushed' );
            } else {
                $( 'input[name=coachpress-lite-flush-local-fonts-button]' ).val( 'Failed, Reload Page and Try Again' );
            }
        });
    }); 
});

function CoachPressLitescrollToSection( section_id ){
    var preview_section_id = "banner_section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {
        
        case 'accordion-section-sidebar-widgets-newsletter':
        preview_section_id = "newsletter_section";
        break;

        case 'accordion-section-sidebar-widgets-promo':
        preview_section_id = "promo_section";
        break;

        case 'accordion-section-sidebar-widgets-about':
        preview_section_id = "about_section";
        break;
        
        case 'accordion-section-sidebar-widgets-service':
        preview_section_id = "service_section";
        break;

        case 'accordion-section-wheel_of_life':
        preview_section_id = "wheeloflife_section";
        break;

        case 'accordion-section-sidebar-widgets-testimonial':
        preview_section_id = "testimonial_section";
        break;

        case 'accordion-section-sidebar-widgets-client':
        preview_section_id = "client_section";
        break;
        
        case 'accordion-section-sidebar-widgets-cta':
        preview_section_id = "cta_section";
        break;   
        
        case 'accordion-section-blog_section':
        preview_section_id = "blog_section";
        break;
        
        case 'accordion-section-front_sort':
        preview_section_id = "banner_section";
        break;
    }

    if( $contents.find('#'+preview_section_id).length > 0 && $contents.find('.home').length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + preview_section_id ).offset().top
        }, 1000);
    }
}

( function( api ) {

    // Extends our custom "example-1" section.
    api.sectionConstructor['coachpress-lite-section'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );