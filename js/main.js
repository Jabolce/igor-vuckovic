/**
 * Igor Vukovic Theme – Main JavaScript
 */
(function () {
    'use strict';

    // =========================================================
    // STICKY HEADER — adds .scrolled class after 20px
    // =========================================================
    const header = document.getElementById('site-header');
    if (header) {
        const onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 20);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // run once on load
    }

    // =========================================================
    // HAMBURGER MENU
    // =========================================================
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileNav    = document.getElementById('mobile-nav');

    function openMobileMenu() {
        if (!hamburgerBtn || !mobileNav) return;
        hamburgerBtn.classList.add('is-open');
        hamburgerBtn.setAttribute('aria-expanded', 'true');
        mobileNav.classList.add('is-open');
        mobileNav.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        if (!hamburgerBtn || !mobileNav) return;
        hamburgerBtn.classList.remove('is-open');
        hamburgerBtn.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        mobileNav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function () {
            if (mobileNav.classList.contains('is-open')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    // Close mobile menu when a link is clicked
    if (mobileNav) {
        mobileNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    // ESC key closes mobile menu
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // =========================================================
    // VIDEO LIGHTBOX
    // =========================================================
    const lightbox       = document.getElementById('lightbox');
    const lightboxIframe = document.getElementById('lightbox-iframe');
    const lightboxClose  = document.getElementById('lightbox-close');

    function openLightbox(embedUrl) {
        if (!lightbox || !embedUrl) return;
        
        lightboxIframe.src = embedUrl;
        
        // Auto-correct aspect ratio bounds for natively 4:3 videos
        if (embedUrl.indexOf('489946032') !== -1) {
            document.querySelector('.lightbox-ratio').style.aspectRatio = '4 / 3';
        } else {
            document.querySelector('.lightbox-ratio').style.aspectRatio = '16 / 9'; // Default fallback
        }
        
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';

        // Dynamically adjust ratio if Vimeo API is available
        if (typeof Vimeo !== 'undefined') {
            var player = new Vimeo.Player(lightboxIframe);
            player.ready().then(function() {
                Promise.all([player.getVideoWidth(), player.getVideoHeight()]).then(function(dimensions) {
                    var w = dimensions[0];
                    var h = dimensions[1];
                    if (w && h) {
                        document.querySelector('.lightbox-ratio').style.aspectRatio = w + ' / ' + h;
                    }
                }).catch(function(e) { /* ignore */ });
            });
        }
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('is-open');
        lightboxIframe.src = '';
        document.body.style.overflow = '';
    }

    // Portfolio items navigate via their <a> link (see front-page.php)

    // Close button
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    // Click outside lightbox inner
    if (lightbox) {
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });
    }

    // ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLightbox();
    });

    // =========================================================
    // ACTIVE NAV HIGHLIGHT
    // =========================================================
    const currentPath = window.location.pathname;
    const navLinks    = document.querySelectorAll('#primary-nav a[data-cat]');

    navLinks.forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && currentPath.indexOf(link.dataset.cat) !== -1) {
            link.closest('li').classList.add('active');
        }
    });

    // =========================================================
    // STAGGERED GRID ANIMATION
    // =========================================================
    const gridItems = document.querySelectorAll('.portfolio-item');

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry, i) {
                if (entry.isIntersecting) {
                    setTimeout(function () {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, i * 70);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05 });

        gridItems.forEach(function (item) {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
            obs.observe(item);
        });
    }

    // =========================================================
    // CINEMA BLACKOUT — scroll-triggered dark surround for video
    // Three conditions required: hero visible + scrolled + credits NOT yet in view
    // =========================================================
    (function () {
        if (!document.body.classList.contains('single-project')) return;

        var hero    = document.querySelector('.project-hero');
        var credits = document.querySelector('.project-content');
        if (!hero) return;

        var heroVisible    = false;
        var userScrolled   = false;
        var creditsVisible = false;   // turns OFF effect when credits scroll in

        function updateCinema() {
            if (heroVisible && userScrolled && !creditsVisible) {
                document.body.classList.add('cinema-mode');
            } else {
                document.body.classList.remove('cinema-mode');
            }
        }

        // Only activate after user has scrolled past the header (~80px)
        window.addEventListener('scroll', function () {
            userScrolled = window.scrollY > 80;
            updateCinema();
        }, { passive: true });

        // Watch the hero entering / leaving the viewport
        var heroObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                heroVisible = entry.isIntersecting;
                updateCinema();
            });
        }, { threshold: 0.25 });

        heroObs.observe(hero);

        // Watch the credits — as soon as they peek in, turn cinema off
        if (credits) {
            var creditsObs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    creditsVisible = entry.isIntersecting;
                    updateCinema();
                });
            }, { threshold: 0.05 });   // 5% of credits visible = lights back on

            creditsObs.observe(credits);
        }
    })();

})();
