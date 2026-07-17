<?php
/**
 * 共通ヘッダー
 * <head> は React版 index.html と同一のタグ構成を出力する（Phase 1.5）。
 * ヘッダーナビ本体（Header.tsx の移植）は Phase 2.1 で実装する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_images = get_template_directory_uri() . '/assets/images';
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
  <body>
    <?php
    // Phase 2 で CustomCursor / FloatingBg / ヘッダーナビをここに実装する
    ?>
    <main>
