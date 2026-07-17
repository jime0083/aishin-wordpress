<?php
/**
 * ページ全体の背景アニメーションレイヤー（React版 FloatingBg.tsx の移植）
 * SHAPES 11個の定義値は React 版から完全転記。
 * スクロール視差は assets/js/floating-bg.js が data-speed / data-top を読んで適用する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_shapes = array(
	array( 'glow', '4%', 18, 0.12, 1 ),
	array( 'glow2', '72%', 68, 0.2, 2 ),
	array( 'glow', '58%', 8, 0.3, 3 ),
	array( 'ring', '85%', 30, 0.42, 1 ),
	array( 'ring-dash', '10%', 82, 0.26, 2 ),
	array( 'ring', '30%', 50, 0.5, 3 ),
	array( 'square', '78%', 90, 0.6, 1 ),
	array( 'square2', '20%', 4, 0.55, 2 ),
	array( 'square', '46%', 74, 0.36, 3 ),
	array( 'plus', '90%', 58, 0.68, 2 ),
	array( 'plus', '38%', 26, 0.46, 1 ),
);
?>
<div class="floating-bg" aria-hidden="true">
	<?php foreach ( $aishin_shapes as $aishin_s ) : list( $aishin_kind, $aishin_left, $aishin_top, $aishin_speed, $aishin_float ) = $aishin_s; ?>
	<div class="floating-bg__item" style="left: <?php echo esc_attr( $aishin_left ); ?>; top: <?php echo esc_attr( $aishin_top ); ?>%;" data-speed="<?php echo esc_attr( $aishin_speed ); ?>" data-top="<?php echo esc_attr( $aishin_top ); ?>">
		<span class="floating-bg__shape floating-bg__shape--<?php echo esc_attr( $aishin_kind ); ?> f-float-<?php echo esc_attr( $aishin_float ); ?>"></span>
	</div>
	<?php endforeach; ?>
</div>
