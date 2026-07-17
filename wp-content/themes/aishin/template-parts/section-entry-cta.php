<?php
/**
 * ENTRY CTA セクション（React版 EntryCta.tsx の移植・全ページ共通で末尾に使用）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="entry" id="entry">
  <?php /* 装飾用の点線枠のみ（背景写真は配置しない方針: P-030） */ ?>
  <div class="entry__bg" aria-hidden="true"></div>
  <div class="container entry__inner" data-reveal>
    <p class="entry__eyebrow">JOIN US</p>
    <h2 class="entry__title" aria-label="ENTRY">
      <?php foreach ( str_split( 'ENTRY' ) as $aishin_ch ) : ?>
      <span class="entry__char"><?php echo esc_html( $aishin_ch ); ?></span>
      <?php endforeach; ?>
    </h2>
    <p class="entry__copy">
      あなたという、まだ見ぬピースを待っている。
      <br />
      新卒・第二新卒・35歳以下の中途採用、エントリー受付中。
    </p>
    <a href="<?php echo esc_url( home_url( '/entry/' ) ); ?>" class="entry__btn">
      ENTRY FORM <span aria-hidden="true">→</span>
    </a>
  </div>
</section>
