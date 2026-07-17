/**
 * 下層ページ共通ヒーローのアニメーション（React版 PageHero.tsx の移植）
 * キネティックタイポグラフィ（ドロップイン→浮遊ループ）＋物理演算ピース起動。
 */
(function () {
  'use strict';

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('.page-hero');
    if (!root || !window.gsap) return;

    var chars = gsap.utils.toArray('.page-hero__char');
    // titleJa / lead は任意のため、存在する要素のみアニメーション対象にする
    var subTargets = ['.page-hero__ja', '.page-hero__lead'].filter(function (sel) {
      return root.querySelector(sel);
    });

    if (prefersReducedMotion()) {
      gsap.set(chars, { opacity: 1, yPercent: 0, rotate: 0 });
      gsap.set(['.page-hero__eyebrow'].concat(subTargets), { opacity: 1, y: 0 });
    } else {
      var tl = gsap.timeline({ defaults: { ease: 'back.out(1.8)' } });
      tl.fromTo(
        '.page-hero__eyebrow',
        { opacity: 0, y: 18 },
        { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' },
        0
      ).fromTo(
        chars,
        {
          opacity: 0,
          yPercent: 130,
          rotate: function () {
            return gsap.utils.random(-26, 26);
          },
        },
        {
          opacity: 1,
          yPercent: 0,
          rotate: 0,
          duration: 0.9,
          stagger: 0.06,
        },
        0.1
      );
      subTargets.forEach(function (sel) {
        tl.fromTo(
          sel,
          { opacity: 0, y: 24 },
          { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' },
          '-=0.5'
        );
      });
      // 出現後もタイトルがゆっくり浮遊し続ける
      tl.to('.page-hero__title', {
        y: -8,
        duration: 2.6,
        yoyo: true,
        repeat: -1,
        ease: 'sine.inOut',
      });
    }

    // 物理演算ピース（下層ページは startOffset 160）
    var stage = document.querySelector('.page-hero__stage');
    var pieces = stage ? stage.querySelector('.hero__pieces') : null;
    if (stage && pieces && window.aishinPhysicsPieces) {
      window.aishinPhysicsPieces(stage, pieces, { startOffset: 160 });
    }
  });
})();
