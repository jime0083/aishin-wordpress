<?php
/**
 * 固定ページ・表示設定のセットアップスクリプト（WP-CLI用・冪等）
 *
 * 実行: docker compose run --rm wpcli eval-file /seed/seed-pages.php
 * （本番Bitnami環境: wp eval-file <パス> --path=/opt/bitnami/wordpress）
 *
 * - 固定ページ service / works / career / entry / home を作成
 *   （page-{slug}.php テンプレートがスラッグで自動適用される）
 * - フロントページ表示を固定ページ home に設定
 * - パーマリンク /%postname%/
 * - サイトタイトル・キャッチフレーズ設定
 * - デフォルトコンテンツ（Hello World / サンプルページ）削除
 */

if ( ! defined( 'ABSPATH' ) ) {
	echo "WP-CLI の eval-file で実行してください\n";
	exit( 1 );
}

$pages = array(
	'home'    => 'トップ',
	'service' => 'SERVICE',
	'works'   => 'WORKS',
	'career'  => 'CAREER',
	'entry'   => 'ENTRY',
);

$page_ids = array();
foreach ( $pages as $slug => $title ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $existing ) {
		$page_ids[ $slug ] = $existing->ID;
		WP_CLI::log( "既存: 固定ページ {$slug}（ID {$existing->ID}）" );
		continue;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_name'   => $slug,
			'post_title'  => $title,
			'post_status' => 'publish',
		)
	);
	if ( is_wp_error( $id ) || ! $id ) {
		WP_CLI::error( "固定ページの作成に失敗: {$slug}" );
	}
	$page_ids[ $slug ] = $id;
	WP_CLI::log( "作成: 固定ページ {$slug}（ID {$id}）" );
}

// フロントページ設定
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_ids['home'] );

// サイト基本設定
update_option( 'blogname', '株式会社アイシン' );
update_option( 'blogdescription', '' );

// パーマリンク
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();

// デフォルトコンテンツの削除（Hello World: ID1 / サンプルページ）
$hello = get_post( 1 );
if ( $hello && 'post' === $hello->post_type && 'trash' !== $hello->post_status ) {
	wp_delete_post( 1, true );
	WP_CLI::log( '削除: Hello World 投稿' );
}
$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );
if ( $sample ) {
	wp_delete_post( $sample->ID, true );
	WP_CLI::log( '削除: サンプルページ' );
}
// 日本語版デフォルトの「サンプルページ」スラッグにも対応
$sample_ja = get_page_by_path( urlencode( 'サンプルページ' ), OBJECT, 'page' );
if ( $sample_ja ) {
	wp_delete_post( $sample_ja->ID, true );
	WP_CLI::log( '削除: サンプルページ（日本語スラッグ）' );
}

WP_CLI::success( '固定ページと表示設定のセットアップが完了しました。' );
