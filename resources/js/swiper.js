import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

const swiper = (carousel) => {

    //* Checking for basic parameters and setting defaults if not setup
    //? These are just the defaults, you can add more if need be
    const loop = carousel.getAttribute('swiper-loop') || false;
    const autoHeight = carousel.getAttribute('swiper-autoHeight') || false;
    const freeMode = carousel.getAttribute('swiper-freeMode') || false;
    const initialSlide = carousel.getAttribute('swiper-initialSlide') || 0;
    const slidesPerView = carousel.getAttribute('swiper-slidesPerView') || 1;
    const spaceBetween = carousel.getAttribute('swiper-spaceBetween') || 0;
    const speed = carousel.getAttribute('swiper-speed') || 500;
    const allowTouchMove = carousel.getAttribute('swiper-allowTouchMove') || false;
    const centeredSlides = carousel.getAttribute('swiper-centeredSlides') || false;

    //! Using vertical sliders requires either .swiper or its outer container to have a fixed height
    const direction = carousel.getAttribute('swiper-direction') || 'horizontal';

    
    //* Set the parameters in an object ready for Swiper
    const swiperOptions = {
        loop, slidesPerView, spaceBetween, speed, allowTouchMove, centeredSlides, direction, initialSlide, freeMode, autoHeight,
        modules: [],
    };

    if ( carousel.classList.contains('fade') ) { 
        swiperOptions.effect = 'fade',
        swiperOptions.modules = [...swiperOptions.modules, EffectFade ];
    }

    //* If navigation is enabled as a class then initialise navigation
    if ( carousel.classList.contains('navigation') ) {
    
        //* Navigation declaration: These should match whatever you have in the HTML
        const navigation = {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
            enabled: true
        };

        swiperOptions.navigation = navigation;
        swiperOptions.modules = [
            ...swiperOptions.modules,
            Navigation
        ]

    }

    //* If pagination is enabled as a class then initialise navigation
    if ( carousel.classList.contains('pagination') ) {
    
        //* Pagination declaration: These should match whatever you have in the HTML
        const pagination = {
            el: ".swiper-pagination",
            clickable: true,
        };

        swiperOptions.pagination = pagination;
        swiperOptions.modules = [
            ...swiperOptions.modules,
            Pagination
        ]

    }
    
    //* If autoplay is enabled as a class then initialise autoplay
    if ( carousel.classList.contains('autoplay') ) {
        
        //* Set additional Autoplay paramenters
        const delay = carousel.getAttribute('swiper-autoplay-delay') || 0;
        const disableOnInteraction = carousel.getAttribute('swiper-autoplay-disableInteraction') || false;
        const pauseOnMouseEnter = carousel.getAttribute('swiper-autoplay-pauseOnMouseEnter') || false;
        
        // 
        swiperOptions.autoplay = {
            delay, disableOnInteraction, pauseOnMouseEnter
        },

        //* Add the Autoplay module to the existsing modules array in the swiperOptions object
        swiperOptions.modules = [ ...swiperOptions.modules, Autoplay ];
    }

    //* Check if there are any breakpoints and 
    //* If so, add them to the breakpoints object in swiperOptions
    if (carousel.getAttribute('swiper-slidesPerView-sm')) {
        swiperOptions.breakpoints = {
            ...swiperOptions.breakpoints,
            375: {
                slidesPerView: carousel.getAttribute('swiper-slidesPerView-sm'),
            }
        }
    }
    if (carousel.getAttribute('swiper-slidesPerView-md')) {
        swiperOptions.breakpoints = {
            ...swiperOptions.breakpoints,
            576: {
                slidesPerView: carousel.getAttribute('swiper-slidesPerView-md'),
            }
        }
    }
    if (carousel.getAttribute('swiper-slidesPerView-lg')) {
        swiperOptions.breakpoints = {
            ...swiperOptions.breakpoints,
            768: {
                slidesPerView: carousel.getAttribute('swiper-slidesPerView-lg'),
            }
        }
    }
    if (carousel.getAttribute('swiper-slidesPerView-xl')) {
        swiperOptions.breakpoints = {
            ...swiperOptions.breakpoints,
            992: {
                slidesPerView: carousel.getAttribute('swiper-slidesPerView-xl'),
            }
        }
    }
    if (carousel.getAttribute('swiper-slidesPerView-xxl')) {
        swiperOptions.breakpoints = {
            ...swiperOptions.breakpoints,
            1200: {
                slidesPerView: carousel.getAttribute('swiper-slidesPerView-xxl'),
            }
        }
    }

    return new Swiper(carousel, swiperOptions);
}

const initSwiper = (swipers) => {
    for (let i = 0; i < swipers.length; i++) {
        swiper(swipers[i]);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    initSwiper(document.body.querySelectorAll('.swiper'));
});