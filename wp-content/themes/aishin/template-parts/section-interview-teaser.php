<?php
/**
 * トップページ INTERVIEW ティーザー（React版 InterviewTeaser.tsx の移植）
 * カードはカスタム投稿タイプ interview から取得する（スラッグ 01→03 順）。
 * 一覧ページは持たず、各社員の詳細ページへ直接遷移する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aishin_members = aishin_get_interviews();
?>
<section class="interview section section--tinted" id="interview">
  <?php get_template_part( 'template-parts/giant-word', null, array( 'text' => 'PEOPLE', 'side' => 'right' ) ); ?>
  <div class="container">
    <p class="section__eyebrow" data-reveal>
      <span class="section__eyebrow-num">04</span> INTERVIEW
    </p>
    <div class="section__head" data-reveal>
      <h2 class="section__title">
        ピースを発明する<span class="u-accent">人たち</span>。
      </h2>
    </div>
    <div class="interview__cards" data-reveal-group>
      <?php foreach ( $aishin_members as $aishin_m ) : ?>
      <a href="<?php echo esc_url( get_permalink( $aishin_m ) ); ?>" class="interview__card">
        <?php aishin_interview_portrait( $aishin_m->ID, '3 / 4' ); ?>
        <p class="interview__quote">“<?php echo esc_html( aishin_field( 'quote', $aishin_m->ID ) ); ?>”</p>
        <p class="interview__name"><?php echo esc_html( get_the_title( $aishin_m ) ); ?></p>
        <p class="interview__role"><?php echo esc_html( aishin_field( 'teaser_role', $aishin_m->ID ) ); ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
