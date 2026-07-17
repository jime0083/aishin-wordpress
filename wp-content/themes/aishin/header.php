<?php
/**
 * 共通ヘッダー
 * <head> は React版 index.html と同一のタグ構成。
 * ヘッダーナビは React版 Header.tsx の移植（開閉・スクロール挙動は assets/js/header.js）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_images = get_template_directory_uri() . '/assets/images';

/*
 * ナビゲーション（Header.tsx の NAV_ITEMS と同一。
 * INTERVIEW は一覧ページ廃止に伴いナビにない）
 */
$aishin_nav_items = array(
	array( home_url( '/' ), 'ABOUT', is_front_page() ),
	array( home_url( '/service/' ), 'SERVICE', is_page( 'service' ) ),
	array( home_url( '/works/' ), 'WORKS', is_page( 'works' ) ),
	array( home_url( '/career/' ), 'CAREER', is_page( 'career' ) ),
);
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="株式会社アイシンは「まだ見ぬピースを発明する」をテーマに、戦略コンサルティング・DX支援・新規事業開発を手がけるベンチャー企業です。新卒・若手採用に力を入れています。"
    />
    <meta name="theme-color" content="#FBF7F2" />
    <link rel="icon" type="image/png" href="<?php echo esc_url( $aishin_images . '/logo-mark.png' ); ?>" />
    <link rel="apple-touch-icon" href="<?php echo esc_url( $aishin_images . '/logo-mark.png' ); ?>" />
    <title>株式会社アイシン | まだ見ぬピースを発明する</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&display=swap"
      rel="stylesheet"
    />
    <?php wp_head(); ?>
  </head>
  <body<?php aishin_body_attrs(); ?>>
    <?php get_template_part( 'template-parts/floating-bg' ); ?>
    <header class="header">
      <div class="header__inner">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo" aria-label="AISHIN トップページ">
          <?php /* [IMG-LOGO] ロゴ画像に差し替え予定（React版と同じくテキスト+マークで代用） */ ?>
          <span class="header__logo-mark" aria-hidden="true"></span>AISHIN</a>

        <nav class="header__nav" aria-label="メインナビゲーション">
          <?php foreach ( $aishin_nav_items as $aishin_item ) : list( $aishin_url, $aishin_label, $aishin_active ) = $aishin_item; ?>
          <a href="<?php echo esc_url( $aishin_url ); ?>" class="header__link<?php echo $aishin_active ? ' active' : ''; ?>"<?php echo $aishin_active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $aishin_label ); ?></a>
          <?php endforeach; ?>
          <a href="<?php echo esc_url( home_url( '/entry/' ) ); ?>" class="header__entry">ENTRY</a>
        </nav>

        <button type="button" class="header__burger" aria-label="メニューを開く" aria-expanded="false">
          <span></span>
          <span></span>
        </button>
      </div>

      <div class="header__drawer" aria-hidden="true">
        <nav aria-label="モバイルナビゲーション">
          <?php foreach ( $aishin_nav_items as $aishin_item ) : list( $aishin_url, $aishin_label, $aishin_active ) = $aishin_item; ?>
          <a href="<?php echo esc_url( $aishin_url ); ?>" class="header__drawer-link<?php echo $aishin_active ? ' active' : ''; ?>" style="transition-delay: 0ms;"<?php echo $aishin_active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $aishin_label ); ?></a>
          <?php endforeach; ?>
          <a href="<?php echo esc_url( home_url( '/entry/' ) ); ?>" class="header__drawer-link header__drawer-link--entry" style="transition-delay: 0ms;">ENTRY →</a>
        </nav>
      </div>
    </header>
    <main>
