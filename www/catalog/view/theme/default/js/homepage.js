jQuery(function($) {
    "use strict";

    var gamba = window.gamba || {};

    /*=======================================
    =             MAIN FUNCTION             =
    =======================================*/

    gamba.mainFunction = function() {
        // slide homepage - background slide
        $('.gb-background-slide').slick({
            dots: true,
            arrows: false,
            speed: 700,
            fade: true,
            autoplay: true,
            autoplaySpeed: 7000,
            cssEase: 'linear',
            pauseOnHover: false
        });

        $('.gb-background-slide').on('afterChange', function(event, slick, currentSlide){
            $('.slick-active  .group-title').addClass('animated fadeInDown');
            $('.slick-active  .btn-left').addClass('animated fadeInLeft');
            $('.slick-active  .btn-right').addClass('animated fadeInRight');

            $('.slick-active  .group-title').removeClass('hidden');
            $('.slick-active  .btn-left').removeClass('hidden');
            $('.slick-active  .btn-right').removeClass('hidden');
        });

        $('.gb-background-slide').on('beforeChange', function(event, slick, currentSlide){
            $('.slick-active  .group-title').removeClass('animated fadeInDown');
            $('.slick-active  .btn-left').removeClass('animated fadeInLeft');
            $('.slick-active  .btn-right').removeClass('animated fadeInRight');

            $('.slick-active  .group-title').addClass('hidden');
            $('.slick-active  .btn-left').addClass('hidden');
            $('.slick-active  .btn-right').addClass('hidden');
        });

        $('.list-products-carousel').slick({
            dots: false,
            arrows: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            pauseOnHover: false,
            prevArrow:'<button class="btn btn-prev"><i class="fa fa-angle-left"><i></button>',
            nextArrow:'<button class="btn btn-next"><i class="fa fa-angle-right"><i></button>',
            responsive: [
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        dots: true,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: true,
                        arrows: false,
                    }
                }
            ]
        });

        $('.team-list').slick({
            dots: false,
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            infinite: true,
            pauseOnHover: false,
            responsive: [
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        $('.list-testimonial').slick({
            dots: true,
            arrows: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            infinite: true,
            pauseOnHover: false,
        });

        $('.list-testimonial-2').slick({
            dots: true,
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            infinite: true,
            pauseOnHover: false,
            responsive: [
                {
                    breakpoint: 601,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });


        $('.list-service').slick({
            dots: false,
            arrows: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            pauseOnHover: false,
            prevArrow:'<button class="btn btn-prev"><i class="fa fa-angle-left"><i></button>',
            nextArrow:'<button class="btn btn-next"><i class="fa fa-angle-right"><i></button>',
            responsive: [
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        dots: true,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: true,
                        arrows: false,
                    }
                }
            ]
        });

        // Effect hover
        $('.team-list .gb-team-1 .team-image').directionalHover();
        $('.gb-about-post .about-image .link').directionalHover();
    };

   

    /*======================================
    =            INIT FUNCTIONS            =
    ======================================*/

    $(document).ready(function() {
        gamba.mainFunction();
    });

    /*=====  End of INIT FUNCTIONS  ======*/

    $(window).on('load', function() {
    });

});