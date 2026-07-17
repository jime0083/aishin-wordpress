<?php
/**
 * トップページ ABOUT セクション（React版 About.tsx の移植）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_stats = array(
	array( 2018, '設立', '', 0 ),
	array( 48, 'メンバー数', '名', 0 ),
	array( 28.4, '平均年齢', '歳', 1 ),
	array( 120, '支援プロジェクト', '+', 0 ),
);

$aishin_profile = array(
	array( '社名', '株式会社アイシン（Aishin Inc.）' ),
	array( '設立', '2018年4月' ),
	array( '代表取締役', '相心 卓夢' ),
	array( '所在地', '〒150-0041 東京都渋谷区神南1-2-3 アイシンビル5F' ),
	array( '電話番号', '03-1234-5678' ),
	array( '事業内容', '戦略コンサルティング／DX支援／新規事業開発支援' ),
	array( 'メンバー数', '48名（平均年齢28.4歳）' ),
);
?>
<section class="about section" id="about">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'ABOUT', 'side' => 'left' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num">02</span> ABOUT US
    </p>
    <div class="about__grid">
      <div class="about__text" data-reveal>
        <h2 class="section__title">
          若さは、<span class="u-accent">武器</span>だ。
        </h2>
        <p>
          アイシンのメンバーの平均年齢は28.4歳。新卒1年目からクライアントの経営課題に向き合い、
          裁量と責任を持ってプロジェクトを推進します。
          「若いから任せられない」ではなく「若いからこそ発明できる」。
          それが私たちの組織のつくり方です。
        </p>
        <div class="about__stats" data-reveal-group>
          <?php foreach ( $aishin_stats as $aishin_s ) : list( $aishin_value, $aishin_label, $aishin_suffix, $aishin_decimals ) = $aishin_s; ?>
          <div class="about__stat">
            <span class="about__stat-value" data-count="<?php echo esc_attr( $aishin_value ); ?>" data-decimals="<?php echo esc_attr( $aishin_decimals ); ?>">0</span>
            <span class="about__stat-suffix"><?php echo esc_html( $aishin_suffix ); ?></span>
            <span class="about__stat-label"><?php echo esc_html( $aishin_label ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="about__images" data-reveal>
        <?php
        aishin_image_frame(
			array(
				'id'    => 'IMG-01',
				'label' => '若手社員のディスカッション風景',
				'ratio' => '4 / 3',
				'class' => 'about__img-main',
			)
		);
		aishin_image_frame(
			array(
				'id'    => 'IMG-02',
				'label' => 'ホワイトボードで戦略を描く場面',
				'ratio' => '4 / 5',
				'class' => 'about__img-sub',
			)
		);
		?>
      </div>
    </div>

    <div class="about__profile" data-reveal>
      <h3 class="about__profile-title">COMPANY PROFILE</h3>
      <dl class="about__profile-table">
        <?php foreach ( $aishin_profile as $aishin_row ) : ?>
        <div class="about__profile-row">
          <dt><?php echo esc_html( $aishin_row[0] ); ?></dt>
          <dd><?php echo esc_html( $aishin_row[1] ); ?></dd>
        </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </div>
</section>
