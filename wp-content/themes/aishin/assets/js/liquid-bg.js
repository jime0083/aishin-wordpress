/**
 * Hero背景のWebGL液体歪みキャンバス（React版 LiquidBg.tsx の移植）
 * 紙色＋ドットグリッド＋巨大タイポをテクスチャに描き、
 * カーソルの軌跡に沿ったリップルと常時の揺らぎでUVを歪ませる。
 * WebGL非対応環境ではCSSフォールバック（親要素のドット背景）のまま。
 */
(function () {
  'use strict';

  var TRAIL_COUNT = 16;
  var TEXT_ROWS = 4;

  /** 背景を流れる巨大タイポ（行ごとに交互方向・速度違い） */
  var ROW_PHRASES = [
    { text: 'INVENT THE MISSING PIECE — ', color: 'rgba(255, 90, 31, 0.22)', speed: 0.016 },
    { text: 'AISHIN INC. — CONSULTING VENTURE — ', color: 'rgba(38, 34, 29, 0.13)', speed: -0.022 },
    { text: 'STRATEGY × CREATIVE × LOGIC — ', color: 'rgba(255, 90, 31, 0.18)', speed: 0.012 },
    { text: 'JOIN OUR TEAM — EST. 2018 — ', color: 'rgba(38, 34, 29, 0.11)', speed: -0.018 },
  ];

  var VERT =
    'attribute vec2 aPos;\n' +
    'varying vec2 vUv;\n' +
    'void main() {\n' +
    '  vUv = vec2(aPos.x * 0.5 + 0.5, 0.5 - aPos.y * 0.5);\n' +
    '  gl_Position = vec4(aPos, 0.0, 1.0);\n' +
    '}\n';

  var FRAG =
    'precision highp float;\n' +
    'uniform sampler2D uTex;\n' +
    'uniform sampler2D uTextTex;\n' +
    'uniform vec2 uRes;\n' +
    'uniform float uTime;\n' +
    'uniform float uScrollX;\n' +
    'uniform vec3 uTrail[' + TRAIL_COUNT + '];\n' +
    'uniform float uRowRepeat[' + TEXT_ROWS + '];\n' +
    'uniform float uRowSpeed[' + TEXT_ROWS + '];\n' +
    'uniform float uRowFrac[' + TEXT_ROWS + '];\n' +
    'varying vec2 vUv;\n' +
    '\n' +
    'void main() {\n' +
    '  vec2 uv = vUv;\n' +
    '  float aspect = uRes.x / uRes.y;\n' +
    '\n' +
    '  // 常時ゆらめく液体の揺らぎ\n' +
    '  vec2 offset = vec2(\n' +
    '    sin(uv.y * 9.0 + uTime * 0.7) + sin(uv.y * 4.0 - uTime * 0.4),\n' +
    '    cos(uv.x * 8.0 + uTime * 0.6) + cos(uv.x * 3.5 + uTime * 0.35)\n' +
    '  ) * 0.0022;\n' +
    '\n' +
    '  // カーソル軌跡のリップル（押し出し歪み）\n' +
    '  for (int i = 0; i < ' + TRAIL_COUNT + '; i++) {\n' +
    '    vec3 p = uTrail[i];\n' +
    '    vec2 d = uv - p.xy;\n' +
    '    d.x *= aspect;\n' +
    '    float dist2 = dot(d, d);\n' +
    '    float influence = p.z * exp(-dist2 * 70.0);\n' +
    '    offset += normalize(d + 0.0001) * influence * 0.045;\n' +
    '    // リング状の波紋\n' +
    '    float ring = sin(sqrt(dist2) * 40.0 - uTime * 6.0);\n' +
    '    offset += normalize(d + 0.0001) * ring * influence * 0.012;\n' +
    '  }\n' +
    '\n' +
    '  vec2 suv = uv + offset + vec2(uScrollX / uRes.x, 0.0);\n' +
    '  vec4 base = texture2D(uTex, suv);\n' +
    '\n' +
    '  // 多層キネティックタイポ: 行ごとに逆方向へ流れ、歪みの影響も受ける\n' +
    '  vec2 tuvBase = uv + offset;\n' +
    '  float rowF = clamp(tuvBase.y, 0.0, 0.999) * ' + TEXT_ROWS + '.0;\n' +
    '  int row = int(floor(rowF));\n' +
    '  // GLSL ES 1.0では動的インデックスが使えない環境があるため定数ループで選択\n' +
    '  float repeat = 0.0;\n' +
    '  float speed = 0.0;\n' +
    '  float frac = 1.0;\n' +
    '  for (int i = 0; i < ' + TEXT_ROWS + '; i++) {\n' +
    '    if (i == row) {\n' +
    '      repeat = uRowRepeat[i];\n' +
    '      speed = uRowSpeed[i];\n' +
    '      frac = uRowFrac[i];\n' +
    '    }\n' +
    '  }\n' +
    '  float tx = fract(tuvBase.x * repeat + uTime * speed + uScrollX / uRes.x);\n' +
    '  vec2 tuv = vec2(tx * frac, rowF / ' + TEXT_ROWS + '.0);\n' +
    '  vec4 typo = texture2D(uTextTex, tuv);\n' +
    '\n' +
    '  gl_FragColor = vec4(mix(base.rgb, typo.rgb, typo.a), 1.0);\n' +
    '}\n';

  function drawTexture(ctx, w, h, dpr) {
    ctx.clearRect(0, 0, w, h);
    ctx.fillStyle = '#fbf7f1';
    ctx.fillRect(0, 0, w, h);

    // 右上のコーラルの淡いグロー
    var glow = ctx.createRadialGradient(w * 0.82, h * 0.12, 0, w * 0.82, h * 0.12, w * 0.5);
    glow.addColorStop(0, 'rgba(255, 138, 92, 0.16)');
    glow.addColorStop(1, 'rgba(255, 138, 92, 0)');
    ctx.fillStyle = glow;
    ctx.fillRect(0, 0, w, h);

    // ドットグリッド
    var gap = 30 * dpr;
    ctx.fillStyle = '#e7ddca';
    for (var y = gap / 2; y < h; y += gap) {
      for (var x = gap / 2; x < w; x += gap) {
        ctx.beginPath();
        ctx.arc(x, y, 1.5 * dpr, 0, Math.PI * 2);
        ctx.fill();
      }
    }
  }

  /**
   * 多層キネティックタイポのアトラス（1行=1フレーズ、横タイル可能）。
   * 各行の1タイル分の実幅を返す（シェーダーのリピート計算に使用）。
   */
  function drawTextAtlas(ctx, w, rowH) {
    ctx.clearRect(0, 0, w, rowH * TEXT_ROWS);
    ctx.textBaseline = 'middle';
    return ROW_PHRASES.map(function (row, i) {
      var fontSize = rowH * 0.74;
      ctx.font = '800 ' + fontSize + 'px Syne, sans-serif';
      var tileW = ctx.measureText(row.text).width;
      // アトラス幅に収まらないフレーズはフォントを縮小してタイル化を保つ
      if (tileW > w) {
        fontSize *= (w / tileW) * 0.98;
        ctx.font = '800 ' + fontSize + 'px Syne, sans-serif';
        tileW = ctx.measureText(row.text).width;
      }
      var y = rowH * i + rowH / 2;
      ctx.strokeStyle = row.color;
      ctx.lineWidth = Math.max(2, fontSize * 0.016);
      // タイル境界をまたいでも途切れないよう2周分描く
      ctx.save();
      ctx.beginPath();
      ctx.rect(0, rowH * i, w, rowH);
      ctx.clip();
      ctx.strokeText(row.text, 0, y);
      ctx.strokeText(row.text, tileW, y);
      ctx.restore();
      return tileW;
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.querySelector('.hero__canvas');
    var parent = canvas ? canvas.parentElement : null;
    if (!canvas || !parent) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var gl = canvas.getContext('webgl', { antialias: false, alpha: false });
    if (!gl) return;

    var dpr = Math.min(window.devicePixelRatio || 1, 1.75);

    // --- シェーダー ---
    var compile = function (type, src) {
      var shader = gl.createShader(type);
      if (!shader) return null;
      gl.shaderSource(shader, src);
      gl.compileShader(shader);
      if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) return null;
      return shader;
    };
    var vs = compile(gl.VERTEX_SHADER, VERT);
    var fs = compile(gl.FRAGMENT_SHADER, FRAG);
    var program = gl.createProgram();
    if (!vs || !fs || !program) return;
    gl.attachShader(program, vs);
    gl.attachShader(program, fs);
    gl.linkProgram(program);
    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return;
    gl.useProgram(program);

    // --- フルスクリーンクアッド ---
    var buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(
      gl.ARRAY_BUFFER,
      new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]),
      gl.STATIC_DRAW
    );
    var aPos = gl.getAttribLocation(program, 'aPos');
    gl.enableVertexAttribArray(aPos);
    gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0);

    // --- テクスチャ（2Dキャンバスに描画）: 0=ベース背景 / 1=タイポアトラス ---
    var texCanvas = document.createElement('canvas');
    var texCtx = texCanvas.getContext('2d');
    var atlasCanvas = document.createElement('canvas');
    var atlasCtx = atlasCanvas.getContext('2d');
    if (!texCtx || !atlasCtx) return;

    var createTexture = function () {
      var texture = gl.createTexture();
      gl.bindTexture(gl.TEXTURE_2D, texture);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
      return texture;
    };
    var baseTexture = createTexture();
    var atlasTexture = createTexture();

    var maxTexSize = gl.getParameter(gl.MAX_TEXTURE_SIZE);
    var atlasW = Math.min(4096, maxTexSize);
    var atlasRowH = 256;
    atlasCanvas.width = atlasW;
    atlasCanvas.height = atlasRowH * TEXT_ROWS;

    var uTex = gl.getUniformLocation(program, 'uTex');
    var uTextTex = gl.getUniformLocation(program, 'uTextTex');
    var uRes = gl.getUniformLocation(program, 'uRes');
    var uTime = gl.getUniformLocation(program, 'uTime');
    var uScrollX = gl.getUniformLocation(program, 'uScrollX');
    var uTrail = gl.getUniformLocation(program, 'uTrail');
    var uRowRepeat = gl.getUniformLocation(program, 'uRowRepeat');
    var uRowSpeed = gl.getUniformLocation(program, 'uRowSpeed');
    var uRowFrac = gl.getUniformLocation(program, 'uRowFrac');
    gl.uniform1i(uTex, 0);
    gl.uniform1i(uTextTex, 1);
    gl.uniform1fv(
      uRowSpeed,
      ROW_PHRASES.map(function (r) {
        return r.speed;
      })
    );

    var uploadBase = function () {
      gl.activeTexture(gl.TEXTURE0);
      gl.bindTexture(gl.TEXTURE_2D, baseTexture);
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texCanvas);
    };

    var tileWidths = ROW_PHRASES.map(function () {
      return atlasW;
    });

    // 画面上の行高に合わせたタイルの繰り返し数（グリフの縦横比を保つ）
    var updateRepeats = function () {
      var w = canvas.width;
      var h = canvas.height;
      if (w === 0 || h === 0) return;
      var scale = h / TEXT_ROWS / atlasRowH;
      gl.uniform1fv(
        uRowRepeat,
        tileWidths.map(function (tw) {
          return w / Math.max(1, tw * scale);
        })
      );
    };

    // アトラス再描画＋行ごとのリピート数・タイル幅比を更新
    var updateAtlas = function () {
      tileWidths = drawTextAtlas(atlasCtx, atlasW, atlasRowH);
      gl.activeTexture(gl.TEXTURE1);
      gl.bindTexture(gl.TEXTURE_2D, atlasTexture);
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, atlasCanvas);
      gl.uniform1fv(
        uRowFrac,
        tileWidths.map(function (tw) {
          return tw / atlasW;
        })
      );
      updateRepeats();
    };

    var resize = function () {
      var w = Math.round(parent.clientWidth * dpr);
      var h = Math.round(parent.clientHeight * dpr);
      if (w === 0 || h === 0) return;
      canvas.width = w;
      canvas.height = h;
      texCanvas.width = w;
      texCanvas.height = h;
      gl.viewport(0, 0, w, h);
      gl.uniform2f(uRes, w, h);
      drawTexture(texCtx, w, h, dpr);
      uploadBase();
      updateRepeats();
    };
    updateAtlas();
    resize();

    // Webフォント読み込み後にタイポを描き直す
    document.fonts.ready.then(function () {
      drawTexture(texCtx, texCanvas.width, texCanvas.height, dpr);
      uploadBase();
      updateAtlas();
    });

    // --- カーソル軌跡 ---
    var trail = new Float32Array(TRAIL_COUNT * 3);
    var trailIndex = 0;
    var lastX = -1;
    var lastY = -1;
    var pushPoint = function (clientX, clientY) {
      var rect = canvas.getBoundingClientRect();
      var x = (clientX - rect.left) / rect.width;
      var y = (clientY - rect.top) / rect.height;
      if (x < 0 || x > 1 || y < 0 || y > 1) return;
      var dx = clientX - lastX;
      var dy = clientY - lastY;
      if (dx * dx + dy * dy < 64) return;
      lastX = clientX;
      lastY = clientY;
      trail[trailIndex * 3] = x;
      trail[trailIndex * 3 + 1] = y;
      trail[trailIndex * 3 + 2] = 1;
      trailIndex = (trailIndex + 1) % TRAIL_COUNT;
    };
    var onMouseMove = function (e) {
      pushPoint(e.clientX, e.clientY);
    };
    var onTouchMove = function (e) {
      var t = e.touches[0];
      if (t) pushPoint(t.clientX, t.clientY);
    };
    window.addEventListener('mousemove', onMouseMove, { passive: true });
    window.addEventListener('touchmove', onTouchMove, { passive: true });

    // --- 描画ループ ---
    var visible = true;
    var start = performance.now();
    var render = function () {
      if (visible) {
        for (var i = 0; i < TRAIL_COUNT; i += 1) {
          trail[i * 3 + 2] *= 0.955;
        }
        gl.uniform1f(uTime, (performance.now() - start) / 1000);
        gl.uniform1f(uScrollX, window.scrollY * 0.25 * dpr);
        gl.uniform3fv(uTrail, trail);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      }
      requestAnimationFrame(render);
    };
    requestAnimationFrame(render);

    var observer = new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
    });
    observer.observe(canvas);

    window.addEventListener('resize', resize);
  });
})();
