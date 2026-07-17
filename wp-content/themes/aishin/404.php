<?php
/**
 * 404ページ（React版 ComingSoon.tsx（NOT FOUND表示）の移植）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="coming-soon">
  <div class="container">
    <h1 class="coming-soon__title">NOT FOUND</h1>
    <p class="coming-soon__jp">ページが見つかりません</p>
    <p class="coming-soon__note">このページは現在準備中です。</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-line">
      ← BACK TO TOP
    </a>
  </div>
</section>
<?php
get_footer();
