(function($) {
    $(document).ready(function() {
        // -------------sticky------------
        $('.navigation-area').sticky({
            topSpacing: 0,
        });
        // -------------slicknav------------

        $('#main-menu').slicknav({
            label: '',
            prependTo: '.navigation-area .container',
            closeOnClick: true,
        });

        // -----------progress-bar------

        $(".progress-bar-1").animate({
            width: "88%"
        }, 2500);
        $(".progress-bar-2").animate({
            width: "95%"
        }, 2700);
        $(".progress-bar-3").animate({
            width: "78%"
        }, 2900);
        $(".progress-bar-4").animate({
            width: "67%"
        }, 3100);
        // -----------------services-owl-carousel---------
        $('.services-carousel').owlCarousel({
            items: 3,
            loop: false,
            autoplay: false,
            autoplayHoverPause: true,
            nav: false,
            dots: true,
            autoplaySpeed: 3000,
            smartSpeed: 2000,
            Speed: 500,
            responsive: {
                0: {
                    items: 1,
                    dots: false,
                    margin: 10,
                },
                479: {
                    items: 1,
                    dots: false,
                    margin: 10,
                },
                701: {
                    items: 2,
                    dots: false,
                },
                992: {
                    items: 3,
                }
            }
        });

        // -----------------latest-news-owl-carousel---------
        $('.latest-news-carousel').owlCarousel({
            items: 3,
            loop: true,
            autoplay: true,
            autoplayHoverPause: true,
            nav: true,
            dots: false,
            margin: 30,
            autoplaySpeed: 3000,
            smartSpeed: 2000,
            Speed: 500,
            loop: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 15,
                    dots: false,
                },
                479: {
                    items: 1,
                    margin: 15,
                    dots: false,
                },
                701: {
                    items: 2,
                    dots: false,
                },
                992: {
                    items: 3,
                }
            }
        });
        // -----------------testimonial-owl-carousel---------

        $('.testimonial-carousel').owlCarousel({
            items: 3,
            loop: true,
            autoplay: true,
            autoplayHoverPause: true,
            nav: false,
            dots: true,
            margin: 0,
            autoplaySpeed: 3000,
            smartSpeed: 2000,
            Speed: 500,
            responsive: {
                0: {
                    items: 1,
                    dots: false,
                },
                479: {
                    items: 1,
                    dots: false,
                },
                701: {
                    items: 2,
                    dots: false,
                },
                992: {
                    items: 3,
                }
            }
        });

    });

    // -----------------wow-for-animation---------
    var wow = new WOW({
    });
    wow.init();

    /*---------------preloader----------------------*/
     setTimeout(function(){
        $('.loader-bg').fadeToggle();
    }, 1000);


}(jQuery));