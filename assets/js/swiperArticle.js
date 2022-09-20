import Swiper from 'swiper/bundle';
import 'swiper/css';
import 'swiper/css/pagination';

const swiper = new Swiper('.swiper-image', {
    direction: 'horizontal',
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    grabCursor: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    effect: 'creative',
    creativeEffect: {
        prev: {
            shadow: true,
            translate: [0, 0, -400]
        },
        next: {
            translate: ["100%", 0, 0]
        }
    }
});