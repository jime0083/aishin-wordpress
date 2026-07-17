<?php
/**
 * ENTRYページ（React版 Entry.tsx の移植）
 * 固定ページのスラッグ 'entry' に自動適用される。
 *
 * 送信はダミー動作（実送信なし・「送信できません」の案内画面を表示）。
 * バリデーション・画面切替は assets/js/entry-form.js が担当
 * （将来バックエンドや外部フォームサービスに接続できるよう送信処理は1関数に集約）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_entry_types = array( '新卒採用', '中途採用', 'インターン', 'カジュアル面談', 'その他' );

$aishin_sources = array( '就活サイト', '求人媒体', 'SNS', 'ニュースサイト', '知人からの紹介', 'その他' );

/* トレース元の個人情報保護方針の主要項目 */
$aishin_privacy_items = array(
	'個人情報の管理: ご提供いただいた個人情報は、正確かつ最新の状態に保ち、不正アクセス・紛失・改ざん・漏洩を防止するため適切に管理します。',
	'個人情報の利用目的: ご入力いただいた情報は、採用選考のご連絡・ご案内のためにのみ利用します。',
	'第三者への開示・提供の禁止: ご本人の同意がある場合または法令に基づく場合を除き、個人情報を第三者に開示・提供しません。',
	'安全対策: 個人情報の正確性および安全性確保のために、セキュリティに万全の対策を講じています。',
	'ご本人の照会: ご本人が個人情報の照会・修正・削除を希望される場合は、ご本人であることを確認のうえ対応します。',
	'法令・規範の遵守と見直し: 保有する個人情報に関して適用される日本の法令を遵守するとともに、本方針の内容を適宜見直し、改善に努めます。',
);

get_header();

get_template_part( 'template-parts/page-hero', null, array( 'title' => 'ENTRY' ) );
?>

<section class="section ent">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'JOIN US', 'side' => 'right' ) ); ?>
  <div class="container">
    <?php /* 送信後画面（ポートフォリオサイトのため実送信は行わない: P-026）。JSが表示を切り替える */ ?>
    <div class="ent__done" data-reveal style="display: none;">
      <p class="section__eyebrow">NOTICE</p>
      <h2 class="section__title">
        このポートフォリオサイトからは<span class="u-accent">送信できません</span>。
      </h2>
      <p class="ent__done-body">
        当サイトはポートフォリオとして制作したデモサイトのため、
        ご入力いただいたエントリー内容はどこにも送信されません。ご了承ください。
      </p>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-line">
        ← BACK TO TOP
      </a>
    </div>

    <div class="ent__main">
      <p class="section__eyebrow" data-reveal>
        ENTRY FORM
      </p>
      <h2 class="section__title">エントリーフォーム</h2>
      <div class="ent__lead" data-reveal>
        <p>
          「新卒採用」「中途採用」「インターン」など、お気軽にエントリーください。
          内容確認後、採用担当者よりご連絡差し上げます。
        </p>
        <p class="ent__note">
          ※内容によってはご返信を控えさせていただく場合もございますので予めご了承くださいませ。
        </p>
      </div>

      <form class="ent__form" novalidate data-reveal>
        <div class="ent__field">
          <label for="entryType">
            エントリーの種類 <span class="ent__required">必須</span>
          </label>
          <select id="entryType" data-field="entryType">
            <option value="">選択してください</option>
            <?php foreach ( $aishin_entry_types as $aishin_t ) : ?>
            <option value="<?php echo esc_attr( $aishin_t ); ?>"><?php echo esc_html( $aishin_t ); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="ent__field">
          <label for="source">
            当社を知ったきっかけ <span class="ent__required">必須</span>
          </label>
          <select id="source" data-field="source">
            <option value="">選択してください</option>
            <?php foreach ( $aishin_sources as $aishin_s ) : ?>
            <option value="<?php echo esc_attr( $aishin_s ); ?>"><?php echo esc_html( $aishin_s ); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="ent__field">
          <label for="name">
            お名前 <span class="ent__required">必須</span>
          </label>
          <input
            id="name"
            type="text"
            autocomplete="name"
            placeholder="例）愛信 太郎"
            data-field="name"
          />
        </div>

        <div class="ent__field">
          <label for="affiliation">
            現在のご所属（学校名・会社名） <span class="ent__required">必須</span>
          </label>
          <input
            id="affiliation"
            type="text"
            autocomplete="organization"
            placeholder="例）〇〇大学 〇〇学部 / 株式会社〇〇"
            data-field="affiliation"
          />
        </div>

        <div class="ent__field">
          <label for="phone">
            電話番号 <span class="ent__required">必須</span>
          </label>
          <input
            id="phone"
            type="tel"
            autocomplete="tel"
            placeholder="例）09012345678"
            data-field="phone"
          />
        </div>

        <div class="ent__field">
          <label for="email">
            メールアドレス <span class="ent__required">必須</span>
          </label>
          <input
            id="email"
            type="email"
            autocomplete="email"
            placeholder="例）taro.aishin@example.com"
            data-field="email"
          />
        </div>

        <div class="ent__field">
          <label for="message">
            志望動機・メッセージ <span class="ent__required">必須</span>
          </label>
          <textarea
            id="message"
            rows="7"
            placeholder="志望動機やご質問など、自由にご記入ください"
            data-field="message"
          ></textarea>
        </div>

        <?php /* 個人情報の取り扱い */ ?>
        <div class="ent__privacy">
          <h3>個人情報の取り扱いについて</h3>
          <ol>
            <?php foreach ( $aishin_privacy_items as $aishin_item ) : ?>
            <li><?php echo esc_html( $aishin_item ); ?></li>
            <?php endforeach; ?>
          </ol>
        </div>

        <label class="ent__agree">
          <input type="checkbox" id="agree" />
          個人情報の取り扱いに同意する
        </label>

        <button type="submit" class="ent__submit" disabled>
          同意して送信する <span aria-hidden="true">→</span>
        </button>
        <p class="ent__submit-note">
          全ての必須項目の入力と個人情報の取り扱いへの同意が必要です
        </p>
      </form>
    </div>
  </div>
</section>

<?php
get_footer();
