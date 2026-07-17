<?php
/**
 * トップページ ファーストビュー（React版 Hero.tsx の移植）
 * アニメーション（キネティックタイポ・物理演算の起動）は assets/js/hero.js が担当。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_title_lines  = array( 'まだ見ぬピースを', '発明する' );
$aishin_accent_chars = array( 'ピ', 'ー', 'ス' );
?>
<section class="hero" aria-label="ファーストビュー">
  <div class="hero__main">
    <canvas class="hero__canvas" aria-hidden="true"></canvas>

    <?php aishin_pieces_stage( 'hero__stage' ); ?>

    <div class="hero__content">
      <p class="hero__lead">CONSULTING VENTURE — AISHIN Inc.</p>
      <h1 class="hero__title">
        <?php foreach ( $aishin_title_lines as $aishin_line ) : ?>
        <span class="hero__line"><?php
        foreach ( preg_split( '//u', $aishin_line, -1, PREG_SPLIT_NO_EMPTY ) as $aishin_ch ) {
			printf(
				'<span class="hero__char%s">%s</span>',
				in_array( $aishin_ch, $aishin_accent_chars, true ) ? ' hero__char--accent' : '',
				esc_html( $aishin_ch )
			);
		}
		?></span>
        <?php endforeach; ?>
      </h1>
      <p class="hero__sub">
        Inventing the Missing Piece<span class="hero__sub-dot">.</span>
      </p>
    </div>

    <?php /* 回転する円形テキストバッジ（ENTRYへのショートカット） */ ?>
    <a href="<?php echo esc_url( home_url( '/entry/' ) ); ?>" class="hero__badge" aria-label="採用エントリーへ">
      <svg viewBox="0 0 120 120" class="hero__badge-svg">
        <defs>
          <path
            id="badge-circle"
            d="M 60,60 m -46,0 a 46,46 0 1,1 92,0 a 46,46 0 1,1 -92,0"
          ></path>
        </defs>
        <text class="hero__badge-text">
          <textPath href="#badge-circle">JOIN OUR TEAM ・ AISHIN INC. ・ ENTRY ・</textPath>
        </text>
      </svg>
      <span class="hero__badge-arrow" aria-hidden="true">→</span>
    </a>

    <div class="hero__scroll-cue" aria-hidden="true">
      <span class="hero__scroll-line"></span>
      SCROLL
    </div>
  </div>

  <?php get_template_part( 'template-parts/marquee' ); ?>
</section>
