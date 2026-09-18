jQuery(function($) {
    "use strict";

    var gamba = window.gamba || {};

    /*=======================================
    =             MAIN FUNCTION             =
    =======================================*/

    gamba.headerFunction = function() {
        //js for menu PC
        // Add class fixed for menu when scroll
        var window_height = $(window).height();

        if (!$('.bg-transparent').hasClass('home')) {
            window_height = window_height / $('.bg-transparent').height();
        }

        $(window).on('scroll load', function(event) {
            if (($(window).height() + 400) < $(document).height()) {
                if ($(window).scrollTop() > 1) {
                    $(".header-main").addClass('header-fixed');
                } else {
                    $(".header-main").removeClass('header-fixed');
                    $(".header-main").removeClass('hide-menu');
                }
            }
        });

        // Show menu when scroll up, hide menu when scroll down
        // var lastScroll = 50;
        // $(window).on('scroll load', function (event) {
        //     var st = $(this).scrollTop();
        //     if (st > lastScroll) {
        //         $('.header-main').addClass('hide-menu');
        //         if ($('.nav-search').hasClass('hide') === false) {
        //             $('.nav-search').toggleClass('hide');
        //         }
        //     }
        //     else if (st < lastScroll) {
        //         $('.header-main').removeClass('hide-menu');
        //     }

        //     if ($(window).scrollTop() <= 200 ){
        //         $('.header-main').removeClass('.header-fixed').removeClass('hide-menu');
        //     }
        //     else if ($(window).scrollTop() < window_height && $(window).scrollTop() > 0) {
        //         $('.header-main').addClass('hide-menu');
        //     }
        //     lastScroll = st;
        // });


        // Show - hide box search on menu
        $('.button-search').on('click', function() {
            $('.nav-search').toggleClass('hide');
        });

        //hide box seach when click outside
        $('body').on('click', function(event) {
            if ($('.button-search').has(event.target).length === 0 && !$('.button-search').is(event.target) && $('.nav-search').has(event.target).length === 0 && !$('.nav-search').is(event.target)) {
                if ($('.nav-search').hasClass('hide') === false) {
                    $('.nav-search').toggleClass('hide');
                }
            }
        });

        // Menu Mobile
        $(".wrapper-menu-mobile").css("min-height", $(window).height());
        $(".wrapper-search-mobile").css("min-height", $(window).height());

        // show menu
        $(".hamburger-menu-mobile").on("click", function() {
            $('body').addClass("open-menu-mobile");
        });
        $(".mb-button-close").on("click", function() {
            $('body').removeClass("open-menu-mobile");
        });

        //show search
        $(".button-search-mobile").on("click", function() {
            $('body').addClass("open-search-mobile");
        });
        $(".mb-button-close").on("click", function() {
            $('body').removeClass("open-search-mobile");
        });


        // show hide dropdown menu
        $('.mb-nav>.dropdown>.icons-dropdown').on('click', function() {
            if ($(this).parents('.dropdown').hasClass('mb-menu-dropdown-open') === true) {
                $(this).parents('.dropdown').removeClass('mb-menu-dropdown-open');
            } else {
                $('.mb-nav .dropdown').removeClass('mb-menu-dropdown-open');
                $(this).parents('.dropdown').addClass('mb-menu-dropdown-open');
            }
        });
        $('.dropdown-2 .icons-dropdown').on('click', function() {
            $(this).parents('.dropdown-2').toggleClass('mb-menu-dropdown-open');
        });
    };

    gamba.mainFunction = function() {

        // ----------------------- WOW-JS --------------------------- //
        new WOW().init();

        // ----------------------- COUNT TO --------------------------- //
        if ($(".count-number").length) {
            $('.counter-item').appear(function() {
                setTimeout(function() {
                    $('.counter-item .count').countTo();
                }, 300);
            });
        }

        // ----------------------- BACK TOP --------------------------- //
        $('#gb-back-top .link').on('click', function() {
            $('body,html').animate({
                scrollTop: 0
            }, 900);
            return false;
        });

        var temp = $(window).height();
        $(window).on('scroll load', function(event) {
            if ($(window).scrollTop() > temp) {
                $('#gb-back-top .link').addClass('show-btn');
            } else {
                $('#gb-back-top .link').removeClass('show-btn');
            }
        });

        // ----------------------- Play videos --------------------------- //
        // JS for section Videos bg
        if ($('.video-thumbnail').length) {
            var gurl = $(".video-embed")[0].src;
            $(".video-button-play ").on('click', function(event) {
                $(".video-embed").addClass('show-video');
                $(".video-button-close").addClass('show-video');
                $(".video-embed")[0].src += "&autoplay=1";
                event.preventDefault();
            });

            $(".video-button-close").on('click', function(event) {
                $(".video-embed")[0].src = gurl;
                $(".video-embed").removeClass('show-video');
                $(".video-button-close").removeClass('show-video');
            });
        };

        // ----------------------- Effect for blogs --------------------------- //
        $('.gb-blog .image-wrapper .link').directionalHover();

        $('.about-us-wrapper-2 .image-wrapper .link').directionalHover();


        // ----------------------- List logo --------------------------- //
        $('.slider-logo-wrapper').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 500,
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 381,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });


        // ----------------------- List event --------------------------- //
        $('.list-event').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 500,
            dots: true,
            arrows: false,
            autoplay: true,
            vertical: true,
            verticalSwiping: true,
            customPaging: function(slider, i) {
                var i = i + 1;
                return '<button>' + '0' + i + '</button>';
            }
        });

        // ----------------------- SHOW GALLERY --------------------------- //
        if ($(".fancybox").length) {
            $(".fancybox").fancybox({
                helpers: {
                    thumbs: {
                        width: 50,
                        height: 50
                    },
                    overlay: {
                        locked: false
                    }
                }
            });

            if ($(window).width() > 600) {
                $.fancybox.helpers.thumbs.onUpdate = function(opts, obj) {
                    if (this.list) {
                        var center = Math.floor($(window).width() * 0.5 - (obj.group.length / 2 * this.width + this.width * 0.5));
                        this.list.css('left', center);
                    }
                };
            }
        }


        // ----------------------- SHOW COMMENT --------------------------- //
        $('div[class*="merge"]').css("display", "none");
        $('.reply-1').click(function(event) {
            $('.merge-1').toggle(300);
        });
        $('.reply-2').click(function(event) {
            $('.merge-2').toggle(300);
        });

        // ----------------------- SHOW COMMENT --------------------------- //
        if ($(".ladding-wrapper .gamba-about").length) {
            setTimeout(function() {
                $(".gamba-about .gb-wrapper-content .title").typed({
                    strings: ["Gamba bakery, cakery, pizza, pastry shop"],
                    typeSpeed: 10,
                    backDelay: 0,
                    loop: false,
                });
            }, 300);
        }

    };

    gamba.datepick = function() {
        // js for calendar
        $('.input-daterange, .archive-datepicker').datepicker({
            format: 'mm/dd/yy',
            maxViewMode: 0
        });

        // js for time
        $('.times-open').timepicker({
            'scrollDefault': 'now'
        });
    };

    /*======================================
    =            INIT FUNCTIONS            =
    ======================================*/

    $(document).ready(function() {
        gamba.headerFunction();
        gamba.mainFunction();
        gamba.datepick();

    });

    /*=====  End of INIT FUNCTIONS  ======*/

    $(window).on('load', function() {});

});