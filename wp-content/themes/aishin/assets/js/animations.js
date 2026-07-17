/**
 * 全ページ共通のスクロール連動アニメーション
 * （React版 useSubpageAnimations.ts + Home.tsx の演出を統合移植。
 *   パラメータは全てReact版から数値を転記）
 *
 * skew演出の対象はテンプレートが <body data-skew-targets="..."> で指定する。
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    if (!window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);

    // display:none の要素（例: ENTRYの送信後画面）は登録しない。
    // React版でも後からマウントされる要素にはアニメーションが登録されない挙動と同じ。
    var isRendered = function (el) {
      return el.getClientRects().length > 0;
    };

    var mm = gsap.matchMedia();
    mm.add('(prefers-reduced-motion: no-preference)', function () {
      // 単体要素のフェードイン
      gsap.utils.toArray('[data-reveal]').filter(isRendered).forEach(function (el) {
        gsap.fromTo(
          el,
          { opacity: 0, y: 48 },
          {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 85%' },
          }
        );
      });

      // グループ（子要素を回転・スケール付きでずらして表示）
      gsap.utils.toArray('[data-reveal-group]').filter(isRendered).forEach(function (group) {
        gsap.fromTo(
          Array.prototype.slice.call(group.children),
          {
            opacity: 0,
            y: 72,
            scale: 0.94,
            rotate: function () {
              return gsap.utils.random(-4, 4);
            },
          },
          {
            opacity: 1,
            y: 0,
            scale: 1,
            rotate: 0,
            duration: 0.9,
            ease: 'power3.out',
            stagger: 0.13,
            scrollTrigger: { trigger: group, start: 'top 82%' },
          }
        );
      });

      // セクション見出しのマスクリビール
      gsap.utils.toArray('.section__title').filter(isRendered).forEach(function (el) {
        gsap.fromTo(
          el,
          { clipPath: 'inset(0 0 100% 0)', y: 36 },
          {
            clipPath: 'inset(0 0 -20% 0)',
            y: 0,
            duration: 1,
            ease: 'power4.out',
            scrollTrigger: { trigger: el, start: 'top 86%' },
          }
        );
      });

      // 巨大アウトライン英字をスクロールで横に流す
      gsap.utils.toArray('[data-giant]').forEach(function (el) {
        var dir = el.dataset.giant === 'left' ? 1 : -1;
        gsap.fromTo(
          el,
          { xPercent: 6 * dir },
          {
            xPercent: -10 * dir,
            ease: 'none',
            scrollTrigger: {
              trigger: el.parentElement || el,
              start: 'top bottom',
              end: 'bottom top',
              scrub: 0.6,
            },
          }
        );
      });

      // スクロール速度に連動したskew（歪み）演出
      var skewSelector = document.body.dataset.skewTargets;
      if (skewSelector) {
        var targets = gsap.utils.toArray(skewSelector);
        if (targets.length > 0) {
          var setters = targets.map(function (el) {
            return gsap.quickSetter(el, 'skewY', 'deg');
          });
          var proxy = { value: 0 };
          var apply = function () {
            setters.forEach(function (set) {
              set(proxy.value);
            });
          };
          ScrollTrigger.create({
            onUpdate: function (self) {
              var velocity = gsap.utils.clamp(-6, 6, self.getVelocity() / -400);
              if (Math.abs(velocity) > Math.abs(proxy.value)) {
                proxy.value = velocity;
                gsap.to(proxy, {
                  value: 0,
                  duration: 0.8,
                  ease: 'power3.out',
                  overwrite: true,
                  onUpdate: apply,
                });
              }
            },
          });
        }
      }

      // 数字のカウントアップ
      gsap.utils.toArray('[data-count]').filter(isRendered).forEach(function (el) {
        var target = Number(el.dataset.count || 0);
        var decimals = Number(el.dataset.decimals || 0);
        var obj = { n: 0 };
        gsap.to(obj, {
          n: target,
          duration: 1.6,
          ease: 'power2.out',
          scrollTrigger: { trigger: el, start: 'top 88%' },
          onUpdate: function () {
            el.textContent = obj.n.toFixed(decimals);
          },
        });
      });

      // 画像プレースホルダーのマスクリビール（左→右に開く）
      gsap.utils.toArray('.img-ph').filter(isRendered).forEach(function (el) {
        gsap.fromTo(
          el,
          { clipPath: 'inset(0 100% 0 0)' },
          {
            clipPath: 'inset(0 0% 0 0)',
            duration: 1.1,
            ease: 'power4.out',
            scrollTrigger: { trigger: el, start: 'top 86%' },
          }
        );
      });

      // ミッションの行ごとのマスクリビール（キネティック文字・トップページのみ存在）
      gsap.utils.toArray('[data-reveal-line] .mission__line-inner').forEach(function (el) {
        gsap.fromTo(
          el,
          { yPercent: 110 },
          {
            yPercent: 0,
            duration: 1,
            ease: 'power4.out',
            scrollTrigger: { trigger: el, start: 'top 88%' },
          }
        );
      });

      // ENTRY CTA の巨大文字が下から順にせり上がる（EntryCta 使用ページのみ）
      var entryChars = gsap.utils.toArray('.entry__char');
      if (entryChars.length > 0) {
        gsap.fromTo(
          entryChars,
          { yPercent: 60, opacity: 0 },
          {
            yPercent: 0,
            opacity: 1,
            duration: 0.7,
            ease: 'back.out(1.6)',
            stagger: 0.07,
            scrollTrigger: { trigger: '.entry', start: 'top 70%' },
          }
        );
      }
    });
  });
})();
