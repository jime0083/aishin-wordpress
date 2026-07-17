<?php
/**
 * カスタム投稿タイプ「社員インタビュー」（interview）
 *
 * React版 /interview/:id（id = 01/02/03）と同じURLになるよう、
 * スラッグを 01/02/03 とし rewrite slug は 'interview' を使用する。
 * 一覧（アーカイブ）ページは持たない（React版仕様: トップのカードから詳細へ直接遷移）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function aishin_register_cpt_interview() {
	register_post_type(
		'interview',
		array(
			'labels'       => array(
				'name'          => '社員インタビュー',
				'singular_name' => '社員インタビュー',
				'add_new'       => '新規追加',
				'add_new_item'  => 'インタビューを追加',
				'edit_item'     => 'インタビューを編集',
				'all_items'     => 'インタビュー一覧',
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array(
				'slug'       => 'interview',
				'with_front' => false,
			),
			'supports'     => array( 'title' ),
			'menu_icon'    => 'dashicons-groups',
			'show_in_rest' => false,
		)
	);
}
add_action( 'init', 'aishin_register_cpt_interview' );

/**
 * ACFフィールド値の取得ラッパー（ACF未有効時も致命的エラーにしない）
 */
function aishin_field( $name, $post_id = false ) {
	if ( function_exists( 'get_field' ) ) {
		return get_field( $name, $post_id );
	}
	return get_post_meta( $post_id ? $post_id : get_the_ID(), $name, true );
}

/**
 * インタビューを表示順（スラッグ 01→03 昇順）で全件取得する。
 * トップの InterviewTeaser / 詳細の「ほかの社員も知る。」で共通使用。
 *
 * @param int $exclude 除外する投稿ID（詳細ページで自分自身を除く用。0で除外なし）
 * @return WP_Post[]
 */
function aishin_get_interviews( $exclude = 0 ) {
	$posts = get_posts(
		array(
			'post_type'      => 'interview',
			'posts_per_page' => -1,
			'orderby'        => 'name', // post_name（スラッグ）順
			'order'          => 'ASC',
			'post__not_in'   => $exclude ? array( $exclude ) : array(),
		)
	);
	return $posts;
}

/**
 * インタビューのポートレート画像フレームを出力する
 * （ACF画像フィールド（添付ID）→ URL 解決。フレームIDはファイル名から導出）
 *
 * @param int    $post_id 投稿ID
 * @param string $ratio   aspect-ratio
 * @param string $shape   切り抜き形状（null可）
 */
function aishin_interview_portrait( $post_id, $ratio = '3 / 4', $shape = null ) {
	$attachment_id = aishin_field( 'portrait_image', $post_id );
	$label         = (string) aishin_field( 'portrait_label', $post_id );
	$src           = $attachment_id ? wp_get_attachment_image_url( (int) $attachment_id, 'full' ) : null;
	$frame_id      = $src ? pathinfo( wp_basename( $src ), PATHINFO_FILENAME ) : 'IMG-PORTRAIT';

	aishin_image_frame(
		array(
			'id'    => $frame_id,
			'label' => $label,
			'ratio' => $ratio,
			'shape' => $shape,
			'src'   => $src,
		)
	);
}
