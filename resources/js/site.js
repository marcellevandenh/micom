import './_animations';

// Header 

const initHeader = (header) => {
    if (header) {
        const burger = header.querySelector('.burger');
        const menu = header.querySelector('.menu');

        if (burger && menu) {
            const setMenuState = (isOpen) => {
                document.body.classList.toggle('no-scroll', isOpen);
                burger.classList.toggle('open', isOpen);
                menu.classList.toggle('active', isOpen);
                burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                burger.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
            };

            burger.addEventListener('click', () => {
                setMenuState(!menu.classList.contains('active'));
            });

            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => setMenuState(false));
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && menu.classList.contains('active')) {
                    setMenuState(false);
                    burger.focus();
                }
            });
        }
    }
}


// Form Submit && Captcha

var forms = document.querySelectorAll('form');
var loadCaptcha = function loadCaptcha(e) {
    forms.forEach(function (form) {
        setTimeout(function () {
            form.classList.add('captcha-loaded');
        }, 500);
        form.removeEventListener('input', loadCaptcha);
    });
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    // script.src= 'https://challenges.cloudflare.com/turnstile/v0/api.js'
    script.src = 'https://www.google.com/recaptcha/api.js';
    head.appendChild(script);
};

const initForms = (forms) => {
    if (forms) {
        forms.forEach(form => {
            let submitted = false;
            form.addEventListener('submit', (e) => {
                submitted ? e.preventDefault() : '';
            });
            form.addEventListener('input', loadCaptcha);

            const fileUploads = form.querySelectorAll('.file-upload');
            fileUploads.forEach(label => {
                const input = label.querySelector('input');
                const handle = label.getAttribute('data-for');
                const name  = form.querySelector(`#${handle}-name`);

                input.addEventListener('change', () => {
                    const files = input.files;
                    let names = []

                    for (let i = 0; i < files.length; ++i) {
                        names.push(files[i].name);
                    }

                    name.innerHTML = names.toString().replace(',', ', ');
                })
            })
        })
    }
}

// Accordion 

const initAccordions = (accordions) => {
    if (accordions) {
        accordions.forEach(accordion => {
            const container = accordion.parentElement.parentElement;
            const accordionGroup = container.querySelectorAll('.accordion') ? container.querySelectorAll('.accordion') : 0;
            const wrapper = accordion.parentElement;

            
            accordion.addEventListener("click", function() {
                console.log('Parent', accordion.parentElement.parentElement);
                if (container.classList.contains('accordion-container') && !wrapper.classList.contains('open')) {
                    accordionGroup.forEach(accordion => {
                        accordion.parentElement.classList.remove("open");
                    })
                }
                accordion.parentElement.parentElement.parentElement.classList.remove('open');
                wrapper.classList.toggle("open"); 
            });
        })
    }
}

const copyText = () => {
    if( document.getElementById('copyText') ) {
        document.getElementById('copyText').addEventListener('click', () => {
            const text = document.getElementById('current_url');
            navigator.clipboard.writeText(text.value);

            document.getElementById('copied').style.opacity = '100';
            setTimeout(function() {
                document.getElementById('copied').style.opacity = '0';
            }, 1500);
        });
    }
}

const initJobToTradeWidget = () => {
    const trigger = document.querySelector('.job-to-trade-widget');
    const dialog = document.querySelector('.job-to-trade-dialog');
    const closeButton = dialog?.querySelector('.job-to-trade-dialog__close');

    if (!trigger || !dialog || !closeButton) {
        return;
    }

    const closeDialog = (shouldFocusTrigger = false) => {
        if (dialog.open) {
            dialog.close();
        }

        trigger.setAttribute('aria-expanded', 'false');

        if (shouldFocusTrigger) {
            trigger.focus();
        }
    };

    trigger.addEventListener('click', () => {
        if (dialog.open) {
            closeDialog();
            return;
        }

        dialog.show();
        trigger.setAttribute('aria-expanded', 'true');
        closeButton.focus();
    });

    closeButton.addEventListener('click', () => closeDialog(true));
    dialog.addEventListener('close', () => trigger.setAttribute('aria-expanded', 'false'));

    document.addEventListener('click', (event) => {
        if (dialog.open && !dialog.contains(event.target) && !trigger.contains(event.target)) {
            closeDialog();
        }
    });
};

window.addEventListener('DOMContentLoaded', function () {
    initHeader(document.querySelector('header'));
    initForms(document.querySelectorAll('form'));
    initAccordions(document.querySelectorAll(".accordion"));
    copyText(document.querySelector('#copyText'));
    try {
        initJobToTradeWidget();
    } catch (error) {
        console.error('Unable to initialise the JobToTrade widget.', error);
    }
});
