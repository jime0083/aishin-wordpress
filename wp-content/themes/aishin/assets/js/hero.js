/**
 * トップページFVのアニメーション（React版 Hero.tsx の移植）
 * - キネティックタイポグラフィ（横書きタイトルのドロップイン→浮遊ループ）
 * - 物理演算（matter-js）ピースの起動（startOffset 200）
 * どちらもローディング演出の 'aishin:reveal' イベントを受けて開始する。
 */
(function () {
  'use strict';

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  var start = function () {
    var content = document.querySelector('.hero__content');
    if (!content || !window.gsap) return;

    var reduced = prefersReducedMotion();
    var chars = gsap.utils.toArray('.hero__char');

    if (reduced) {
      gsap.set(chars, { opacity: 1, yPercent: 0, rotate: 0 });
      // hero__badge は含めない: React版では gsap.context(contentRef) のスコープ外に
      // あるためアニメーション対象にならず、CSS初期値 opacity:0 のまま非表示になる（P-001）
      gsap.set(['.hero__sub', '.hero__lead', '.hero__scroll-cue'], {
        opacity: 1,
        y: 0,
      });
    } else {
      var tl = gsap.timeline({ defaults: { ease: 'back.out(1.8)' } });
      tl.fromTo(
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
          stagger: 0.055,
        },
        0.1
      )
        .fromTo(
          '.hero__lead',
          { opacity: 0, y: 24 },
          { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' },
          '-=0.5'
        )
        .fromTo(
          '.hero__sub',
          { opacity: 0, y: 24 },
          { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' },
          '-=0.55'
        )
        // hero__badge のアニメーションは行わない（P-001）: React版では
        // gsap.context(contentRef) のスコープ外にあるためアニメーション対象にならず、
        // CSS初期値 opacity:0 のまま非表示になる。その挙動に合わせる。
        .fromTo(
          '.hero__scroll-cue',
          { opacity: 0 },
          { opacity: 1, duration: 0.6, ease: 'none' },
          '-=0.4'
        )
        // 出現後もタイトルがゆっくり浮遊し続ける
        .to('.hero__title', {
          y: -10,
          duration: 2.6,
          yoyo: true,
          repeat: -1,
          ease: 'sine.inOut',
        });
    }

    // 物理演算（reduced-motion 時は aishinPhysicsPieces 側で起動しない）
    var stage = document.querySelector('.hero__stage');
    var pieces = stage ? stage.querySelector('.hero__pieces') : null;
    if (stage && pieces && window.aishinPhysicsPieces) {
      window.aishinPhysicsPieces(stage, pieces, { startOffset: 200 });
    }
  };

  document.addEventListener('aishin:reveal', start, { once: true });
})();
