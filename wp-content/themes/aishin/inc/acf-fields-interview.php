<?php
/**
 * ACFフィールド定義（社員インタビュー）— PHPコードで登録しGit管理する
 *
 * React版 src/data/interviews.ts の Interview 型と1:1対応:
 *   name(=投稿タイトル) / nameEn / joinYear / position / quote / career /
 *   portraitImgId+Label / qa[0..2]{heading, paragraphs, photo?}
 * Q&AはReact版のデータ構造どおり3問固定（ACF無料版のためリピーター不使用）。
 * teaser_role はトップページのカード用肩書（React版 InterviewTeaser の MEMBERS.role）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$fields = array(
			array(
				'key'          => 'field_aishin_name_en',
				'name'         => 'name_en',
				'label'        => '氏名（英語表記）',
				'type'         => 'text',
				'required'     => 1,
				'instructions' => '例: Misaki Sato',
			),
			array(
				'key'          => 'field_aishin_join_year',
				'name'         => 'join_year',
				'label'        => '入社年',
				'type'         => 'text',
				'required'     => 1,
				'instructions' => '例: 2020年（「入社」は付けない。表示側で「◯◯年入社」と組み立てます）',
			),
			array(
				'key'          => 'field_aishin_position',
				'name'         => 'position',
				'label'        => '役職',
				'type'         => 'text',
				'required'     => 1,
				'instructions' => '例: Consultant（新卒入社）',
			),
			array(
				'key'          => 'field_aishin_teaser_role',
				'name'         => 'teaser_role',
				'label'        => 'トップページ用肩書',
				'type'         => 'text',
				'required'     => 1,
				'instructions' => 'トップページのカードに表示する肩書。例: Consultant / 2020年新卒入社',
			),
			array(
				'key'          => 'field_aishin_quote',
				'name'         => 'quote',
				'label'        => '一言（クオート）',
				'type'         => 'text',
				'required'     => 1,
				'instructions' => 'カード・詳細ページの大見出しに使う一言',
			),
			array(
				'key'          => 'field_aishin_career',
				'name'         => 'career',
				'label'        => '経歴概要',
				'type'         => 'textarea',
				'rows'         => 4,
				'new_lines'    => '',
				'required'     => 1,
			),
			array(
				'key'           => 'field_aishin_portrait_image',
				'name'          => 'portrait_image',
				'label'         => 'ポートレート写真',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'required'      => 1,
				'instructions'  => '縦3:4推奨。トップページのカードと詳細ページFVで共通使用',
			),
			array(
				'key'   => 'field_aishin_portrait_label',
				'name'  => 'portrait_label',
				'label' => 'ポートレートの説明（alt）',
				'type'  => 'text',
			),
		);

		// Q&A 3問（React版のデータ構造どおり固定）
		for ( $i = 1; $i <= 3; $i++ ) {
			$fields[] = array(
				'key'      => "field_aishin_qa{$i}_heading",
				'name'     => "qa{$i}_heading",
				'label'    => "Q{$i} 見出し",
				'type'     => 'text',
				'required' => ( 1 === $i ) ? 1 : 0,
			);
			$fields[] = array(
				'key'          => "field_aishin_qa{$i}_body",
				'name'         => "qa{$i}_body",
				'label'        => "Q{$i} 本文",
				'type'         => 'textarea',
				'rows'         => 8,
				'new_lines'    => '',
				'required'     => ( 1 === $i ) ? 1 : 0,
				'instructions' => '段落の区切りは空行（改行2つ）で入力してください',
			);
			$fields[] = array(
				'key'           => "field_aishin_qa{$i}_photo",
				'name'          => "qa{$i}_photo",
				'label'         => "Q{$i} 写真（任意）",
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'instructions'  => '横4:3推奨。未設定の場合は本文のみの1カラム表示になります',
			);
			$fields[] = array(
				'key'   => "field_aishin_qa{$i}_photo_label",
				'name'  => "qa{$i}_photo_label",
				'label' => "Q{$i} 写真の説明（alt）",
				'type'  => 'text',
			);
		}

		acf_add_local_field_group(
			array(
				'key'      => 'group_aishin_interview',
				'title'    => 'インタビュー内容',
				'fields'   => $fields,
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'interview',
						),
					),
				),
				'position' => 'normal',
				'style'    => 'default',
			)
		);
	}
);
