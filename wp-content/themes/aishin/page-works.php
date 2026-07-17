<?php
/**
 * WORKSページ（React版 Works.tsx の移植）
 * 固定ページのスラッグ 'works' に自動適用される。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_works = array(
	array(
		'num'         => '01',
		'tag'         => 'MANUFACTURING',
		'title_lines' => array( '老舗製造業のDXで', '生産性132%を実現' ),
		'challenge'   => '紙の生産日報と熟練者の勘に頼った工程管理で、ボトルネックの特定に数週間かかっていた。',
		'support'     => '現場に常駐して工程データをデジタル化。業務フローを再設計し、生産ダッシュボードの導入から定着までを伴走。',
		'result'      => '生産性が前年比132%に向上。月間の残業時間も約2割削減された。',
		'stat'        => array(
			'prefix'   => '',
			'value'    => '132',
			'decimals' => 0,
			'suffix'   => '%',
			'label'    => '生産性（前年比）',
		),
		'img_id'      => 'IMG-W01',
		'img_label'   => '製造業の工場・生産ラインの風景',
	),
	array(
		'num'         => '02',
		'tag'         => 'RETAIL',
		'title_lines' => array( '全国120店舗の', '小売チェーンの', '購買体験を再設計' ),
		'challenge'   => '店舗ごとに接客品質がばらつき、ECと店舗が分断されて機会損失が発生していた。',
		'support'     => '顧客動線と購買データを分析し、店舗オペレーションを標準化。アプリと店舗をつなぐOMO体験を設計。',
		'result'      => 'EC経由売上が2.6倍に。アプリ会員は1年で40万人増加した。',
		'stat'        => array(
			'prefix'   => '',
			'value'    => '2.6',
			'decimals' => 1,
			'suffix'   => '倍',
			'label'    => 'EC経由売上（施策後1年）',
		),
		'img_id'      => 'IMG-W02',
		'img_label'   => '小売店舗・売場の風景',
	),
	array(
		'num'         => '03',
		'tag'         => 'STARTUP',
		'title_lines' => array( 'SAスタートアップ', '新規事業を', '0→1で共創' ),
		'challenge'   => '主力事業の成長が鈍化し、第二の柱となる新規事業の種がなかった。',
		'support'     => '機会探索ワークショップで事業仮説を創出。MVPを設計し、高速の仮説検証サイクルを共に回した。',
		'result'      => '検証開始から90日でローンチ。初年度で有料顧客100社を獲得した。',
		'stat'        => array(
			'prefix'   => '',
			'value'    => '90',
			'decimals' => 0,
			'suffix'   => '日',
			'label'    => '構想からローンチまで',
		),
		'img_id'      => 'IMG-W03',
		'img_label'   => 'スタートアップのオフィス・開発風景',
	),
	array(
		'num'         => '04',
		'tag'         => 'HEALTHCARE',
		'title_lines' => array( '地域医療グループの', '経営改革で増収+24%' ),
		'challenge'   => '診療科ごとの採算が不透明で、慢性的な人材不足が経営を圧迫していた。',
		'support'     => '部門別採算を可視化し、患者体験を再設計。採用ブランディングと定着施策までを一体で支援。',
		'result'      => '増収+24%を達成。看護師の離職率も9ポイント改善した。',
		'stat'        => array(
			'prefix'   => '+',
			'value'    => '24',
			'decimals' => 0,
			'suffix'   => '%',
			'label'    => '増収率（改革後2年）',
		),
		'img_id'      => 'IMG-W04',
		'img_label'   => '医療施設・スタッフの風景',
	),
	array(
		'num'         => '05',
		'tag'         => 'LOGISTICS',
		'title_lines' => array( '物流企業配送網最適化', 'コスト23%削減' ),
		'challenge'   => '燃料費の高騰とドライバー不足で、利益率が年々低下していた。',
		'support'     => '配送データを分析して拠点とルートを再設計。需要予測モデルを導入し、積載率を最大化。',
		'result'      => '配送コストを23%削減しながら、納期遵守率99%を維持した。',
		'stat'        => array(
			'prefix'   => '',
			'value'    => '23',
			'decimals' => 0,
			'suffix'   => '%',
			'label'    => '配送コスト削減率',
		),
		'img_id'      => 'IMG-W05',
		'img_label'   => '物流倉庫・配送トラックの風景',
	),
);

get_header();

get_template_part( 'template-parts/page-hero', null, array( 'title' => 'WORKS' ) );
?>

<section class="section svc-intro">
  <div class="container">
    <h2 class="section__title">
      成果で語る、<span class="u-accent">挑戦の記録</span>。
    </h2>
    <div class="svc-intro__body" data-reveal>
      <p>
        業界も規模も異なるクライアントとの、代表的なプロジェクトをご紹介します。
        どの現場にも「まだ見ぬピース」があり、私たちはそれを一緒に発明してきました。
      </p>
    </div>
  </div>
</section>

<?php foreach ( $aishin_works as $aishin_i => $aishin_w ) : $aishin_flip = ( 1 === $aishin_i % 2 ); ?>
<section class="section wrk <?php echo $aishin_flip ? 'section--tinted' : ''; ?>">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => $aishin_w['tag'], 'side' => $aishin_flip ? 'left' : 'right' ) ); ?>
  <div class="container">
    <?php /* svc__grid（左右交互のメディア＋テキストグリッド）を共通レイアウトとして流用 */ ?>
    <div class="svc__grid <?php echo $aishin_flip ? 'svc__grid--flip' : ''; ?>">
      <div class="svc__media" data-reveal>
        <span class="svc__num" aria-hidden="true"><?php echo esc_html( $aishin_w['num'] ); ?></span>
        <?php
        aishin_image_frame(
			array(
				'id'    => $aishin_w['img_id'],
				'label' => $aishin_w['img_label'],
				'ratio' => '4 / 3',
			)
		);
		?>
      </div>
      <div class="svc__body">
        <span class="works__tag"><?php echo esc_html( $aishin_w['tag'] ); ?></span>
        <h2 class="section__title wrk__title"><?php
        $aishin_line_count = count( $aishin_w['title_lines'] );
		foreach ( $aishin_w['title_lines'] as $aishin_j => $aishin_line ) {
			echo '<span class="wrk__title-line">' . esc_html( $aishin_line );
			if ( $aishin_j < $aishin_line_count - 1 ) {
				echo '<br />';
			}
			echo '</span>';
		}
		?></h2>
        <dl class="wrk__rows">
          <div class="wrk__row">
            <dt>課題</dt>
            <dd><?php echo esc_html( $aishin_w['challenge'] ); ?></dd>
          </div>
          <div class="wrk__row">
            <dt>支援内容</dt>
            <dd><?php echo esc_html( $aishin_w['support'] ); ?></dd>
          </div>
          <div class="wrk__row">
            <dt>成果</dt>
            <dd><?php echo esc_html( $aishin_w['result'] ); ?></dd>
          </div>
        </dl>
        <p class="wrk__stat">
          <span class="wrk__stat-value"><?php echo esc_html( $aishin_w['stat']['prefix'] ); ?><span data-count="<?php echo esc_attr( $aishin_w['stat']['value'] ); ?>" data-decimals="<?php echo esc_attr( $aishin_w['stat']['decimals'] ); ?>">0</span><span class="wrk__stat-suffix"><?php echo esc_html( $aishin_w['stat']['suffix'] ); ?></span></span>
          <span class="wrk__stat-label"><?php echo esc_html( $aishin_w['stat']['label'] ); ?></span>
        </p>
      </div>
    </div>
  </div>
</section>
<?php endforeach; ?>

<?php
get_template_part(
	'template-parts/marquee',
	null,
	array(
		'text'    => 'YOUR PIECE COMES NEXT — ',
		'reverse' => true,
		'tilt'    => true,
	)
);
get_template_part( 'template-parts/section-entry-cta' );

get_footer();
