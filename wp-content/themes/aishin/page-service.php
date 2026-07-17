<?php
/**
 * SERVICEページ（React版 Service.tsx の移植）
 * 固定ページのスラッグ 'service' に自動適用される。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_services = array(
	array(
		'num'        => '01',
		'title'      => 'Strategy Consulting',
		'jp'         => '戦略コンサルティング',
		'giant'      => 'STRATEGY',
		'lead'       => '経営課題を構造化し、絵に描いた餅で終わらせない「実行できる戦略」を描きます。資料を納品して終わりではなく、現場に入り込み、成果が出るまで伴走するのがアイシンの流儀です。',
		'challenges' => array(
			'売上や利益の伸び悩みの原因が特定できていない',
			'中期経営計画が現場の行動につながっていない',
			'新しい打ち手を検討するリソースやノウハウが社内にない',
		),
		'offers'     => array(
			array(
				'title' => '経営課題の構造化・診断',
				'desc'  => 'データ分析と現場ヒアリングで課題の全体像を可視化し、取り組むべき順番を明確にします。',
			),
			array(
				'title' => '成長戦略・事業戦略の策定',
				'desc'  => '市場・競合・自社の分析に基づき、実行可能な戦略オプションを設計します。',
			),
			array(
				'title' => '実行支援・PMO',
				'desc'  => '戦略を現場のアクションに落とし込み、KPI管理と改善サイクルの定着まで支援します。',
			),
		),
		'steps'      => array( '現状分析・課題の構造化', '戦略オプションの設計', '実行計画への落とし込み', '伴走・定着支援' ),
		'img_id'     => 'IMG-S01',
		'img_label'  => '戦略ディスカッションの風景',
	),
	array(
		'num'        => '02',
		'title'      => 'DX Acceleration',
		'jp'         => 'DX支援',
		'giant'      => 'DX',
		'lead'       => 'ツールを導入して終わり、にはしません。データとテクノロジーで業務と事業そのものを再発明し、変革が現場の文化になるまで伴走します。',
		'challenges' => array(
			'紙とExcel中心の業務から抜け出せない',
			'データが部署ごとに散在し、経営判断に活かせていない',
			'システムを導入したものの現場に定着しなかった',
		),
		'offers'     => array(
			array(
				'title' => 'DX戦略・ロードマップ策定',
				'desc'  => '業務とデータの棚卸しから、投資対効果の高い変革の道筋を設計します。',
			),
			array(
				'title' => 'データ基盤・ダッシュボード構築',
				'desc'  => '散在するデータを統合し、意思決定に使える形で見える化します。',
			),
			array(
				'title' => '業務プロセス再設計・定着化',
				'desc'  => 'ツール任せにせず業務フローから再設計し、現場への定着と内製化まで支援します。',
			),
		),
		'steps'      => array( '業務・データの棚卸し', 'DXロードマップ策定', 'ツール選定・導入・開発', '現場定着・内製化支援' ),
		'img_id'     => 'IMG-S02',
		'img_label'  => 'データダッシュボードを囲むチーム',
	),
	array(
		'num'        => '03',
		'title'      => 'New Business Design',
		'jp'         => '新規事業開発',
		'giant'      => 'CREATE',
		'lead'       => '0→1の事業づくりをクライアントと共創します。アイデアの種を仮説検証で磨き上げ、「まだ見ぬピース」を勝てる事業に育て上げます。',
		'challenges' => array(
			'新規事業のアイデアが社内からなかなか出てこない',
			'アイデアはあるが検証の進め方がわからない',
			'既存事業の枠を越えた成長の柱をつくりたい',
		),
		'offers'     => array(
			array(
				'title' => '事業機会の探索・アイデア創出',
				'desc'  => '市場の兆しと自社の強みを掛け合わせるワークショップで、事業仮説を生み出します。',
			),
			array(
				'title' => 'MVP設計・仮説検証の伴走',
				'desc'  => '小さく速く検証を回し、撤退・継続の判断基準まで含めて伴走します。',
			),
			array(
				'title' => 'グロース戦略・事業計画策定',
				'desc'  => '検証を通過した事業の成長戦略と、投資判断に耐える事業計画を策定します。',
			),
		),
		'steps'      => array( '機会探索・アイデア創出', '仮説検証・MVP開発', '事業化・ローンチ', 'グロース支援' ),
		'img_id'     => 'IMG-S03',
		'img_label'  => '付箋を使ったワークショップ風景',
	),
);

get_header();

/* 日本語サブ・導入文はユーザー指示（P-012）により表示しない */
get_template_part( 'template-parts/page-hero', null, array( 'title' => 'SERVICE' ) );
?>

<section class="section svc-intro">
  <div class="container">
    <h2 class="section__title">
      発明は、<span class="u-accent">3つの現場</span>で起こる。
    </h2>
    <div class="svc-intro__body" data-reveal>
      <p>
        アイシンのコンサルティングは、レポートの納品では終わりません。
        戦略・DX・新規事業という3つの現場で、クライアントのチームの一員として手を動かし、
        成果というピースがはまる瞬間まで伴走します。
      </p>
    </div>
  </div>
</section>

<?php foreach ( $aishin_services as $aishin_i => $aishin_s ) : $aishin_flip = ( 1 === $aishin_i % 2 ); ?>
<section class="section svc <?php echo $aishin_flip ? 'section--tinted' : ''; ?>" id="service-<?php echo esc_attr( $aishin_s['num'] ); ?>">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => $aishin_s['giant'], 'side' => $aishin_flip ? 'left' : 'right' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num"><?php echo esc_html( $aishin_s['num'] ); ?></span> <?php echo esc_html( strtoupper( $aishin_s['title'] ) ); ?>
    </p>
    <div class="svc__grid <?php echo $aishin_flip ? 'svc__grid--flip' : ''; ?>">
      <div class="svc__media" data-reveal>
        <span class="svc__num" aria-hidden="true"><?php echo esc_html( $aishin_s['num'] ); ?></span>
        <?php
        aishin_image_frame(
			array(
				'id'    => $aishin_s['img_id'],
				'label' => $aishin_s['img_label'],
				'ratio' => '4 / 3',
			)
		);
		?>
      </div>
      <div class="svc__body">
        <h2 class="section__title"><?php echo esc_html( $aishin_s['title'] ); ?></h2>
        <p class="svc__jp"><?php echo esc_html( $aishin_s['jp'] ); ?></p>
        <p class="svc__lead" data-reveal><?php echo esc_html( $aishin_s['lead'] ); ?></p>
        <h3 class="svc__label" data-reveal>こんな課題に</h3>
        <ul class="svc__challenges" data-reveal>
          <?php foreach ( $aishin_s['challenges'] as $aishin_c ) : ?>
          <li><?php echo esc_html( $aishin_c ); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="svc__points" data-reveal-group>
      <?php foreach ( $aishin_s['offers'] as $aishin_j => $aishin_o ) : ?>
      <div class="svc__point">
        <span class="svc__point-num"><?php echo esc_html( $aishin_s['num'] . '-' . ( $aishin_j + 1 ) ); ?></span>
        <h3><?php echo esc_html( $aishin_o['title'] ); ?></h3>
        <p><?php echo esc_html( $aishin_o['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div data-reveal>
      <h3 class="svc__label">進め方</h3>
      <ol class="svc__steps">
        <?php foreach ( $aishin_s['steps'] as $aishin_j => $aishin_step ) : ?>
        <li class="svc__step">
          <span class="svc__step-num">STEP <?php echo esc_html( $aishin_j + 1 ); ?></span>
          <span class="svc__step-title"><?php echo esc_html( $aishin_step ); ?></span>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>
<?php endforeach; ?>

<?php
get_template_part(
	'template-parts/marquee',
	null,
	array(
		'text'    => "LET'S INVENT THE MISSING PIECE — ",
		'reverse' => true,
		'tilt'    => true,
	)
);
get_template_part( 'template-parts/section-entry-cta' );

get_footer();
