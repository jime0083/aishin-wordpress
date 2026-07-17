<?php
/**
 * トップページ（React版 Home.tsx の移植）
 * Loader → Hero → Mission → About → WorksTeaser → InterviewTeaser
 * → CareerTeaser → Marquee → EntryCta
 * （SERVICEセクションは下層ページ化に伴い削除済み: React版と同じ構成）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/loader' );
get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/section-mission' );
get_template_part( 'template-parts/section-about' );
get_template_part( 'template-parts/section-works-teaser' );
get_template_part( 'template-parts/section-interview-teaser' );
get_template_part( 'template-parts/section-career-teaser' );
get_template_part(
	'template-parts/marquee',
	null,
	array(
		'text'    => 'JOIN US — INVENT YOUR OWN PIECE — ',
		'reverse' => true,
		'tilt'    => true,
	)
);
get_template_part( 'template-parts/section-entry-cta' );

get_footer();
