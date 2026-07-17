<?php
/**
 * 画像フレーム出力関数（React版 src/components/ImagePlaceholder.tsx の移植）
 *
 * 対応表に ID が登録されていれば実画像を、なければ枠（プレースホルダー）を出力する。
 * shape=puzzle / puzzle-left は外側ラッパーでSVGクリップし、内側の .img-ph は
 * スクロール連動マスクリビール（clip-path inset）の対象のまま残す（React版と同構造）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * プレースホルダーID → 実画像URL の対応表（React版 src/data/images.ts と同一の22件）
 */
function aishin_image_sources() {
	static $sources = null;
	if ( null === $sources ) {
		$ids = array(
			// トップ ABOUT（若さは武器だ）
			'IMG-01',
			'IMG-02',
			// トップ CAREER ティーザー
			'IMG-12',
			// SERVICE 01 戦略コンサル / 02 DX支援 / 03 新規事業開発
			'IMG-S01',
			'IMG-S02',
			'IMG-S03',
			// WORKS 01〜05
			'IMG-W01',
			'IMG-W02',
			'IMG-W03',
			'IMG-W04',
			'IMG-W05',
			// 社員ポートレート（トップINTERVIEW・詳細ページ共通）
			'IMG-09',
			'IMG-10',
			'IMG-11',
			// INTERVIEW 記事写真
			'IMG-I01',
			'IMG-I02',
			'IMG-I03',
			'IMG-I04',
			'IMG-I05',
			'IMG-I06',
			// CAREER
			'IMG-C01',
			'IMG-C02',
		);
		$base    = get_template_directory_uri() . '/assets/images/opt/';
		$sources = array();
		foreach ( $ids as $id ) {
			$sources[ $id ] = $base . $id . '.jpg';
		}
	}
	return $sources;
}

/**
 * 画像フレームを出力する。
 *
 * @param array $args {
 *   @type string $id    プレースホルダーID（例 'IMG-01'）
 *   @type string $label ラベル（alt / aria-label に使用）
 *   @type string $ratio aspect-ratio（例 '4 / 3'）
 *   @type string $shape 'puzzle'|'puzzle-left'|'ellipse'|'blob1'|'blob2'|'arch'|'pill'|null
 *   @type string $src   明示的な画像URL（未指定なら $id から対応表を参照）
 *   @type string $class 追加クラス
 * }
 */
function aishin_image_frame( $args ) {
	$id    = isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	$ratio = isset( $args['ratio'] ) ? $args['ratio'] : '4 / 3';
	$shape = isset( $args['shape'] ) ? $args['shape'] : null;
	$src   = isset( $args['src'] ) ? $args['src'] : null;
	$class = isset( $args['class'] ) ? $args['class'] : '';

	$sources      = aishin_image_sources();
	$resolved_src = $src ? $src : ( isset( $sources[ $id ] ) ? $sources[ $id ] : null );

	// 内側コンテンツ（実画像 or プレースホルダー枠）
	if ( $resolved_src ) {
		$inner = sprintf(
			'<img class="img-ph__img" src="%s" alt="%s" loading="lazy" decoding="async" />',
			esc_url( $resolved_src ),
			esc_attr( $label )
		);
	} else {
		$inner = '<svg class="img-ph__cross" aria-hidden="true">'
			. '<line x1="0" y1="0" x2="100%" y2="100%"></line>'
			. '<line x1="100%" y1="0" x2="0" y2="100%"></line>'
			. '</svg>'
			. '<span class="img-ph__id">' . esc_html( $id ) . '</span>'
			. '<span class="img-ph__label">' . esc_html( $label ) . '</span>';
	}

	$aria = 'role="img" aria-label="' . esc_attr( '画像プレースホルダー: ' . $label ) . '"';

	// パズルピース型: 外側ラッパーでSVGクリップ（React版と同一構造）
	if ( 'puzzle' === $shape || 'puzzle-left' === $shape ) {
		$clip_id = 'puzzle-clip-' . $id;
		$path    = ( 'puzzle-left' === $shape ) ? AISHIN_PUZZLE_PATH_LEFT : AISHIN_PUZZLE_PATH;
		printf(
			'<div class="img-ph-puzzle %1$s" style="aspect-ratio: %2$s; clip-path: url(#%3$s);">'
			. '<svg width="0" height="0" aria-hidden="true" focusable="false"><defs>'
			. '<clipPath id="%3$s" clipPathUnits="objectBoundingBox"><path d="%4$s"></path></clipPath>'
			. '</defs></svg>'
			. '<div class="img-ph" %5$s>%6$s</div>'
			. '</div>',
			esc_attr( trim( $class ) ),
			esc_attr( $ratio ),
			esc_attr( $clip_id ),
			esc_attr( $path ),
			$aria, // 内部生成のためエスケープ済み
			$inner
		);
		return;
	}

	printf(
		'<div class="img-ph %1$s %2$s" style="aspect-ratio: %3$s" %4$s>%5$s</div>',
		$shape ? esc_attr( 'img-ph--' . $shape ) : '',
		esc_attr( trim( $class ) ),
		esc_attr( $ratio ),
		$aria,
		$inner
	);
}
