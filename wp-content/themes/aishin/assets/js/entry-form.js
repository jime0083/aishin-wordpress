/**
 * エントリーフォームのバリデーションとダミー送信（React版 Entry.tsx の移植）
 * - blur で touched 管理、エラー文言・活性制御は React 版と同一
 * - 送信は実際には行わず「送信できません」画面に切り替える（P-026）
 *   将来の実送信は submitForm() にバックエンド/外部フォームサービス接続を追加する
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.ent__form');
    if (!form) return;

    var main = document.querySelector('.ent__main');
    var done = document.querySelector('.ent__done');
    var agreeInput = document.getElementById('agree');
    var submitBtn = form.querySelector('.ent__submit');
    var submitNote = form.querySelector('.ent__submit-note');

    var fields = Array.prototype.slice.call(form.querySelectorAll('[data-field]'));
    var touched = {};

    /** 各項目のバリデーション（エラーメッセージを返す。問題なければ空文字） */
    var validateField = function (key, value) {
      var trimmed = value.trim();
      if (trimmed === '') {
        if (key === 'entryType' || key === 'source') return '選択してください';
        return '入力してください';
      }
      if (key === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmed)) {
        return 'メールアドレスの形式が正しくありません';
      }
      if (key === 'phone') {
        var digits = trimmed.replace(/[^0-9]/g, '');
        if (!/^[0-9+\-() ]+$/.test(trimmed) || digits.length < 10 || digits.length > 11) {
          return '電話番号の形式が正しくありません（半角数字10〜11桁）';
        }
      }
      return '';
    };

    var fieldError = function (el) {
      var key = el.dataset.field;
      return touched[key] ? validateField(key, el.value) : '';
    };

    var isValid = function () {
      return fields.every(function (el) {
        return validateField(el.dataset.field, el.value) === '';
      });
    };

    var canSubmit = function () {
      return isValid() && agreeInput.checked;
    };

    /** エラー表示・送信ボタン活性・注意文の表示を更新（React版のレンダリングに相当） */
    var render = function () {
      fields.forEach(function (el) {
        var wrap = el.closest('.ent__field');
        var existing = wrap.querySelector('.ent__error');
        var message = fieldError(el);
        if (message) {
          if (!existing) {
            existing = document.createElement('p');
            existing.className = 'ent__error';
            wrap.appendChild(existing);
          }
          existing.textContent = message;
        } else if (existing) {
          existing.remove();
        }
      });
      var ok = canSubmit();
      submitBtn.disabled = !ok;
      submitNote.style.display = ok ? 'none' : '';
    };

    fields.forEach(function (el) {
      el.addEventListener('input', render);
      el.addEventListener('change', render);
      el.addEventListener('blur', function () {
        touched[el.dataset.field] = true;
        render();
      });
    });
    agreeInput.addEventListener('change', render);

    /**
     * ダミー送信: 実際の送信処理は行わない
     * （将来ここでAPI/外部フォームサービスに接続する）
     */
    var submitForm = function () {
      main.style.display = 'none';
      done.style.display = '';
      window.scrollTo(0, 0);
    };

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!canSubmit()) return;
      submitForm();
    });
  });
})();
