/**
 * インタビュー詳細ページのアニメーション（React版 InterviewDetail.tsx の移植）
 * - ヒーローの一言（quote）を1文字ずつキネティック出現
 * - 物理演算ピースの起動（startOffset 160）
 */
(function () {
  'use strict';

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('.itv-hero');
    if (!root || !window.gsap) return;

    var chars = gsap.utils.toArray('.itv-hero__char');
    var subs = ['.itv-hero__eyebrow', '.itv-hero__name', '.itv-hero__role'];

    if (prefersReducedMotion()) {
      gsap.set(chars, { opacity: 1, yPercent: 0, rotate: 0 });
      gsap.set(subs, { opacity: 1, y: 0 });
    } else {
      var tl = gsap.timeline({ defaults: { ease: 'back.out(1.8)' } });
      tl.fromTo(
        '.itv-hero__eyebrow',
        { opacity: 0, y: 18 },
        { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' },
        0
      )
        .fromTo(
          chars,
          {
            opacity: 0,
            yPercent: 130,
            rotate: function () {
              return gsap.utils.random(-18, 18);
            },
          },
          { opacity: 1, yPercent: 0, rotate: 0, duration: 0.8, stagger: 0.028 },
          0.1
        )
        .fromTo(
          ['.itv-hero__name', '.itv-hero__role'],
          { opacity: 0, y: 24 },
          { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.1 },
          '-=0.4'
        );
    }

    // 物理演算ピース（下層ページは startOffset 160）
    var stage = document.querySelector('.page-hero__stage');
    var pieces = stage ? stage.querySelector('.hero__pieces') : null;
    if (stage && pieces && window.aishinPhysicsPieces) {
      window.aishinPhysicsPieces(stage, pieces, { startOffset: 160 });
    }
  });
})();
