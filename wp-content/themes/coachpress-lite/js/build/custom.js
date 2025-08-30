jQuery(document).ready(function($) {

    var slider_auto, slider_loop, rtl;
    
    if( coachpress_lite_data.auto == '1' ){
        slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( coachpress_lite_data.loop == '1' ){
        slider_loop = true;
    }else{
        slider_loop = false;
    }
    
    if( coachpress_lite_data.rtl == '1' ){
        rtl = true;
    }else{
        rtl = false;
    }

    //sticky t bar toggle
    $(".sticky-t-bar .close").on( 'click', function () {
        $(".sticky-bar-content").slideToggle();
        $(".sticky-t-bar").toggleClass("active");
    });

    //move site branding in the middle of menu item
    $('.site-header, .site-banner.cta-newsletter-banner .banner-caption').removeClass('hide-element');
    $('.cta-newsletter-banner .item .section-subtitle, .cta-newsletter-banner .item .newsletter-img').prependTo('.text-holder');

    //header search form toggle 
    $('.header-search .search-toggle').on( 'click', function () {
        $(this).siblings('.header-search-wrap').fadeIn();
        // $('.header-search-wrap form .search-field').focus();
    });

    $('.header-search .close').on( 'click', function () {
        $(this).parents('.header-search-wrap').fadeOut();
    });

    $(window).on( 'keyup', function (e) {
        if (e.key == 'Escape') {
            $('.header-search .header-search-wrap').fadeOut();
        }
    });

    //add submenu toggle btn
    $('.nav-menu .menu-item-has-children').find('> a').after('<button class="submenu-toggle"><i class="fas fa-caret-down"></i></button>');

    //toggle main navigation
    $('.mobile-header .toggle-btn').on( 'click', function () {
        $(this).parents('.mobile-header').addClass('menu-toggled');
        $(this).siblings('.mobile-header-popup').animate({
            width: 'toggle',
        });
    });

    $('.mobile-header .mbl-header-inner > .close').on( 'click', function () {
        $(this).parents('.mobile-header').removeClass('menu-toggled');
        $('.mobile-header-popup').animate({
            width: 'toggle',
        });
    });

    //submenu toggle
    $('.menu-item-has-children .submenu-toggle').on('click', function () {
        $(this).toggleClass('active');
        $(this).siblings('.sub-menu').stop(true, false, true).slideToggle();
    });

    //move secondary menu into main navigation
    if($('.mobile-header .secondary-menu').length == '1') {
        $('.mobile-header .secondary-menu .nav-menu > li').insertAfter('.mobile-header .main-navigation .nav-menu > li:last-child');
    } 

    //add class on input field focus
    $('.header-search > .search-form input[type="search"]').on( 'focus', function () {
        $(this).parents('.search-form').addClass('focused');
    }).on( 'blur', function () {
        $(this).parents('.search-form').removeClass('focused');
    });

    $(window).on( 'keyup', function (e) {

        if (e.key == 'Escape') {
            // if ($('body').hasClass('menu-active')) {
            //     $('.site-header .secondary-menu > div').animate({
            //         width: 'toggle',
            //     });
            // }
            $('body').removeClass('menu-active');
            $('.header-bottom').fadeOut();
        }

    });

    $('.site-header .secondary-menu .toggle-btn').on( 'click', function () {
        $('body').addClass('menu-active');
        $(this).siblings('div').animate({
            width: 'toggle',
        });
    });

    $('.site-header .secondary-menu .close').on( 'click', function () {
        $('body').removeClass('menu-active');
        $(this).parent('div').animate({
            width: 'toggle',
        });
    });

    //back to top
    $(window).on( 'scroll', function () {
        if ($(this).scrollTop() > 200) {
            $('.back-to-top').addClass('active');
        } else {
            $('.back-to-top').removeClass('active');
        }
    });

    $('.back-to-top').on('click', function () {
        $('body,html').animate({
            scrollTop: 0,
        }, 600);
    });

    //for accessibility
    $('.main-navigation ul li a, .main-navigation ul li button, .footer-navigation ul li a, .footer-navigation ul li button, .footer-navigation ul li a, .footer-navigation ul li button').on( 'focus', function () {
        $(this).parents('li').addClass('hover');
    }).on( 'blur', function () {
        $(this).parents('li').removeClass('hover');
    });

    //banner slider
    $('.banner-slider .item-wrap').owlCarousel({
        items: 1,
        margin: 1,
        autoplay: slider_auto,
        loop: slider_loop,
        nav: true,
        dots: false,
        rewind: true,
        rtl: rtl,
        lazyLoad: true,
        autoplaySpeed: 800,
        animateOut: coachpress_lite_data.animation,
        autoplayTimeout: coachpress_lite_data.speed,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="38.781" height="9.63" viewBox="0 0 38.781 9.63"><g transform="translate(1068.309 1520.63) rotate(180)"><path d="M141.589-1.863l-8.34,4.815v-9.63Z" transform="translate(926.72 1517.678)" fill="#7d6a91"/><path d="M3089.528,1523h30.965" transform="translate(-2060 -7.502)" fill="none" stroke="#7d6a91" stroke-width="1"/></g></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="38.781" height="9.63" viewBox="0 0 38.781 9.63"><g transform="translate(1068.309 1520.63) rotate(180)"><path d="M141.589-1.863l-8.34,4.815v-9.63Z" transform="translate(926.72 1517.678)" fill="#7d6a91"/><path d="M3089.528,1523h30.965" transform="translate(-2060 -7.502)" fill="none" stroke="#7d6a91" stroke-width="1"/></g></svg>'
        ], 
        responsive : {
            0 : {
                nav: false,
                dots: true,
            }, 
            768 : {
                nav: true,
                dots: false,
            }
        }
    });

    //testimonial slider
    if ($('.testimonial-section .testimonial-wrap .widget').length > 1) {
        itemLoop = true;
    } else {
        itemLoop = false;
    }
    $('.testimonial-section .testimonial-wrap-inner').owlCarousel({
        items: 1,
        autoplay: false,
        loop: itemLoop,
        nav: false,
        dots: true,
        margin: 30,
        rtl: rtl,
        lazyLoad: true,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
    });

    //client section slider
    $('.client-section .elementor-widget-heading .elementor-heading-title').wrapInner('<span></span>');
    $('.client-section .blossom-inner-wrap').addClass('owl-carousel');
    if ($('.client-section .blossom-inner-wrap .image-holder').length > 4) {
        itemLoop = true;
    } else {
        itemLoop = false;
    }
    $('.client-section .widget .blossom-inner-wrap').owlCarousel({
        items: 4,
        autoplay: true,
        loop: itemLoop,
        nav: false,
        dots: false,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
        margin:20,
        rtl: rtl,
        lazyLoad: true,
        responsive: {
            0: {
                items: 2,
                // nav: false,
                // dots: true,
            },
            768: {
                items: 3,
                // nav: true,
                // dots: false,
            },
            1025: {
                items: 4,
            }
        }
    });

    //wrap sidebar widget title with span
    $('.widget .widget-title').wrapInner('<span></span>');

    $(window).on('load resize', function () {
        var breadcrumbHeight = $('.single .page-header .breadcrumb-wrapper').outerHeight();

        if ($('.single.style-one .page-header .breadcrumb-wrapper + .blossomthemes-email-newsletter-wrapper, .single.style-six .page-header .breadcrumb-wrapper + .blossomthemes-email-newsletter-wrapper').length == '1') {
            $('.single.style-one .page-header .breadcrumb-wrapper + .blossomthemes-email-newsletter-wrapper, .single.style-six .page-header .breadcrumb-wrapper + .blossomthemes-email-newsletter-wrapper').css({
                'margin-top': - breadcrumbHeight,
                'padding-top': breadcrumbHeight
            });
        }
    });

    //Guttenberg alignfull js
    $(window).on('load resize', function () {
        var gbWindowWidth = $(window).width();
        var gbContainerWidth = $('.coachpress-has-blocks .site-content > .container').width();
        var gbContentWidth = $('.coachpress-has-blocks .site-main .entry-content').width();
        var gbMarginFull = (parseInt(gbContainerWidth) - parseInt(gbWindowWidth)) / 2;
        var gbMarginCenter = (parseInt(gbContentWidth) - parseInt(gbWindowWidth)) / 2;

        $(".coachpress-has-blocks.full-width .site-main .entry-content .alignfull").css({ "max-width": gbWindowWidth, "width": gbWindowWidth, "margin-left": gbMarginFull });

        $(".coachpress-has-blocks.fullwidth-centered .site-main .entry-content .alignfull").css({ "max-width": gbWindowWidth, "width": gbWindowWidth, "margin-left": gbMarginCenter });

    });
    
    
    /* Portfolio Isotope Filter */
    if( $('.page-template-portfolio').length > 0 ){
        // init Isotope
        var $grid = $('.grid').imagesLoaded( function(){  
        
            $grid.isotope({
              // options
            });
            
            // filter items on button click
            $('.filter-button-group').on( 'click', 'button', function() {
              $('.filter-button-group button').removeClass('is-checked');
              $(this).addClass('is-checked');
              var filterValue = $(this).attr('data-filter');
              $grid.isotope({ filter: filterValue });
            });
        
        });
    }

    //Ajax for Add to Cart
    $('.btn-simple').on( 'click', function() {        
        $(this).addClass('adding-cart');
        var product_id = $(this).attr('id');
        
        $.ajax ({
            url     : coachpress_lite_data.ajax_url,  
            type    : 'POST',
            data    : 'action=coachpress_lite_add_cart_single&product_id=' + product_id,    
            success : function(results){
                $('#'+product_id).replaceWith(results);
            }
        }).done(function(){
            var cart = $('#cart-'+product_id).val();
            $('.cart .cart-count').html(cart);         
        });
    });
     
});