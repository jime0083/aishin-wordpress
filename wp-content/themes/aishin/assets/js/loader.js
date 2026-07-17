/**
 * アクセス直後のローディング演出（React版 Loader.tsx の移植）
 * カウンター → マスクワイプでFVへ。
 * 画面のワイプが始まるタイミング（1.55s）で CustomEvent 'aishin:reveal' を
 * document に発火する（hero.js がこれを受けてFVアニメーションを開始する）。
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('.loader');
    if (!root) return;

    var reveal = function () {
      document.dispatchEvent(new CustomEvent('aishin:reveal'));
    };

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !window.gsap) {
      root.style.display = 'none';
      reveal();
      return;
    }

    var counter = { n: 0 };
    var countEl = root.querySelector('.loader__count-num');

    var tl = gsap.timeline({
      onComplete: function () {
        root.style.display = 'none';
      },
    });

    tl.fromTo(
      '.loader__brand-char',
      { yPercent: 120, opacity: 0 },
      { yPercent: 0, opacity: 1, duration: 0.6, stagger: 0.05, ease: 'power3.out' },
      0
    )
      .fromTo(
        '.loader__tag',
        { opacity: 0 },
        { opacity: 1, duration: 0.5, ease: 'none' },
        0.2
      )
      .to(
        counter,
        {
          n: 100,
          duration: 1.3,
          ease: 'power2.inOut',
          onUpdate: function () {
            if (countEl) countEl.textContent = String(Math.round(counter.n));
          },
        },
        0.1
      )
      .to('.loader__mark', { rotate: 372, duration: 1.5, ease: 'power2.inOut' }, 0)
      // ワイプ開始: 紙色パネル→オレンジパネルの順に上へ抜ける
      .add(reveal, 1.55)
      .to('.loader__panel--paper', { yPercent: -100, duration: 0.7, ease: 'power4.inOut' }, 1.5)
      .to('.loader__panel--orange', { yPercent: -100, duration: 0.7, ease: 'power4.inOut' }, 1.62);
  });
})();
