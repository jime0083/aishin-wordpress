/**
 * ヘッダーの挙動（React版 Header.tsx の移植）
 * - scrollY > 24 で is-scrolled
 * - バーガーで is-open トグル＋body overflow hidden
 * - ドロワーリンクの transitionDelay: 開時 80 + i * 50ms / 閉時 0ms
 * - リンククリックで閉じる
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var header = document.querySelector('.header');
    if (!header) return;

    var burger = header.querySelector('.header__burger');
    var drawer = header.querySelector('.header__drawer');
    var drawerLinks = Array.prototype.slice.call(
      header.querySelectorAll('.header__drawer-link')
    );
    var open = false;

    var onScroll = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 24);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    var applyOpen = function () {
      header.classList.toggle('is-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
      if (burger) {
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        burger.setAttribute('aria-label', open ? 'メニューを閉じる' : 'メニューを開く');
      }
      if (drawer) {
        drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
      }
      drawerLinks.forEach(function (link, i) {
        link.style.transitionDelay = (open ? 80 + i * 50 : 0) + 'ms';
      });
    };

    if (burger) {
      burger.addEventListener('click', function () {
        open = !open;
        applyOpen();
      });
    }

    var close = function () {
      if (!open) return;
      open = false;
      applyOpen();
    };
    drawerLinks.forEach(function (link) {
      link.addEventListener('click', close);
    });
    var logo = header.querySelector('.header__logo');
    if (logo) logo.addEventListener('click', close);
  });
})();
