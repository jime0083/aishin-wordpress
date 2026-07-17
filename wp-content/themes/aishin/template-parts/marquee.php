<?php
/**
 * セクション間を横断する無限ループのキネティックテキスト帯
 * （React版 Marquee.tsx の移植。text×4リピート×2スパン）
 *
 * 使用: get_template_part( 'template-parts/marquee', null, array( 'text' => '...', 'reverse' => true, 'tilt' => true ) );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_text    = isset( $args['text'] ) ? $args['text'] : 'INVENTING THE MISSING PIECE — AISHIN Inc. — ';
$aishin_reverse = ! empty( $args['reverse'] );
$aishin_tilt    = ! empty( $args['tilt'] );

$aishin_class = 'marquee';
if ( $aishin_reverse ) {
	$aishin_class .= ' marquee--reverse';
}
if ( $aishin_tilt ) {
	$aishin_class .= ' marquee--tilt';
}
$aishin_repeated = str_repeat( $aishin_text, 4 );
?>
<div class="<?php echo esc_attr( $aishin_class ); ?>" aria-hidden="true">
  <div class="marquee__track">
    <span class="marquee__text"><?php echo esc_html( $aishin_repeated ); ?></span>
    <span class="marquee__text"><?php echo esc_html( $aishin_repeated ); ?></span>
  </div>
</div>
