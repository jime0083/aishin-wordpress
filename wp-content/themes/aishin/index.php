<?php
/**
 * フォールバックテンプレート
 * 実際の各ページは front-page.php / page-*.php / single-interview.php / 404.php を使用する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="coming-soon">
  <div class="container">
    <h1 class="coming-soon__title">AISHIN</h1>
    <p class="coming-soon__note">このページは現在準備中です。</p>
  </div>
</section>
<?php
get_footer();
