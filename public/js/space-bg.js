/**
 * Neboostla Space Background Animations
 * Pure vanilla JS + CSS animations — no external dependencies
 * Nebula-themed: purple, blue, white, yellow
 */
(function () {
    'use strict';

    // ─── Configuration ───────────────────────────────────────────────
    var STAR_COUNT = 120;
    var METEOR_MIN_INTERVAL = 3000;
    var METEOR_MAX_INTERVAL = 8000;

    // Size tiers with weights
    var SIZE_TIERS = [
        { weight: 50, min: 1,   max: 2   },
        { weight: 25, min: 2,   max: 3.5 },
        { weight: 13, min: 3.5, max: 5   },
        { weight: 8,  min: 5,   max: 8   },
        { weight: 4,  min: 8,   max: 13  },
    ];

    // Nebula color palette
    var COLORS = [
        // White
        'rgba(255,255,255,1)', 'rgba(255,255,255,0.95)', 'rgba(240,245,255,0.9)',
        // Blue / Cyan
        'rgba(114,220,255,0.95)', 'rgba(59,130,246,0.9)', 'rgba(96,165,250,0.9)',
        'rgba(56,189,248,0.95)',
        // Purple / Violet
        'rgba(139,92,246,0.9)', 'rgba(167,139,250,0.9)', 'rgba(196,181,253,0.85)',
        'rgba(124,58,237,0.9)', 'rgba(192,132,252,0.9)',
        // Yellow / Gold
        'rgba(253,224,71,0.9)', 'rgba(250,204,21,0.85)', 'rgba(251,191,36,0.9)',
        'rgba(254,240,138,0.8)',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────
    function rand(min, max) { return Math.random() * (max - min) + min; }
    function randInt(min, max) { return Math.floor(rand(min, max + 1)); }
    function pick(arr) { return arr[randInt(0, arr.length - 1)]; }

    function pickSize() {
        var total = 0;
        for (var i = 0; i < SIZE_TIERS.length; i++) total += SIZE_TIERS[i].weight;
        var r = Math.random() * total, cum = 0;
        for (var j = 0; j < SIZE_TIERS.length; j++) {
            cum += SIZE_TIERS[j].weight;
            if (r <= cum) return rand(SIZE_TIERS[j].min, SIZE_TIERS[j].max);
        }
        return rand(1, 2);
    }

    // ─── Inject CSS Keyframes ────────────────────────────────────────
    function injectStyles() {
        var style = document.createElement('style');
        style.textContent = [
            // We create 8 different twinkle keyframe variants
            // so stars don't all animate identically
            '@keyframes twinkle1{0%,100%{opacity:0.1}50%{opacity:1}}',
            '@keyframes twinkle2{0%,100%{opacity:0.15}50%{opacity:0.85}}',
            '@keyframes twinkle3{0%,100%{opacity:0.05}50%{opacity:0.95}}',
            '@keyframes twinkle4{0%,100%{opacity:0.2}50%{opacity:0.75}}',
            '@keyframes twinkle5{0%,100%{opacity:0.3}50%{opacity:1}}',
            '@keyframes twinkle6{0%,100%{opacity:0.08}50%{opacity:0.9}}',
            '@keyframes twinkle7{0%,100%{opacity:0.12}50%{opacity:0.7}}',
            '@keyframes twinkle8{0%,100%{opacity:0.25}50%{opacity:0.95}}',

            '@keyframes meteorFly{' +
                '0%{transform:translate(0,0) rotate(var(--angle));opacity:0}' +
                '5%{opacity:1}' +
                '100%{transform:translate(calc(-100vw * 0.7),calc(100vh * 0.7)) rotate(var(--angle));opacity:0}' +
            '}',

            '#space-stars,#space-meteors{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}',

            '.space-star{position:absolute;border-radius:50%;will-change:opacity}',

            '.space-meteor{' +
                'position:absolute;height:2px;border-radius:1px;' +
                'will-change:transform,opacity;opacity:0;' +
                'animation:meteorFly var(--speed) ease-in forwards' +
            '}',
        ].join('\n');
        document.head.appendChild(style);
    }

    // ─── Stars ───────────────────────────────────────────────────────
    function createStars() {
        var container = document.createElement('div');
        container.id = 'space-stars';
        container.setAttribute('aria-hidden', 'true');

        var frag = document.createDocumentFragment();

        for (var i = 0; i < STAR_COUNT; i++) {
            var star = document.createElement('div');
            star.className = 'space-star';
            var size = pickSize();
            var color = pick(COLORS);

            // Glow
            var glowSize = size * 2.5;
            var glowColor = color.replace(/[\d.]+\)$/, '0.5)');
            var shadow = '0 0 ' + glowSize + 'px ' + glowColor;
            if (size > 4) {
                var bigGlow = color.replace(/[\d.]+\)$/, '0.15)');
                shadow += ',0 0 ' + (glowSize * 3) + 'px ' + bigGlow;
            }

            // Each star gets a unique animation variant, duration, and delay
            var variant = 'twinkle' + (randInt(1, 8));
            var duration = rand(1.5, 6); // seconds
            var delay = rand(0, 8);      // seconds — large spread so they never sync

            star.style.cssText = [
                'width:' + size + 'px',
                'height:' + size + 'px',
                'left:' + rand(0, 100) + '%',
                'top:' + rand(0, 100) + '%',
                'background:' + color,
                'box-shadow:' + shadow,
                'animation:' + variant + ' ' + duration.toFixed(2) + 's ' + delay.toFixed(2) + 's ease-in-out infinite',
                'opacity:0',
            ].join(';');

            frag.appendChild(star);
        }

        container.appendChild(frag);
        document.body.prepend(container);
    }

    // ─── Meteors ─────────────────────────────────────────────────────
    var meteorContainer = null;

    function createMeteorContainer() {
        meteorContainer = document.createElement('div');
        meteorContainer.id = 'space-meteors';
        meteorContainer.setAttribute('aria-hidden', 'true');
        document.body.prepend(meteorContainer);
    }

    var meteorGradients = [
        'linear-gradient(90deg,rgba(114,220,255,0),rgba(114,220,255,0.8) 40%,rgba(255,255,255,1))',
        'linear-gradient(90deg,rgba(139,92,246,0),rgba(139,92,246,0.7) 40%,rgba(255,255,255,1))',
        'linear-gradient(90deg,rgba(253,224,71,0),rgba(253,224,71,0.7) 40%,rgba(255,255,255,1))',
    ];

    function shootMeteor() {
        if (document.hidden) {
            scheduleMeteor();
            return;
        }

        var meteor = document.createElement('div');
        meteor.className = 'space-meteor';

        var angle = rand(25, 55);
        var speed = rand(0.6, 1.2); // seconds
        var startX = rand(30, 95);
        var startY = rand(0, 30);
        var tailLen = rand(80, 150);

        meteor.style.cssText = [
            'left:' + startX + '%',
            'top:' + startY + '%',
            'width:' + tailLen + 'px',
            'background:' + pick(meteorGradients),
            '--angle:' + angle + 'deg',
            '--speed:' + speed.toFixed(2) + 's',
            'filter:drop-shadow(0 0 4px rgba(114,220,255,0.6))',
        ].join(';');

        meteorContainer.appendChild(meteor);

        // Remove element after animation ends
        setTimeout(function () {
            if (meteor.parentNode) meteor.remove();
        }, speed * 1000 + 100);

        scheduleMeteor();
    }

    function scheduleMeteor() {
        var delay = rand(METEOR_MIN_INTERVAL, METEOR_MAX_INTERVAL);
        setTimeout(shootMeteor, delay);
    }

    // ─── Init ────────────────────────────────────────────────────────
    function init() {
        injectStyles();
        createStars();
        createMeteorContainer();
        // First meteor fires after a short delay
        setTimeout(shootMeteor, rand(1000, 3000));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
