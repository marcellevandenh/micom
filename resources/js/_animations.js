// import anime from 'animejs/lib/anime.es.js';

// Helpers 

const windowHt = window.innerHeight;
const windowWd = window.innerWidth;

const scrollPercent = function (container, speed = 0, offset = 0) {
    let scrollPercent = window.pageYOffset - container.offsetTop + offset;
    if (speed) scrollPercent /= speed;
    return scrollPercent;
};


// Reveal Animation

const scrollAnim = (reveal) => {
    reveal.forEach(element => {
        let trigger = windowHt / (element.getAttribute("reveal-trigger") ? parseInt(element.getAttribute("reveal-trigger")) : 1.2);
        let elPos = element.getBoundingClientRect().top;

        if (elPos < trigger) element.classList.add('revealed');
    });
};

// Scroll to Top

const scrollTop = (scrollTop) => {
    if( scrollTop.length > 0 ) {
        if( scrollPercent(document.querySelector('body')) > 1500 ) {
            scrollTop[0].style.opacity='1';
        } else {
            scrollTop[0].style.opacity='0';
        }
    }
}


// Anims Init

window.addEventListener("DOMContentLoaded", () => {
    scrollAnim(document.querySelectorAll(".reveal"));
    scrollTop(document.querySelectorAll('.back-to-top'));

    window.addEventListener('scroll', () => {
        scrollAnim(document.querySelectorAll(".reveal"));
        scrollTop(document.querySelectorAll('.back-to-top'));
    }, {passive:true});
});
