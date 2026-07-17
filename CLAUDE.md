# 株式会社アイシン コーポレートサイト WordPress版

React SPA版（`/Users/jime0083/aishin`）と**完全に同一のデザイン**を持つWordPressサイトを構築するプロジェクト。

## ⚠️ 最重要ルール（厳守）

### 1. 不明点ゼロで作業する（このプロジェクト全体で徹底）
**作業を進める上で不明点がある場合は必ずユーザーに質問し、不明点が一切ない状態で各作業を進めること。**

- 仕様・デザイン・文言・構成・インフラ設定などに少しでも曖昧な点があれば、推測で進めずに必ず質問する
- 質問への回答を得て、不明点がゼロになってから実装に着手する
- このルールはこのプロジェクトの**全ての作業**（実装・検証・デプロイ・ドキュメント作成）に適用される

### 2. デザイン完全一致（一切のデザイン違いを許さない）
**React版（`/Users/jime0083/aishin`）と全く同じデザインを再現すること。レスポンシブ表示まで含めて一切の違いを許さない。**

- CSS 3ファイル（`global.css` / `home.css` / `subpage.css`）は**1文字も変えずに**そのまま移植する（読み込み順も main.tsx と同じ global → home → subpage）
- HTMLのクラス名・DOM構造・属性（`data-reveal` / `data-cursor-label` / `aria-*` 等）はReact版のJSX出力と完全に一致させる
- アニメーションのパラメータ（duration / ease / stagger / 物理演算の定数等）はReact版のコードから**数値をそのまま転記**する。目分量での再現は禁止
- ブレークポイントは900px（全CSS共通）。900px以下のモバイル表示も必ず照合する
- `prefers-reduced-motion: reduce` / `(pointer: fine)` の分岐挙動もReact版と同一にする
- 迷ったら必ずReact版のソースコードを開いて確認する。記憶で書かない

### 3. 検証フロー（1ページ完成ごと）
```
1ページ実装 → ローカルで表示 → React版と並べて照合（PC幅・900px以下）
→ 差異があれば problem.txt に記録 → 修正 → 再照合 → 全て一致してから次へ
```
- 複数ページをまとめて「完成」としない
- 照合は見た目だけでなく、アニメーション・ホバー・ドラッグ等のインタラクションも対象

### 4. 問題発生時のワークフロー（グローバルルール準拠・省略禁止）
```
問題発生 → problem.txt確認 → TodoWrite追加 → 原因調査 → 修正計画
→ problem.txt記録（P番号を振る） → 修正実施 → 動作確認 → TodoWrite完了 → problem.txt更新
```

### 5. Phase単位の進行ルール（勝手に進めない）
**1つのPhase（Phase 1, Phase 2, …の大区分）が完了するたびに必ず作業を止め、ユーザーの指示を仰ぐこと。指示なしに次のPhaseへ進んではならない。**

```
Phase内の全タスク実施 → progress.txt更新 → Gitにコミット＆プッシュ
→ ★作業停止★ → ユーザーへ完了報告と確認依頼 → 指示を受けて次のPhaseへ
```

- Phase完了時の作業停止前に、**必ずGitへのコミットおよびプッシュを行う**
- コミットメッセージは `<type>: <description>` 形式（例: `feat: Phase 1 基盤構築`）
- 途中のタスク単位でのコミットは任意だが、Phase完了時のコミット＆プッシュは省略禁止

### 6. タスク・進捗の記録
- AIが進める作業は `progress.txt`（Phase番号付き）で管理し、完了したら即時ステータスを更新する
- 人間の操作が必要な作業は `manual-work.txt`（Work番号付き）で管理する
- 新しいタスクを追加する際は、**Sonnetクラスのモデルでも問題なく実装できる粒度**（対象ファイル・変更内容・完了条件が明確な単位）に分解する

### 7. 素材の取り扱い
- 画像等の素材は必ず `/Users/jime0083/aishin/public/images/` からコピーして使用する（新規生成・別素材への差し替えは禁止）
- テーマ内の配置先: `wp-content/themes/aishin/assets/images/`

## プロジェクト概要

| 項目 | 内容 |
|------|------|
| 参照元（デザインの正） | `/Users/jime0083/aishin`（React 18 + Vite + TS の SPA） |
| 参照元 本番URL | https://jime0083.github.io/aishin/ |
| 成果物 | WordPress クラシックテーマ「aishin」自作 |
| ローカル環境 | Docker（docker-compose: wordpress + mysql + phpmyadmin） |
| 本番環境 | AWS Lightsail（WordPress Bitnamiイメージ・東京リージョン） |
| ドメイン/SSL | 格安ドメイン取得 + Let's Encrypt（bncert-tool）でHTTPS化 |
| カスタム投稿 | 社員インタビューのみ CPT `interview` + ACF（無料版）で管理 |
| エントリーフォーム | React版と同じ**ダミー送信**（実送信なし）を忠実に再現 |
| JSライブラリ | GSAP 3.12.5 / ScrollTrigger / matter-js 0.20.0（テーマに同梱・React版とバージョン一致） |
| フォント | Google Fonts: Syne (700/800) / Zen Kaku Gothic New (400/500/700/900) |

## ページ構成とURL

| ページ | React版URL | WordPress版URL | テンプレート |
|--------|-----------|----------------|--------------|
| トップ | `/#/` | `/` | front-page.php |
| サービス | `/#/service` | `/service/` | page-service.php |
| 実績 | `/#/works` | `/works/` | page-works.php |
| インタビュー詳細 | `/#/interview/01`〜`03` | `/interview/01/`〜`03/` | single-interview.php |
| キャリア | `/#/career` | `/career/` | page-career.php |
| エントリー | `/#/entry` | `/entry/` | page-entry.php |
| 404 | `*` | 404 | 404.php |

※HashRouter（`#/`）はGitHub Pages都合のため、WordPress版では通常のパーマリンク（`/%postname%/`）を使用する（決定済み・要件定義書参照）。ナビの現在ページ表示はReact Router NavLink と同じく `active` クラスで行う（`.header__link.active::after` がCSSにある）。

## 記録ファイル

- `requirements.md` — 要件定義書（デザイン再現要件・データ設計・決定事項）
- `progress.txt` — AI作業タスク（Phase番号付き）
- `manual-work.txt` — 人間作業タスク（Work番号付き）
- `problem.txt` — 問題・修正の記録（P番号付き）
- `docs/aws-deploy-guide.md` — AWS公開手順書（Phase 7で作成）

## コマンド

| コマンド | 用途 |
|----------|------|
| `docker compose up -d` | ローカルWordPress起動（http://localhost:8000） |
| `docker compose down` | 停止 |
| `docker compose exec wordpress wp <cmd> --allow-root` | WP-CLI実行 |
| （React版）`cd /Users/jime0083/aishin && npm run dev` | 照合用リファレンス起動 |
