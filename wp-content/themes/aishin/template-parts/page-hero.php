<?php
/**
 * 下層ページ共通ヒーロー（React版 PageHero.tsx の移植）
 * 英語見出しの1文字ずつのドロップイン＋浮遊ループ、背景の巨大アウトライン英字、
 * 物理演算のパズルピース型ワード（ドラッグ可能）で構成する。
 * アニメーションは assets/js/page-hero.js が担当。
 *
 * 使用: get_template_part( 'template-parts/page-hero', null, array( 'title' => 'SERVICE' ) );
 *   title_ja / lead は任意（React版の titleJa / lead。現状未使用だが仕様維持）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_title    = isset( $args['title'] ) ? $args['title'] : '';
$aishin_title_ja = isset( $args['title_ja'] ) ? $args['title_ja'] : '';
$aishin_lead     = isset( $args['lead'] ) ? $args['lead'] : '';
?>
<section class="page-hero" aria-label="<?php echo esc_attr( $aishin_title . ' ページタイトル' ); ?>">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => $aishin_title, 'side' => 'right' ) ); ?>

  <?php /* 落下ピースはトップページと共通の WORD_PIECES を使用（P-020） */ ?>
  <?php aishin_pieces_stage( 'page-hero__stage' ); ?>

  <div class="container page-hero__content">
    <p class="page-hero__eyebrow">AISHIN INC. — RECRUIT SITE</p>
    <h1 class="page-hero__title">
      <span class="page-hero__line"><?php
      foreach ( preg_split( '//u', $aishin_title, -1, PREG_SPLIT_NO_EMPTY ) as $aishin_ch ) {
			echo '<span class="page-hero__char">' . ( ' ' === $aishin_ch ? '&nbsp;' : esc_html( $aishin_ch ) ) . '</span>';
		}
		?></span>
    </h1>
    <?php if ( $aishin_title_ja ) : ?>
    <p class="page-hero__ja"><?php echo esc_html( $aishin_title_ja ); ?></p>
    <?php endif; ?>
    <?php if ( $aishin_lead ) : ?>
    <p class="page-hero__lead"><?php echo esc_html( $aishin_lead ); ?></p>
    <?php endif; ?>
  </div>
</section>
