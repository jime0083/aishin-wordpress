<?php
/**
 * 社員インタビュー初期データ投入スクリプト（WP-CLI用・冪等）
 *
 * 実行: docker compose run --rm wpcli eval-file /seed/seed-interviews.php
 * （本番Bitnami環境: wp eval-file <パス> --path=/opt/bitnami/wordpress）
 *
 * データは React版 /Users/jime0083/aishin/src/data/interviews.ts からの全文転記。
 * teaser_role は React版 InterviewTeaser.tsx の MEMBERS.role からの転記。
 * 画像はテーマ assets/images/opt/ からメディアライブラリへ登録する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	echo "WP-CLI の eval-file で実行してください\n";
	exit( 1 );
}

if ( ! function_exists( 'update_field' ) ) {
	WP_CLI::error( 'ACF が有効化されていません。先に advanced-custom-fields を有効化してください。' );
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/**
 * テーマ内画像をメディアライブラリへ登録し添付IDを返す（登録済みなら既存IDを返す）
 */
function aishin_seed_media( $img_id ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_aishin_img_id',
			'meta_value'     => $img_id,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = get_template_directory() . '/assets/images/opt/' . $img_id . '.jpg';
	if ( ! file_exists( $source ) ) {
		WP_CLI::error( "画像が見つかりません: {$source}" );
	}

	// media_handle_sideload はtmpファイルを移動するためコピーを渡す
	$tmp = wp_tempnam( $img_id . '.jpg' );
	copy( $source, $tmp );
	$file_array = array(
		'name'     => $img_id . '.jpg',
		'tmp_name' => $tmp,
	);
	$attach_id  = media_handle_sideload( $file_array, 0 );
	if ( is_wp_error( $attach_id ) ) {
		WP_CLI::error( "画像登録に失敗: {$img_id} — " . $attach_id->get_error_message() );
	}
	update_post_meta( $attach_id, '_aishin_img_id', $img_id );
	WP_CLI::log( "  画像登録: {$img_id} → 添付ID {$attach_id}" );
	return (int) $attach_id;
}

/* ------------------------------------------------------------------
   インタビューデータ（interviews.ts 全文転記）
------------------------------------------------------------------- */
$interviews = array(
	array(
		'slug'           => '01',
		'name'           => '佐藤 美咲',
		'name_en'        => 'Misaki Sato',
		'join_year'      => '2020年',
		'position'       => 'Consultant（新卒入社）',
		'teaser_role'    => 'Consultant / 2020年新卒入社',
		'quote'          => '1年目から「あなたはどう思う？」と問われ続ける環境',
		'career'         => '大学では社会学を専攻し、フィールドワークで中小企業の事業承継を研究。「現場に入り込んで課題を解く仕事がしたい」と2020年に新卒入社。1年目から製造業クライアントのDXプロジェクトに参画し、データ分析と現場ヒアリングを担当。現在は小売クライアントの戦略チームで市場分析を担う。',
		'portrait_img'   => 'IMG-09',
		'portrait_label' => '若手社員ポートレート',
		'qa'             => array(
			array(
				'heading'     => '1年目から、意見を求められる',
				'body'        => "入社して最初のプロジェクトで、いちばん驚いたのは会議の空気でした。議事録係のつもりで座っていたら、マネージャーから「佐藤さんはどう思う？」と真っ先に聞かれたんです。いちばん年次が下の私の意見も、データと理屈が通っていれば普通に採用される。「新人だから」という枠が本当にありませんでした。\n\nもちろん、意見を求められるのはプレッシャーでもあります。でも「問われ続ける」からこそ、常に自分の頭で考える癖がつきました。最初の1年で、学生時代の自分とは別人になったと思います。",
				'photo'       => 'IMG-I01',
				'photo_label' => '佐藤がオフィスで作業する様子',
			),
			array(
				'heading'     => '「わからない」を放置しない文化',
				'body'        => "アイシンには「わからないことを、わからないままにしない」文化があります。私が製造業の工程データの読み方に苦戦していたとき、先輩は答えを教えるのではなく、一緒に工場まで足を運んでくれました。現場で機械の動きを見ながら学んだことは、いまでも私の武器になっています。\n\nメンター制度で毎週30分の1on1があるのも心強いです。技術的な相談からキャリアの悩みまで、何でも話せる相手がいることが、挑戦のハードルを下げてくれます。",
				'photo'       => null,
				'photo_label' => '',
			),
			array(
				'heading'     => 'まだ見ぬピースは、自分の中にもある',
				'body'        => "「まだ見ぬピースを発明する」という会社のテーマは、クライアントに対してだけの言葉ではないと思っています。プロジェクトのたびに、自分にこんな強みがあったのかと気づかされる。それが私にとってのこの会社で働く意味です。\n\n就活中の皆さんへ。もし「早く現場で鍛えられたい」と思うなら、これ以上の環境はないと断言できます。一緒にピースを発明しましょう。",
				'photo'       => 'IMG-I02',
				'photo_label' => '佐藤がチームでディスカッションする様子',
			),
		),
	),
	array(
		'slug'           => '02',
		'name'           => '田中 蓮',
		'name_en'        => 'Ren Tanaka',
		'join_year'      => '2019年',
		'position'       => 'Senior Consultant（中途入社）',
		'teaser_role'    => 'Senior Consultant / 2019年中途入社',
		'quote'          => '前職の倍のスピードで倍の裁量。成長痛すら楽しい',
		'career'         => '大手SIerで5年間、基幹システムの開発と運用に従事。「作って終わり」ではなく事業の成果まで踏み込みたいと考え、2019年にアイシンへ中途入社。DX支援プロジェクトを中心に、データ基盤構築から業務再設計までを一気通貫で担当。現在は複数プロジェクトのリードを務める。',
		'portrait_img'   => 'IMG-10',
		'portrait_label' => '中堅社員ポートレート',
		'qa'             => array(
			array(
				'heading'     => '転職の決め手は、「任せ方」の本気度',
				'body'        => "転職活動では複数のコンサルファームを受けましたが、アイシンの面接だけ空気が違いました。「入社したら何を任せたいか」が具体的だったんです。実際、入社2週間で担当クライアントのミーティングを仕切ることになりました。放任ではなく、背中は預けるが逃げ道も用意してくれる。その塩梅が絶妙でした。\n\n前職の倍のスピードで意思決定が回り、倍の裁量がある。最初の半年は正直きつかったですが、あの「成長痛」があったから今があると思っています。",
				'photo'       => 'IMG-I03',
				'photo_label' => '田中がクライアントと打ち合わせする様子',
			),
			array(
				'heading'     => 'スピードは、信頼から生まれる',
				'body'        => "アイシンの強みは意思決定の速さですが、それは単に急いでいるからではありません。メンバー同士が互いの専門性を信頼しているから、確認や根回しに時間を使わずに済むんです。エンジニア出身の私の技術判断は尊重され、逆に戦略チームの市場の読みには私が乗る。この相互信頼がスピードの正体です。\n\nSIer時代に培った「動くものを作り切る力」は、コンサルの世界でも強力な武器になりました。手を動かせるコンサルタントは、クライアントからの信頼の質が違います。",
				'photo'       => null,
				'photo_label' => '',
			),
			array(
				'heading'     => '「成長痛」を楽しめる人と働きたい',
				'body'        => "アイシンは、快適な環境をくれる会社ではありません。少し背伸びしないと届かない仕事が、常に目の前にあります。でもその分、1年後に振り返ったときの景色が全然違う。\n\n「今の環境では成長しきれない」と感じている人、その感覚は正しいかもしれません。中途の仲間が増えるのを楽しみにしています。",
				'photo'       => 'IMG-I04',
				'photo_label' => '田中がホワイトボードの前で議論する様子',
			),
		),
	),
	array(
		'slug'           => '03',
		'name'           => '山本 夏紀',
		'name_en'        => 'Natsuki Yamamoto',
		'join_year'      => '2018年',
		'position'       => 'Manager（中途入社）',
		'teaser_role'    => 'Manager / 2018年中途入社',
		'quote'          => '29歳でマネージャーに。年齢ではなく挑戦で評価される会社',
		'career'         => '広告代理店でプランナーとして4年間勤務した後、創業期の2018年にアイシンへ中途入社。新規事業開発チームで複数の0→1プロジェクトを推進し、29歳で最年少マネージャーに昇格。現在は3つのプロジェクトを統括しながら、採用と育成にも携わる。',
		'portrait_img'   => 'IMG-11',
		'portrait_label' => 'マネージャー社員ポートレート',
		'qa'             => array(
			array(
				'heading'     => '年齢の壁が、ここにはなかった',
				'body'        => "前職では「あと5年経てば大きな仕事を任せる」と言われていました。アイシンに来て驚いたのは、評価の物差しが年齢でも社歴でもなく、「どんなピースを発明したか」だけだったこと。入社2年目に立ち上げた新規事業の検証プロセスが社内標準になり、それが評価されて29歳でマネージャーになりました。\n\n発明評価制度は、若手に有利な制度というわけではありません。誰にとってもフェアで、だからこそ緊張感がある。その健全さが好きです。",
				'photo'       => 'IMG-I05',
				'photo_label' => '山本がチームを率いて会議する様子',
			),
			array(
				'heading'     => 'マネージャーの仕事は、ピースをはめること',
				'body'        => "マネージャーになって気づいたのは、この仕事はメンバー一人ひとりの「まだ見ぬピース」を見つけて、最高の場所にはめることだということ。数字の管理よりも、人の強みの発見に時間を使っています。\n\nメンバーが自分でも気づいていなかった強みで成果を出した瞬間が、いちばん嬉しい。私自身がそうやって見出してもらった恩を、次の世代に返しているつもりです。",
				'photo'       => null,
				'photo_label' => '',
			),
			array(
				'heading'     => '次の挑戦者へ',
				'body'        => "アイシンは完成された会社ではありません。制度も文化も、まだ発明の途中です。だからこそ、会社そのものを一緒につくる面白さがあります。\n\n「与えられたキャリア」に物足りなさを感じているなら、ぜひ一度話しましょう。あなたというピースが、この会社のどこにはまるのか。一緒に探すところから始めたいです。",
				'photo'       => 'IMG-I06',
				'photo_label' => '山本のポートレート（働く環境と共に）',
			),
		),
	),
);

/* ------------------------------------------------------------------
   投入処理（冪等: スラッグ既存なら更新）
------------------------------------------------------------------- */
foreach ( $interviews as $data ) {
	$existing = get_page_by_path( $data['slug'], OBJECT, 'interview' );

	$postarr = array(
		'post_type'   => 'interview',
		'post_name'   => $data['slug'],
		'post_title'  => $data['name'],
		'post_status' => 'publish',
	);
	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		$post_id       = wp_update_post( $postarr );
		WP_CLI::log( "更新: interview/{$data['slug']} （{$data['name']}）" );
	} else {
		$post_id = wp_insert_post( $postarr );
		WP_CLI::log( "作成: interview/{$data['slug']} （{$data['name']}）" );
	}
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		WP_CLI::error( "投稿の作成に失敗: {$data['slug']}" );
	}

	update_field( 'name_en', $data['name_en'], $post_id );
	update_field( 'join_year', $data['join_year'], $post_id );
	update_field( 'position', $data['position'], $post_id );
	update_field( 'teaser_role', $data['teaser_role'], $post_id );
	update_field( 'quote', $data['quote'], $post_id );
	update_field( 'career', $data['career'], $post_id );
	update_field( 'portrait_image', aishin_seed_media( $data['portrait_img'] ), $post_id );
	update_field( 'portrait_label', $data['portrait_label'], $post_id );

	foreach ( $data['qa'] as $i => $qa ) {
		$n = $i + 1;
		update_field( "qa{$n}_heading", $qa['heading'], $post_id );
		update_field( "qa{$n}_body", $qa['body'], $post_id );
		update_field( "qa{$n}_photo", $qa['photo'] ? aishin_seed_media( $qa['photo'] ) : null, $post_id );
		update_field( "qa{$n}_photo_label", $qa['photo_label'], $post_id );
	}
}

flush_rewrite_rules();
WP_CLI::success( 'インタビュー3名分の投入が完了しました。' );
