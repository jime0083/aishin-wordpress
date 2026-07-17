<?php
/**
 * トップページ MISSION セクション（React版 Mission.tsx の移植）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_statement_lines = array( '世界は、まだ未完成。', 'ピースは、発明するもの。' );
?>
<section class="mission section" id="mission">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'MISSION', 'side' => 'right' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num">01</span> MISSION
    </p>
    <h2 class="mission__statement">
      <?php foreach ( $aishin_statement_lines as $aishin_line ) : ?>
      <span class="mission__line" data-reveal-line>
        <span class="mission__line-inner"><?php echo esc_html( $aishin_line ); ?></span>
      </span>
      <?php endforeach; ?>
    </h2>
    <div class="mission__body" data-reveal>
      <p>
        株式会社アイシンは、戦略コンサルティングを起点に、クライアントの事業に
        「まだ存在しない解決策＝まだ見ぬピース」を生み出すベンチャー企業です。
      </p>
      <p>
        既存のフレームワークをなぞるだけのコンサルティングはしない。
        現場に飛び込み、若いエネルギーとロジックで、
        誰も見たことのない一手を発明する。それが私たちの仕事です。
      </p>
    </div>
  </div>
</section>
