/**
 * Neboostla Page Transitions & Sidebar Hover Effects
 * Portal-style page transition (close only) using anime.js
 * Sidebar icon hover expansion when collapsed
 */
(function () {
    'use strict';

    // ═══════════════════════════════════════════════════════════════════
    //  PAGE TRANSITION — Portal Close Only
    // ═══════════════════════════════════════════════════════════════════

    var isTransitioning = false;

    function injectTransitionCSS() {
        var style = document.createElement('style');
        style.textContent = [
            '#page-transition-overlay{position:fixed;inset:0;z-index:9999;pointer-events:none;overflow:hidden}',
            '#portal-panel{position:absolute;inset:0;background:#0b1120;will-change:transform;transform:translateX(-100%)}',
            '#portal-glow{position:absolute;top:0;right:-3px;width:6px;height:100%;',
            '  background:linear-gradient(180deg,rgba(139,92,246,0.8),rgba(114,220,255,1),rgba(59,130,246,0.9),rgba(139,92,246,0.8));',
            '  box-shadow:0 0 20px rgba(114,220,255,0.8),0 0 60px rgba(114,220,255,0.4),0 0 100px rgba(139,92,246,0.3);',
            '  opacity:0;will-change:opacity}',
            '#portal-sparks{position:absolute;inset:0;overflow:hidden;pointer-events:none}',
        ].join('\n');
        document.head.appendChild(style);
    }

    function createOverlay() {
        injectTransitionCSS();

        var overlay = document.createElement('div');
        overlay.id = 'page-transition-overlay';

        var panel = document.createElement('div');
        panel.id = 'portal-panel';

        var glow = document.createElement('div');
        glow.id = 'portal-glow';

        var sparks = document.createElement('div');
        sparks.id = 'portal-sparks';

        panel.appendChild(glow);
        panel.appendChild(sparks);
        overlay.appendChild(panel);
        document.body.appendChild(overlay);
    }

    function resetPortal() {
        var panel = document.getElementById('portal-panel');
        var glow = document.getElementById('portal-glow');
        var overlay = document.getElementById('page-transition-overlay');
        if (panel) panel.style.transform = 'translateX(-100%)';
        if (glow) glow.style.opacity = '0';
        if (overlay) overlay.style.pointerEvents = 'none';
        isTransitioning = false;

        // Clean sparks
        var sparksContainer = document.getElementById('portal-sparks');
        if (sparksContainer) sparksContainer.innerHTML = '';
    }

    function spawnSparks() {
        var container = document.getElementById('portal-sparks');
        if (!container) return;
        container.innerHTML = '';

        var colors = [
            'rgba(114,220,255,0.9)',
            'rgba(139,92,246,0.8)',
            'rgba(255,255,255,0.9)',
            'rgba(59,130,246,0.8)',
        ];

        for (var i = 0; i < 12; i++) {
            var spark = document.createElement('div');
            var size = Math.random() * 4 + 2;
            spark.style.cssText = [
                'position:absolute',
                'width:' + size + 'px',
                'height:' + size + 'px',
                'border-radius:50%',
                'background:' + colors[i % colors.length],
                'box-shadow:0 0 ' + (size * 3) + 'px ' + colors[i % colors.length],
                'right:0',
                'top:' + (Math.random() * 100) + '%',
                'opacity:0',
            ].join(';');
            container.appendChild(spark);
        }
    }

    function animateEnter(callback) {
        var panel = document.getElementById('portal-panel');
        var glow = document.getElementById('portal-glow');
        var overlay = document.getElementById('page-transition-overlay');
        if (!panel || !glow || !overlay) {
            if (callback) callback();
            return;
        }

        overlay.style.pointerEvents = 'all';
        panel.style.transform = 'translateX(-100%)';
        glow.style.right = '-3px';
        glow.style.left = 'auto';

        spawnSparks();
        var sparks = document.querySelectorAll('#portal-sparks div');

        anime({ targets: glow, opacity: [0, 1], duration: 150, easing: 'easeOutQuad' });

        anime({
            targets: sparks,
            translateX: function () { return anime.random(-180, -30); },
            translateY: function () { return anime.random(-30, 30); },
            opacity: [{ value: 1, duration: 100 }, { value: 0, duration: 400 }],
            scale: [1, 0],
            duration: 500,
            delay: anime.stagger(25),
            easing: 'easeOutQuad',
        });

        anime({
            targets: panel,
            translateX: ['-100%', '0%'],
            duration: 500,
            easing: 'easeInOutQuart',
            complete: function () {
                if (callback) callback();
            },
        });
    }

    function shouldIntercept(link) {
        if (!link || !link.href) return false;
        if (link.target === '_blank') return false;
        if (link.hasAttribute('download')) return false;
        if (link.href.startsWith('javascript:')) return false;
        if (link.href.startsWith('#')) return false;
        if (link.href === window.location.href) return false;
        try {
            var url = new URL(link.href);
            if (url.origin !== window.location.origin) return false;
        } catch (e) { return false; }
        if (link.closest('form')) return false;
        return true;
    }

    function initPageTransitions() {
        createOverlay();

        // Intercept link clicks
        document.addEventListener('click', function (e) {
            var link = e.target.closest('a');
            if (!link || !shouldIntercept(link)) return;
            if (isTransitioning) { e.preventDefault(); return; }

            e.preventDefault();
            isTransitioning = true;

            animateEnter(function () {
                window.location.href = link.href;
            });
        });

        // CRITICAL: Handle bfcache (back/forward) and normal page loads.
        // When the browser restores a page from cache, the portal panel
        // may still be covering the screen. This resets it immediately.
        window.addEventListener('pageshow', function (e) {
            resetPortal();
        });

        // Also reset on initial load (in case of any stuck state)
        resetPortal();
    }

    // ═══════════════════════════════════════════════════════════════════
    //  SIDEBAR HOVER EXPANSION (when collapsed)
    // ═══════════════════════════════════════════════════════════════════

    var activeHoverAnimations = new Map();

    function isSidebarCollapsed() {
        return document.documentElement.classList.contains('sidebar-is-collapsed');
    }

    function initSidebarHover() {
        var sidebar = document.querySelector('aside');
        if (!sidebar) return;

        sidebar.addEventListener('mouseenter', function (e) {
            var item = e.target.closest('.sidebar-item, .sidebar-item-logout');
            if (!item || !isSidebarCollapsed()) return;

            if (activeHoverAnimations.has(item)) activeHoverAnimations.get(item).pause();

            var icon = item.querySelector('.material-icons, i');
            var anim = anime.timeline({ autoplay: true });
            anim.add({ targets: item, translateX: [0, 6], duration: 250, easing: 'easeOutCubic' }, 0);
            if (icon) anim.add({ targets: icon, scale: [1, 1.2], duration: 250, easing: 'easeOutCubic' }, 0);
            activeHoverAnimations.set(item, anim);
        }, true);

        sidebar.addEventListener('mouseleave', function (e) {
            var item = e.target.closest('.sidebar-item, .sidebar-item-logout');
            if (!item) return;

            if (activeHoverAnimations.has(item)) activeHoverAnimations.get(item).pause();

            var icon = item.querySelector('.material-icons, i');
            var anim = anime.timeline({ autoplay: true });
            anim.add({ targets: item, translateX: [6, 0], duration: 300, easing: 'easeOutCubic' }, 0);
            if (icon) anim.add({ targets: icon, scale: [1.2, 1], duration: 300, easing: 'easeOutCubic' }, 0);
            activeHoverAnimations.set(item, anim);
        }, true);
    }

    // ═══════════════════════════════════════════════════════════════════
    //  INIT
    // ═══════════════════════════════════════════════════════════════════

    function init() {
        if (typeof anime === 'undefined') {
            setTimeout(init, 100);
            return;
        }
        initPageTransitions();
        initSidebarHover();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
