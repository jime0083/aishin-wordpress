<?php
/**
 * CAREERページ（React版 Career.tsx の移植）
 * 固定ページのスラッグ 'career' に自動適用される。
 * トレース元: recruit.positive.co.jp/graduates/ の Career セクション（固有名詞はアイシン向けに置換済み）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_career_items = array(
	array(
		'num'           => '01',
		'heading_lines' => array( '多彩なキャリアを自ら描ける' ),
		'paragraphs'    => array(
			'アイシンには決まったキャリアのレールがなく、自らの意思で道を切り拓くことができます。戦略・DX・新規事業という複数領域を展開しているため職種やポジションが豊富で、チームや役割の垣根を越えて多様な経験を積めるのが魅力です。',
			'与えられた道ではなく、自ら選び、自ら創るキャリアが実現できる。挑戦する人の可能性を信じ、成長を後押しするカルチャーが根づいています。',
		),
	),
	array(
		'num'           => '02',
		'heading_lines' => array( '任せる。だから20代で活躍できる' ),
		'paragraphs'    => array(
			'メンバーを信じ、責任ある仕事を任せる文化があります。若手であっても主体的に挑戦できる環境が整っており、20代から組織の中核で活躍するメンバーが育っています。',
		),
	),
	array(
		'num'           => '03',
		'heading_lines' => array( 'ビジネスパーソンとしての成長も', 'AISHINカレッジシステム' ),
		'paragraphs'    => array(
			'役職や職種、年次に応じた研修制度に加え、AISHINビジネスカレッジや社外大学院への通学支援制度、独自のオンライン研修システムを用意。長期的な視点でメンバーのスキルアップをサポートしています。',
		),
	),
	array(
		'num'           => '04',
		'heading_lines' => array( 'スピード感ある成長を支援するMBO制度' ),
		'paragraphs'    => array(
			'目標管理には「チャレンジシート」を導入し、3ヶ月ごとの目標設定と上司からのフィードバックを実施。短いサイクルで振り返ることで、スピード感のある成長を支援します。',
		),
	),
);

$aishin_benefits = array(
	array(
		'title' => 'カフェテリアプラン制度',
		'desc'  => '自己研鑽・健康・レジャーなどから自由に選べる選択型福利厚生。',
	),
	array(
		'title' => '発明大賞',
		'desc'  => '最も優れた「ピースの発明」を年に一度全社で表彰するアワード。',
	),
	array(
		'title' => '内定者メンター制度',
		'desc'  => '内定期間中から先輩社員がメンターとして伴走し、入社への不安を解消。',
	),
	array(
		'title' => '休暇制度',
		'desc'  => 'アニバーサリー休暇・リチャージ休暇など、休む力も支える制度。',
	),
	array(
		'title' => 'ライフイベント支援',
		'desc'  => '結婚・出産のお祝い金や式参列のためのサポートを用意。',
	),
	array(
		'title' => 'サンクス手当',
		'desc'  => '仲間への感謝をポイントで贈り合えるピアボーナス制度。',
	),
	array(
		'title' => 'AISHINロングラン制度',
		'desc'  => '勤続年数に応じた表彰とリフレッシュ休暇・旅行補助。',
	),
	array(
		'title' => 'トレーナー制度',
		'desc'  => '入社後1年間、先輩コンサルタントが1on1で日々の成長に伴走。',
	),
	array(
		'title' => '働くパパママ支援制度',
		'desc'  => '時短勤務・在宅勤務・復職支援など、子育てと挑戦の両立を支援。',
	),
	array(
		'title' => '子どもの参観日休暇',
		'desc'  => '子どもの学校行事に参加するための特別休暇。',
	),
);

/**
 * キャリア項目1件を出力
 */
function aishin_career_item( $item ) {
	?>
	<div class="crr__item" data-reveal>
		<span class="crr__item-num"><?php echo esc_html( $item['num'] ); ?></span>
		<h3 class="crr__item-heading"><?php
		$line_count = count( $item['heading_lines'] );
		foreach ( $item['heading_lines'] as $j => $line ) {
			echo '<span>' . esc_html( $line );
			if ( $j < $line_count - 1 ) {
				echo '<br />';
			}
			echo '</span>';
		}
		?></h3>
		<?php foreach ( $item['paragraphs'] as $p ) : ?>
		<p class="crr__item-body"><?php echo esc_html( $p ); ?></p>
		<?php endforeach; ?>
	</div>
	<?php
}

get_header();

get_template_part( 'template-parts/page-hero', null, array( 'title' => 'CAREER' ) );
?>

<section class="section svc-intro">
  <div class="container">
    <h2 class="section__title">
      成長に、<span class="u-accent">終わりはない</span>から。
    </h2>
    <div class="svc-intro__body" data-reveal>
      <p>
        AISHINのキャリアサポート。「若手に任せる」を口だけで終わらせないために、
        キャリアの選択肢と成長の仕組みに本気で投資しています。
      </p>
    </div>
  </div>
</section>

<?php /* 01・02（画像左） */ ?>
<section class="section crr">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'CAREER', 'side' => 'right' ) ); ?>
  <div class="container">
    <div class="svc__grid">
      <div class="svc__media" data-reveal>
        <?php
        aishin_image_frame(
			array(
				'id'    => 'IMG-C01',
				'label' => 'キャリア面談・1on1の風景',
				'ratio' => '4 / 3',
			)
		);
		?>
      </div>
      <div class="svc__body">
        <?php
        aishin_career_item( $aishin_career_items[0] );
		aishin_career_item( $aishin_career_items[1] );
		?>
      </div>
    </div>
  </div>
</section>

<?php /* 03・04（画像右） */ ?>
<section class="section crr section--tinted">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'GROWTH', 'side' => 'left' ) ); ?>
  <div class="container">
    <div class="svc__grid svc__grid--flip">
      <div class="svc__media" data-reveal>
        <?php
        aishin_image_frame(
			array(
				'id'    => 'IMG-C02',
				'label' => '研修・勉強会の風景',
				'ratio' => '4 / 3',
			)
		);
		?>
      </div>
      <div class="svc__body">
        <?php
        aishin_career_item( $aishin_career_items[2] );
		aishin_career_item( $aishin_career_items[3] );
		?>
      </div>
    </div>
  </div>
</section>

<?php /* 福利厚生・支援制度リスト */ ?>
<section class="section crr-benefits">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'SUPPORT', 'side' => 'right' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      BENEFITS
    </p>
    <h2 class="section__title">
      挑戦を支える、<span class="u-accent">10の制度</span>。
    </h2>
    <div class="crr__benefits" data-reveal-group>
      <?php foreach ( $aishin_benefits as $aishin_b ) : ?>
      <div class="crr__benefit">
        <h3><?php echo esc_html( $aishin_b['title'] ); ?></h3>
        <p><?php echo esc_html( $aishin_b['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
get_template_part(
	'template-parts/marquee',
	null,
	array(
		'text'    => 'GROW BEYOND YOUR LIMIT — ',
		'reverse' => true,
		'tilt'    => true,
	)
);
get_template_part( 'template-parts/section-entry-cta' );

get_footer();
