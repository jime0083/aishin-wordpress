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

require_once get_template_directory() . '/inc/puzzle-paths.php';
require_once get_template_directory() . '/inc/image-frame.php';
require_once get_template_directory() . '/inc/word-pieces.php';

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
	$js     = get_template_directory_uri() . '/assets/js';

	wp_enqueue_script( 'gsap', $vendor . '/gsap.min.js', array(), '3.15.0', true );
	wp_enqueue_script( 'gsap-scrolltrigger', $vendor . '/ScrollTrigger.min.js', array( 'gsap' ), '3.15.0', true );

	// 全ページ共通
	wp_enqueue_script( 'aishin-header', $js . '/header.js', array(), AISHIN_VERSION, true );
	wp_enqueue_script( 'aishin-cursor', $js . '/cursor.js', array(), AISHIN_VERSION, true );
	wp_enqueue_script( 'aishin-floating-bg', $js . '/floating-bg.js', array(), AISHIN_VERSION, true );
	wp_enqueue_script( 'aishin-animations', $js . '/animations.js', array( 'gsap', 'gsap-scrolltrigger' ), AISHIN_VERSION, true );

	// ヒーロー（物理演算）のあるページ
	if ( aishin_has_hero() ) {
		wp_enqueue_script( 'matter-js', $vendor . '/matter.min.js', array(), '0.20.0', true );
		wp_enqueue_script( 'aishin-physics', $js . '/physics-pieces.js', array( 'matter-js', 'gsap' ), AISHIN_VERSION, true );
	}

	// トップページ専用（ローダー / WebGL液体背景 / FVキネティックタイポ）
	if ( is_front_page() ) {
		wp_enqueue_script( 'aishin-loader', $js . '/loader.js', array( 'gsap' ), AISHIN_VERSION, true );
		wp_enqueue_script( 'aishin-liquid-bg', $js . '/liquid-bg.js', array(), AISHIN_VERSION, true );
		wp_enqueue_script( 'aishin-hero', $js . '/hero.js', array( 'gsap', 'aishin-physics', 'aishin-loader' ), AISHIN_VERSION, true );
	}

	// 下層固定ページ共通ヒーロー
	if ( is_page( array( 'service', 'works', 'career', 'entry' ) ) ) {
		wp_enqueue_script( 'aishin-page-hero', $js . '/page-hero.js', array( 'gsap', 'aishin-physics' ), AISHIN_VERSION, true );
	}

	// ENTRYフォーム
	if ( is_page( 'entry' ) ) {
		wp_enqueue_script( 'aishin-entry-form', $js . '/entry-form.js', array(), AISHIN_VERSION, true );
	}

	// インタビュー詳細
	if ( is_singular( 'interview' ) ) {
		wp_enqueue_script( 'aishin-interview', $js . '/interview.js', array( 'gsap', 'aishin-physics' ), AISHIN_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'aishin_enqueue_scripts' );

/**
 * <body> に付与する属性を出力する。
 * data-skew-targets: スクロール速度連動skew演出の対象セレクタ（animations.js が参照。
 * React版で各ページが useSubpageAnimations({ skewTargets }) に渡していた値と同一）
 */
function aishin_body_attrs() {
	$skew = '';
	if ( is_front_page() ) {
		$skew = '.mission__statement, .works__cards, .interview__cards, .career__list';
	} elseif ( is_page( 'service' ) ) {
		$skew = '.svc__points, .svc__steps';
	} elseif ( is_page( 'works' ) ) {
		$skew = '.wrk__rows';
	} elseif ( is_page( 'career' ) ) {
		$skew = '.crr__benefits';
	} elseif ( is_singular( 'interview' ) ) {
		$skew = '.itv-qa__body';
	}
	if ( $skew ) {
		echo ' data-skew-targets="' . esc_attr( $skew ) . '"';
	}
}

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
