<?php
/**
 * アクセス直後のローディング演出（React版 Loader.tsx の移植・トップページのみ）
 * アニメーションは assets/js/loader.js が担当。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_brand = 'AISHIN';
?>
<div class="loader" aria-hidden="true">
  <div class="loader__panel loader__panel--orange"></div>
  <div class="loader__panel loader__panel--paper">
    <div class="loader__brand">
      <span class="loader__mark"></span>
      <?php foreach ( preg_split( '//u', $aishin_brand, -1, PREG_SPLIT_NO_EMPTY ) as $aishin_ch ) : ?>
      <span class="loader__brand-char"><?php echo esc_html( $aishin_ch ); ?></span>
      <?php endforeach; ?>
    </div>
    <p class="loader__count">
      <span class="loader__count-num">0</span>
      <span class="loader__count-unit">%</span>
    </p>
    <p class="loader__tag">INVENTING THE MISSING PIECE</p>
  </div>
</div>
