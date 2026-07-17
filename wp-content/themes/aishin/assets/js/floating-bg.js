/**
 * 背景の浮遊シェイプのスクロール視差（React版 FloatingBg.tsx の移植）
 * 各シェイプを速度係数つきで移動し、画面外に出たら反対側へループする。
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('.floating-bg');
    if (!root) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var items = Array.prototype.slice.call(root.children);
    var rafId = 0;
    var lastY = -1;

    // スクロール視差: 各シェイプを速度係数つきで移動し、画面外に出たら反対側へループ
    var update = function () {
      var scrollY = window.scrollY;
      if (scrollY !== lastY) {
        lastY = scrollY;
        var vh = window.innerHeight;
        var range = vh + 600;
        items.forEach(function (el) {
          var speed = parseFloat(el.dataset.speed);
          var top = parseFloat(el.dataset.top);
          var baseY = (top / 100) * vh;
          var y = (baseY - scrollY * speed) % range;
          if (y < -300) y += range;
          el.style.transform = 'translateY(' + (y - baseY) + 'px)';
        });
      }
      rafId = requestAnimationFrame(update);
    };
    rafId = requestAnimationFrame(update);
  });
})();
