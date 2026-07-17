<?php
/**
 * FVで落下する「言葉のピース」（React版 src/components/WordPiece.tsx の移植）
 * トップのHeroと下層のPageHeroで共通使用し、内容を統一する（P-020）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 落下ピースの一覧（テキスト / 配色バリエーション）。React版 WORD_PIECES と同一。
 */
function aishin_word_pieces() {
	return array(
		array( 'MISSION', 'solid' ),
		array( 'DX', 'yellow' ),
		array( 'PASSION', 'outline' ),
		array( 'LOGIC', 'white' ),
		array( 'INSIGHT', 'solid' ),
		array( 'TEAM', 'outline' ),
		array( 'GROWTH', 'yellow' ),
		array( 'IDEA', 'white' ),
		array( 'VISION', 'outline' ),
		array( 'CHANGE', 'solid' ),
		array( '?', 'piece' ),
	);
}

/**
 * 1ピースを出力（WordPiece.tsx のレンダリング結果と同一構造）
 */
function aishin_word_piece( $text, $variant ) {
	printf(
		'<div class="hero__piece hero__piece--%1$s">'
		. '<svg class="hero__piece-svg" viewBox="0 0 1 1" preserveAspectRatio="none" aria-hidden="true" focusable="false">'
		. '<path d="%2$s" vector-effect="non-scaling-stroke"></path>'
		. '</svg>'
		. '<span class="hero__piece-text">%3$s</span>'
		. '</div>',
		esc_attr( $variant ),
		esc_attr( AISHIN_PUZZLE_PATH ),
		esc_html( $text )
	);
}

/**
 * WORD_PIECES 全11個を出力する（Hero / PageHero / InterviewDetail 共通）
 */
function aishin_render_word_pieces() {
	foreach ( aishin_word_pieces() as $piece ) {
		aishin_word_piece( $piece[0], $piece[1] );
	}
}

/**
 * 物理演算ステージ（ピースのラッパー含む）を出力する。
 * reduced-motion 時の hero__pieces--static クラスは physics-pieces.js が付与する
 * （React版はレンダリング時に matchMedia で判定するが、判定タイミングは同じ初期表示前）。
 *
 * @param string $stage_class ステージのクラス名（'hero__stage' or 'page-hero__stage'）
 */
function aishin_pieces_stage( $stage_class ) {
	?>
	<div class="<?php echo esc_attr( $stage_class ); ?>" data-cursor-label="DRAG">
		<div class="hero__pieces" aria-hidden="true">
			<?php aishin_render_word_pieces(); ?>
		</div>
	</div>
	<?php
}
