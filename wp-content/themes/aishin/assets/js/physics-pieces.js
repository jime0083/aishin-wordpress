/**
 * ヒーローの物理演算（matter-js）: パズルピースの落下・ドラッグ
 * （React版 usePhysicsPieces.ts / Hero.tsx の物理演算を移植。定数は全て転記）
 *
 * 使用: window.aishinPhysicsPieces(stage, piecesWrap, { startOffset: 200 })
 *   startOffset: 落下開始位置のオフセット（トップHero=200 / 下層PageHero=160）
 */
(function () {
  'use strict';

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  // reduced-motion 時はピースを静的表示にする（React版の hero__pieces--static と同じ）
  document.addEventListener('DOMContentLoaded', function () {
    if (prefersReducedMotion()) {
      document.querySelectorAll('.hero__pieces').forEach(function (el) {
        el.classList.add('hero__pieces--static');
      });
    }
  });

  window.aishinPhysicsPieces = function (stage, piecesWrap, options) {
    var startOffset = (options && options.startOffset) || 160;
    if (!stage || !piecesWrap) return;
    if (prefersReducedMotion()) return;
    if (!window.Matter || !window.gsap) return;

    var elements = Array.prototype.slice.call(
      piecesWrap.querySelectorAll('.hero__piece')
    );
    if (elements.length === 0) return;

    var Engine = Matter.Engine;
    var Bodies = Matter.Bodies;
    var Body = Matter.Body;
    var Composite = Matter.Composite;
    var Mouse = Matter.Mouse;
    var MouseConstraint = Matter.MouseConstraint;

    var engine = Engine.create({ gravity: { x: 0, y: 1.1 } });
    var width = stage.clientWidth;
    var height = stage.clientHeight;

    var wallOpts = { isStatic: true, friction: 0.8 };
    var floor = Bodies.rectangle(width / 2, height + 60, width * 2, 120, wallOpts);
    var wallL = Bodies.rectangle(-60, height / 2, 120, height * 4, wallOpts);
    var wallR = Bodies.rectangle(width + 60, height / 2, 120, height * 4, wallOpts);
    Composite.add(engine.world, [floor, wallL, wallR]);

    // 各DOM要素のサイズを測って剛体を生成し、画面上方からランダムに降らせる
    var bodies = elements.map(function (el, i) {
      var w = el.offsetWidth;
      var h = el.offsetHeight;
      var x = gsap.utils.random(w, Math.max(w + 1, width - w));
      var y = -h - i * gsap.utils.random(90, 180) - startOffset;
      return Bodies.rectangle(x, y, w, h, {
        chamfer: { radius: Math.min(h / 2 - 2, 26) },
        restitution: 0.45,
        friction: 0.4,
        frictionAir: 0.012,
        angle: gsap.utils.random(-0.5, 0.5),
      });
    });
    Composite.add(engine.world, bodies);

    // ドラッグ操作（スクロールを妨げないようホイール/タッチのリスナーは除去）
    var mouse = Mouse.create(stage);
    var mouseConstraint = MouseConstraint.create(engine, {
      mouse: mouse,
      constraint: { stiffness: 0.15, damping: 0.1, render: { visible: false } },
    });
    stage.removeEventListener('wheel', mouse.mousewheel);
    stage.removeEventListener('touchmove', mouse.mousemove);
    stage.removeEventListener('touchstart', mouse.mousedown);
    stage.removeEventListener('touchend', mouse.mouseup);
    Composite.add(engine.world, mouseConstraint);

    // 独自rAFループ: 物理更新とDOM同期（画面外では停止してCPUを節約）
    var visible = true;
    var lastTime = performance.now();
    var tick = function (time) {
      var delta = Math.min(time - lastTime, 33.33);
      lastTime = time;
      if (visible) {
        Engine.update(engine, delta);
        for (var i = 0; i < bodies.length; i += 1) {
          var b = bodies[i];
          var el = elements[i];
          var w = el.offsetWidth;
          var h = el.offsetHeight;
          el.style.transform =
            'translate(' + (b.position.x - w / 2) + 'px, ' + (b.position.y - h / 2) + 'px) rotate(' + b.angle + 'rad)';
        }
      }
      requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);

    var observer = new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      lastTime = performance.now();
    });
    observer.observe(stage);

    // リサイズ時に壁を作り直し、はみ出したピースを戻す
    var onResize = function () {
      var newW = stage.clientWidth;
      var newH = stage.clientHeight;
      if (newW === width && newH === height) return;
      width = newW;
      height = newH;
      Composite.remove(engine.world, [floor, wallL, wallR]);
      floor = Bodies.rectangle(width / 2, height + 60, width * 2, 120, wallOpts);
      wallL = Bodies.rectangle(-60, height / 2, 120, height * 4, wallOpts);
      wallR = Bodies.rectangle(width + 60, height / 2, 120, height * 4, wallOpts);
      Composite.add(engine.world, [floor, wallL, wallR]);
      bodies.forEach(function (b) {
        if (b.position.x < 0 || b.position.x > width) {
          Body.setPosition(b, { x: gsap.utils.random(60, width - 60), y: -120 });
          Body.setVelocity(b, { x: 0, y: 0 });
        }
      });
    };
    window.addEventListener('resize', onResize);
  };
})();
