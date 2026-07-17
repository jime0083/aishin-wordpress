<?php
/**
 * aishin テーマ functions
 *
 * デザインの正は React 版（/Users/jime0083/aishin）。
 * CSSは無改変移植・JSはパラメータ転記で完全一致させる（CLAUDE.md 参照）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AISHIN_VERSION', '0.1.0' );

/**
 * テーマ基本設定
 *
 * 注意: title-tag サポートは意図的に使わない。
 * React版 index.html と同一の <title> を header.php で全ページ固定出力するため。
 */
function aishin_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'aishin_setup' );

/**
 * CSS読み込み
 * React版 main.tsx の import 順（global → home → subpage）を厳守する。
 */
function aishin_enqueue_styles() {
	$dir = get_template_directory_uri() . '/assets/css';
	wp_enqueue_style( 'aishin-global', $dir . '/global.css', array(), AISHIN_VERSION );
	wp_enqueue_style( 'aishin-home', $dir . '/home.css', array( 'aishin-global' ), AISHIN_VERSION );
	wp_enqueue_style( 'aishin-subpage', $dir . '/subpage.css', array( 'aishin-home' ), AISHIN_VERSION );
}
add_action( 'wp_enqueue_scripts', 'aishin_enqueue_styles' );

/**
 * ヒーロー（物理演算パズルピース）を持つページか。
 * matter-js とヒーロー系JSの読み込み条件に使う。404のみ非対象。
 */
function aishin_has_hero() {
	return is_front_page() || is_page() || is_singular( 'interview' );
}

/**
 * JSライブラリ読み込み（フッター・テーマ同梱）
 * バージョンはReact版の node_modules に実際にインストールされているものと一致:
 * gsap 3.15.0 / ScrollTrigger 3.15.0 / matter-js 0.20.0
 */
function aishin_enqueue_scripts() {
	$vendor = get_template_directory_uri() . '/assets/vendor';

	wp_enqueue_script( 'gsap', $vendor . '/gsap.min.js', array(), '3.15.0', true );
	wp_enqueue_script( 'gsap-scrolltrigger', $vendor . '/ScrollTrigger.min.js', array( 'gsap' ), '3.15.0', true );

	if ( aishin_has_hero() ) {
		wp_enqueue_script( 'matter-js', $vendor . '/matter.min.js', array(), '0.20.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'aishin_enqueue_scripts' );

/**
 * wp_head の不要な標準出力を除去する
 * （React版に存在しない出力を減らし、headをindex.htmlに近づける）
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

// ブロックエディタ用CSS等はテーマで使用しないため除去（デザイン汚染防止）
function aishin_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'aishin_dequeue_block_styles', 100 );
