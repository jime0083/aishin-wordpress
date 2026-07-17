<?php
/**
 * 社員インタビュー詳細ページ（React版 InterviewDetail.tsx の移植）
 * 大型ビジュアル → プロフィール・経歴 → Q&A3本＋写真 → 他社員リンク → ENTRY CTA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$aishin_id       = get_the_ID();
	$aishin_slug     = get_post_field( 'post_name', $aishin_id );
	$aishin_name     = get_the_title();
	$aishin_name_en  = (string) aishin_field( 'name_en', $aishin_id );
	$aishin_join     = (string) aishin_field( 'join_year', $aishin_id );
	$aishin_position = (string) aishin_field( 'position', $aishin_id );
	$aishin_quote    = (string) aishin_field( 'quote', $aishin_id );
	$aishin_career   = (string) aishin_field( 'career', $aishin_id );
	$aishin_others   = aishin_get_interviews( $aishin_id );
	?>

<?php /* 大型ビジュアル（ポートレート＋一言のキネティックタイポ） */ ?>
<section class="itv-hero" aria-label="<?php echo esc_attr( $aishin_name . ' インタビュー' ); ?>">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'INTERVIEW', 'side' => 'right' ) ); ?>
  <?php aishin_pieces_stage( 'page-hero__stage' ); ?>
  <div class="container itv-hero__grid">
    <div class="itv-hero__media">
      <?php /* FVのポートレートは縦長の楕円で切り抜く（P-021） */ ?>
      <?php aishin_interview_portrait( $aishin_id, '3 / 4', 'ellipse' ); ?>
    </div>
    <div class="itv-hero__body">
      <p class="itv-hero__eyebrow">INTERVIEW <?php echo esc_html( $aishin_slug ); ?></p>
      <h1 class="itv-hero__quote"><?php
      foreach ( preg_split( '//u', $aishin_quote, -1, PREG_SPLIT_NO_EMPTY ) as $aishin_ch ) {
			echo '<span class="itv-hero__char">' . esc_html( $aishin_ch ) . '</span>';
		}
		?></h1>
      <p class="itv-hero__name">
        <?php echo esc_html( $aishin_name ); ?> <span class="itv-hero__name-en"><?php echo esc_html( $aishin_name_en ); ?></span>
      </p>
      <p class="itv-hero__role">
        <?php echo esc_html( $aishin_position . ' / ' . $aishin_join . '入社' ); ?>
      </p>
    </div>
  </div>
</section>

<?php /* プロフィール・経歴 */ ?>
<section class="section itv-profile section--tinted">
  <div class="container">
    <div class="itv-profile__card" data-reveal>
      <p class="about__profile-title">PROFILE</p>
      <dl>
        <div class="about__profile-row">
          <dt>氏名</dt>
          <dd><?php echo esc_html( $aishin_name . '（' . $aishin_name_en . '）' ); ?></dd>
        </div>
        <div class="about__profile-row">
          <dt>入社</dt>
          <dd><?php echo esc_html( $aishin_join ); ?></dd>
        </div>
        <div class="about__profile-row">
          <dt>役職</dt>
          <dd><?php echo esc_html( $aishin_position ); ?></dd>
        </div>
        <div class="about__profile-row">
          <dt>経歴</dt>
          <dd><?php echo esc_html( $aishin_career ); ?></dd>
        </div>
      </dl>
    </div>
  </div>
</section>

<?php
/* Q&A 3本（見出し＋本文＋写真）。flip = 奇数番目（i%2===1）で写真を左配置 */
for ( $aishin_i = 0; $aishin_i < 3; $aishin_i++ ) :
	$aishin_n       = $aishin_i + 1;
	$aishin_heading = (string) aishin_field( "qa{$aishin_n}_heading", $aishin_id );
	$aishin_body    = (string) aishin_field( "qa{$aishin_n}_body", $aishin_id );
	if ( '' === trim( $aishin_heading ) && '' === trim( $aishin_body ) ) {
		continue;
	}
	$aishin_photo_id    = aishin_field( "qa{$aishin_n}_photo", $aishin_id );
	$aishin_photo_label = (string) aishin_field( "qa{$aishin_n}_photo_label", $aishin_id );
	$aishin_photo_src   = $aishin_photo_id ? wp_get_attachment_image_url( (int) $aishin_photo_id, 'full' ) : null;
	$aishin_flip        = ( 1 === $aishin_i % 2 );
	// 段落は空行区切り
	$aishin_paragraphs = preg_split( '/\r?\n\s*\r?\n/u', trim( $aishin_body ) );
	?>
<section class="section itv-qa">
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num"><?php echo esc_html( '0' . $aishin_n ); ?></span> QUESTION
    </p>
    <h2 class="section__title itv-qa__heading"><?php echo esc_html( $aishin_heading ); ?></h2>
    <div class="itv-qa__grid <?php echo $aishin_photo_src ? '' : 'itv-qa__grid--single'; ?>">
      <div class="itv-qa__body" data-reveal>
        <?php foreach ( $aishin_paragraphs as $aishin_p ) : ?>
        <p><?php echo esc_html( $aishin_p ); ?></p>
        <?php endforeach; ?>
      </div>
      <?php if ( $aishin_photo_src ) : ?>
      <div class="itv-qa__media <?php echo $aishin_flip ? 'itv-qa__media--first' : ''; ?>" data-reveal>
        <?php
        aishin_image_frame(
			array(
				'id'    => pathinfo( wp_basename( $aishin_photo_src ), PATHINFO_FILENAME ),
				'label' => $aishin_photo_label,
				'ratio' => '4 / 3',
				'src'   => $aishin_photo_src,
			)
		);
		?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endfor; ?>

<?php /* 他社員へのリンク */ ?>
<section class="section itv-others section--tinted">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'PEOPLE', 'side' => 'left' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      OTHER MEMBERS
    </p>
    <h2 class="section__title">ほかの社員も知る。</h2>
    <div class="interview__cards itv-others__cards" data-reveal-group>
      <?php foreach ( $aishin_others as $aishin_m ) : ?>
      <a href="<?php echo esc_url( get_permalink( $aishin_m ) ); ?>" class="interview__card">
        <?php aishin_interview_portrait( $aishin_m->ID, '3 / 4' ); ?>
        <p class="interview__quote">“<?php echo esc_html( aishin_field( 'quote', $aishin_m->ID ) ); ?>”</p>
        <p class="interview__name"><?php echo esc_html( get_the_title( $aishin_m ) ); ?></p>
        <p class="interview__role">
          <?php echo esc_html( aishin_field( 'position', $aishin_m->ID ) . ' / ' . aishin_field( 'join_year', $aishin_m->ID ) . '入社' ); ?>
        </p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
get_template_part(
	'template-parts/marquee',
	null,
	array(
		'text'    => 'MEET YOUR FUTURE TEAM — ',
		'reverse' => true,
		'tilt'    => true,
	)
);
get_template_part( 'template-parts/section-entry-cta' );

endwhile;

get_footer();
