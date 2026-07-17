<?php
/**
 * セクション背景の巨大アウトライン英字（React版 GiantWord.tsx の移植）
 * スクロールで横に流れる（animations.js の [data-giant] が担当）。
 *
 * 使用: get_template_part( 'template-parts/giant-word', null, array( 'text' => 'ABOUT', 'side' => 'left' ) );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_text = isset( $args['text'] ) ? $args['text'] : '';
$aishin_side = isset( $args['side'] ) ? $args['side'] : 'right';
?>
<span class="giant-word giant-word--<?php echo esc_attr( $aishin_side ); ?>" data-giant="<?php echo esc_attr( $aishin_side ); ?>" aria-hidden="true"><?php echo esc_html( $aishin_text ); ?></span>
