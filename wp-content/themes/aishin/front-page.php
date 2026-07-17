<?php
/**
 * トップページ（仮）
 * Phase 3 で Home.tsx（Loader / Hero / Mission / About / WorksTeaser /
 * InterviewTeaser / CareerTeaser / Marquee / EntryCta）を実装する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="coming-soon">
  <div class="container">
    <h1 class="coming-soon__title">AISHIN</h1>
    <p class="coming-soon__jp">まだ見ぬピースを発明する</p>
    <p class="coming-soon__note">トップページは Phase 3 で実装します（テーマ雛形の仮表示）。</p>
  </div>
</section>
<?php
get_footer();
