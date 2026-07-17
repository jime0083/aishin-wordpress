/**
 * カーソルインタラクション: ドット＋追従リング（React版 CustomCursor.tsx の移植）
 * ホバー対象で拡大、data-cursor-label でラベル表示。
 * React版と同様、(pointer: fine) かつ non-reduced-motion のときのみDOMを生成する。
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var finePointer = window.matchMedia('(pointer: fine)').matches;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!finePointer || reduced) return;

    var dot = document.createElement('div');
    dot.className = 'cursor__dot';
    dot.setAttribute('aria-hidden', 'true');

    var ring = document.createElement('div');
    ring.className = 'cursor__ring';
    ring.setAttribute('aria-hidden', 'true');
    var labelEl = document.createElement('span');
    labelEl.className = 'cursor__label';
    ring.appendChild(labelEl);

    document.body.appendChild(dot);
    document.body.appendChild(ring);
    document.body.classList.add('has-custom-cursor');

    var pos = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
    var dotPos = { x: pos.x, y: pos.y };
    var ringPos = { x: pos.x, y: pos.y };
    var visible = false;

    var onMove = function (e) {
      pos.x = e.clientX;
      pos.y = e.clientY;
      if (!visible) {
        visible = true;
        dot.style.opacity = '1';
        ring.style.opacity = '1';
      }
    };

    var onOver = function (e) {
      var target = e.target.closest ? e.target.closest('a, button, [data-cursor-label]') : null;
      var label = (target && target.dataset.cursorLabel) || '';
      labelEl.textContent = label;
      ring.classList.toggle('is-label', Boolean(label));
      ring.classList.toggle('is-hover', Boolean(target) && !label);
    };

    var onLeave = function () {
      visible = false;
      dot.style.opacity = '0';
      ring.style.opacity = '0';
    };

    var tick = function () {
      dotPos.x += (pos.x - dotPos.x) * 0.4;
      dotPos.y += (pos.y - dotPos.y) * 0.4;
      ringPos.x += (pos.x - ringPos.x) * 0.16;
      ringPos.y += (pos.y - ringPos.y) * 0.16;
      dot.style.transform = 'translate(' + dotPos.x + 'px, ' + dotPos.y + 'px)';
      ring.style.transform = 'translate(' + ringPos.x + 'px, ' + ringPos.y + 'px)';
      requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);

    window.addEventListener('mousemove', onMove, { passive: true });
    document.addEventListener('mouseover', onOver, { passive: true });
    document.documentElement.addEventListener('mouseleave', onLeave);
  });
})();
