<?php
/**
 * トップページ CAREER ティーザー（React版 CareerTeaser.tsx の移植）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_supports = array(
	array(
		'title' => 'メンター制度',
		'desc'  => '新卒・中途を問わず、入社後1年間は先輩コンサルタントが1on1で伴走します。',
	),
	array(
		'title' => 'アイシン・アカデミー',
		'desc'  => 'ロジカルシンキングからデータ分析まで、月20時間以上の社内研修プログラム。',
	),
	array(
		'title' => '発明評価制度',
		'desc'  => '年次や年齢ではなく「どんなピースを発明したか」で評価・昇格が決まります。',
	),
);
?>
<section class="career section" id="career">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'GROWTH', 'side' => 'left' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num">05</span> CAREER SUPPORT
    </p>
    <div class="career__grid">
      <div class="career__text" data-reveal>
        <h2 class="section__title">
          成長を、<span class="u-accent">仕組み</span>にする。
        </h2>
        <p class="career__lead">
          「若手に任せる」を口だけで終わらせないために、
          アイシンは成長を支える制度に本気で投資しています。
        </p>
        <ul class="career__list" data-reveal-group>
          <?php foreach ( $aishin_supports as $aishin_s ) : ?>
          <li class="career__item">
            <h3><?php echo esc_html( $aishin_s['title'] ); ?></h3>
            <p><?php echo esc_html( $aishin_s['desc'] ); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="<?php echo esc_url( home_url( '/career/' ) ); ?>" class="btn-line">
          VIEW CAREER SUPPORT <span class="btn-line__arrow">→</span>
        </a>
      </div>
      <div class="career__image" data-reveal>
        <?php
        aishin_image_frame(
			array(
				'id'    => 'IMG-12',
				'label' => '研修・1on1の風景',
				'ratio' => '4 / 5',
			)
		);
		?>
      </div>
    </div>
  </div>
</section>
