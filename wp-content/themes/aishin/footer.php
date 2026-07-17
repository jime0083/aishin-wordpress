<?php
/**
 * 共通フッター（React版 Footer.tsx の移植）
 * INTERVIEW は一覧ページ廃止に伴いリンクにない。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_footer_links = array(
	array( home_url( '/' ), 'ABOUT' ),
	array( home_url( '/service/' ), 'SERVICE' ),
	array( home_url( '/works/' ), 'WORKS' ),
	array( home_url( '/career/' ), 'CAREER' ),
	array( home_url( '/entry/' ), 'ENTRY' ),
);
?>
    </main>
    <footer class="footer">
      <?php /* 他セクションと同じ .container グリッドに揃える */ ?>
      <div class="container">
        <div class="footer__top">
          <div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo" aria-label="株式会社アイシン">
              <img
                src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-footer.png' ); ?>"
                alt="株式会社アイシン"
                class="footer__logo-img"
              />
            </a>
          </div>
          <nav class="footer__nav" aria-label="フッターナビゲーション">
            <?php foreach ( $aishin_footer_links as $aishin_link ) : ?>
            <a href="<?php echo esc_url( $aishin_link[0] ); ?>" class="footer__link"><?php echo esc_html( $aishin_link[1] ); ?></a>
            <?php endforeach; ?>
          </nav>
        </div>
        <div class="footer__bottom">
          <small>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Aishin Inc. All Rights Reserved.</small>
        </div>
      </div>
    </footer>
    <?php wp_footer(); ?>
  </body>
</html>
