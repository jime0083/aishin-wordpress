<?php
/**
 * トップページ WORKS ティーザー（React版 WorksTeaser.tsx の移植）
 * 画像は WORKS ページ（IMG-W01〜W03）と連動させて同じ実績・同じ画像を使う。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_works = array(
	array(
		'tag'       => 'MANUFACTURING',
		'title'     => '老舗製造業のDXで生産性132%を実現',
		'img_id'    => 'IMG-W01',
		'img_label' => '製造業の工場・生産ラインの風景',
	),
	array(
		'tag'       => 'RETAIL',
		'title'     => '全国120店舗の小売チェーンの購買体験を再設計',
		'img_id'    => 'IMG-W02',
		'img_label' => '小売店舗・売場の風景',
	),
	array(
		'tag'       => 'STARTUP',
		'title'     => 'シリーズAスタートアップの新規事業を0→1で共創',
		'img_id'    => 'IMG-W03',
		'img_label' => 'スタートアップのオフィス・開発風景',
	),
);
?>
<section class="works section" id="works">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'WORKS', 'side' => 'left' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num">03</span> WORKS
    </p>
    <div class="section__head" data-reveal>
      <h2 class="section__title">
        成果で語る。<span class="u-accent">実績</span>の一部。
      </h2>
      <a href="<?php echo esc_url( home_url( '/works/' ) ); ?>" class="btn-line">
        VIEW MORE WORKS <span class="btn-line__arrow">→</span>
      </a>
    </div>
    <div class="works__cards" data-reveal-group>
      <?php foreach ( $aishin_works as $aishin_w ) : ?>
      <a href="<?php echo esc_url( home_url( '/works/' ) ); ?>" class="works__card">
        <?php
        aishin_image_frame(
			array(
				'id'    => $aishin_w['img_id'],
				'label' => $aishin_w['img_label'],
				'ratio' => '4 / 3',
			)
		);
		?>
        <span class="works__tag"><?php echo esc_html( $aishin_w['tag'] ); ?></span>
        <h3 class="works__title"><?php echo esc_html( $aishin_w['title'] ); ?></h3>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
