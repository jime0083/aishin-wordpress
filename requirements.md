# 要件定義書 — 株式会社アイシン コーポレートサイト WordPress版

作成日: 2026-07-17
参照元: `/Users/jime0083/aishin`（React 18.3 + Vite 5 + TypeScript の SPA / 全コード調査済み）

---

## 1. 目的

React SPAとして制作済みのコーポレートサイト（採用サイト）を、**デザインを一切変えずに** WordPress化する。
インタビュー記事をWordPress管理画面から追加・編集できるようにして保守性を高め、AWS（Lightsail）で公開する。

## 2. 絶対条件

1. **デザイン完全一致**: レスポンシブ（ブレークポイント900px）まで含め、React版と一切のデザイン違いを許さない
2. **調査済みコードが正**: 迷ったら必ずReact版ソースを参照する（本書の記述と食い違う場合もソースが正）
3. **不明点ゼロ運用**: 不明点があれば必ず質問し、解消してから作業する（CLAUDE.md 参照）
4. **素材は参照元からコピー**: `/Users/jime0083/aishin/public/images/`（opt/ 22枚 + ロゴPNG 6種）を使用
5. **タスク粒度**: Sonnetで実装可能な粒度に分解して progress.txt / manual-work.txt に記録する

## 3. 確定済みの決定事項（ユーザー回答 2026-07-17）

| # | 項目 | 決定 |
|---|------|------|
| D-1 | AWS構成 | **Lightsail**（WordPress Bitnamiイメージ・最小プラン $5/月・東京リージョン） |
| D-2 | ドメイン/セキュリティ | 予算最小で本番同等のセキュリティ。**格安ドメインを取得**（年額目安 数百円〜1,500円）し、無料の **Let's Encrypt（bncert-tool）でHTTPS化**。DNSはレジストラ標準DNSを使用（Route 53のホストゾーン料金 $0.50/月を回避）。加えてMFA・SSH鍵・ログイン試行制限・自動スナップショットで防御 |
| D-3 | エントリーフォーム | React版と同じ**ダミー送信**を再現（送信ボタン→「このポートフォリオサイトからは送信できません」画面）。実送信は行わない。将来の実送信切替を見越しJSの送信処理は1関数に集約 |
| D-4 | 管理画面での編集範囲 | **インタビューのみ**カスタム投稿タイプ+カスタムフィールド化。他ページの文言はテンプレートに固定コード化 |
| D-5 | URL構造 | HashRouter（`#/service`）は GitHub Pages 都合のため、WP版は通常パーマリンク（`/service/` 等）を採用。デザインには影響しない |
| D-6 | カスタムフィールド | **ACF（Advanced Custom Fields）無料版**を使用。Q&Aは3問固定（React版のデータ構造が3問のため、無料版にないリピーターは不要）。フィールドはPHPコードで登録しGit管理する |
| D-7 | JSライブラリ | CDNを使わず**テーマに同梱**（gsap 3.12.5 / ScrollTrigger / matter-js 0.20.0。React版とバージョン一致）。「ローディング速度最優先」の方針を踏襲 |

## 4. 技術スタック

| レイヤー | 採用技術 |
|----------|----------|
| CMS | WordPress 最新安定版（6.x）・クラシックテーマ自作（テーマ名: aishin） |
| テンプレート | PHP（front-page.php / page-*.php / single-interview.php / 404.php / template-parts/） |
| CSS | React版の global.css / home.css / subpage.css を**無改変で**移植 |
| JS | React版のロジックを Vanilla JS（ES2020, IIFE/モジュール）へ移植。GSAP / ScrollTrigger / matter-js 同梱 |
| プラグイン | ACF無料版のみ（それ以外は原則追加しない。本番のみログイン保護プラグインを追加） |
| ローカル環境 | Docker Compose（wordpress + mysql:8.0 + phpmyadmin） |
| 本番環境 | AWS Lightsail（Bitnami WordPress・静的IP・HTTPS） |
| データ投入 | WP-CLI シードスクリプト（固定ページ作成・インタビュー3名・画像メディア登録） |

## 5. サイト構成

### 5.1 ページ一覧

| ページ | WP上の実体 | テンプレート | 内容ソース |
|--------|-----------|--------------|-----------|
| トップ | フロントページ | front-page.php | Home.tsx（Loader/Hero/Mission/About/WorksTeaser/InterviewTeaser/CareerTeaser/Marquee/EntryCta） |
| SERVICE | 固定ページ `service` | page-service.php | Service.tsx（SERVICES 3件・svc-intro・Marquee・EntryCta） |
| WORKS | 固定ページ `works` | page-works.php | Works.tsx（WORKS 5件・stat カウントアップ） |
| INTERVIEW詳細 | CPT `interview`（スラッグ 01/02/03） | single-interview.php | InterviewDetail.tsx + interviews.ts（3名） |
| CAREER | 固定ページ `career` | page-career.php | Career.tsx（CAREER_ITEMS 4件・BENEFITS 10件） |
| ENTRY | 固定ページ `entry` | page-entry.php | Entry.tsx（フォーム7項目・PRIVACY_ITEMS 6件・ダミー送信） |
| 404 | — | 404.php | ComingSoon.tsx（NOT FOUND / ページが見つかりません） |

※インタビュー一覧ページは存在しない（React版仕様）。ナビは ABOUT(/) / SERVICE / WORKS / CAREER + ENTRY ボタンのみ。

### 5.2 共通コンポーネント（React → WordPress 対応表）

| React | WordPress実装 | 主な仕様 |
|-------|---------------|----------|
| Header.tsx | header.php + assets/js/header.js | scrollY>24 で `is-scrolled`、バーガーで `is-open`+body overflow hidden、ドロワーリンクの transitionDelay `80+i*50ms`、現在ページに `active` クラス |
| Footer.tsx | footer.php | logo-footer.png、リンク5件、`© {年} Aishin Inc. All Rights Reserved.`（PHP `date('Y')`） |
| CustomCursor.tsx | assets/js/cursor.js | `(pointer:fine)` かつ non-reduced のみ有効。dot lerp 0.4 / ring lerp 0.16、`data-cursor-label` でラベル表示、body に `has-custom-cursor` |
| FloatingBg.tsx | template-parts/floating-bg.php + assets/js/floating-bg.js | SHAPES 11個（種類/left/top/speed/float を完全転記）、スクロール視差ループ（range = vh+600） |
| Loader.tsx | template-parts/loader.php + assets/js/loader.js | トップのみ。カウンター0→100(1.3s power2.inOut)、マーク回転372°、2枚パネルワイプ。reveal時にカスタムイベント発火→Hero開始 |
| LiquidBg.tsx | assets/js/liquid-bg.js | WebGLシェーダー移植（VERT/FRAG原文コピー、TRAIL_COUNT 16、TEXT_ROWS 4、ROW_PHRASES 4行、dpr上限1.75、フォント読込後再描画、IntersectionObserverで停止） |
| Marquee.tsx | template-parts/marquee.php | text×4リピート×2スパン、reverse / tilt バリエーション |
| GiantWord.tsx | template-parts/giant-word.php | `data-giant="left/right"`、スクロールで xPercent 6→-10（scrub 0.6） |
| WordPiece.tsx + puzzleShape.ts | template-parts/word-piece.php + inc/puzzle-paths.php | WORD_PIECES 11個（MISSION〜?）、PUZZLE_PATH / PUZZLE_PATH_LEFT を定数移植 |
| ImagePlaceholder.tsx | inc/image-frame.php（出力関数） | ID→画像URL解決（IMAGE_SOURCES 22件相当）。shape: puzzle / puzzle-left / ellipse 等。img は `loading="lazy" decoding="async"`、alt=ラベル |
| PageHero.tsx | template-parts/page-hero.php + assets/js/page-hero.js | 英字1文字ずつドロップイン（stagger 0.06）→浮遊ループ、GiantWord、物理演算ピース。eyebrow「AISHIN INC. — RECRUIT SITE」 |
| usePhysicsPieces.ts / Hero物理演算 | assets/js/physics-pieces.js | matter-js。gravity y=1.1、restitution 0.45、friction 0.4、frictionAir 0.012、chamfer min(h/2-2,26)、落下開始オフセット: トップ -200 / 下層 -160（引数化）。ドラッグ可・スクロール非阻害・画面外停止・リサイズ対応 |
| useSubpageAnimations.ts / Home.tsx のアニメ | assets/js/animations.js | 下記 5.3 の全アニメーションを1ファイルに集約。skew対象は `<body data-skew-targets="...">` で各テンプレートから指定 |

### 5.3 スクロール連動アニメーション（全ページ共通言語・パラメータはReact版から転記）

1. `[data-reveal]`: opacity 0→1, y 48→0, 0.9s power3.out, start "top 85%"
2. `[data-reveal-group]`: 子要素 y72/scale0.94/rotate±4 → 0, stagger 0.13, start "top 82%"
3. `.section__title`: clipPath inset マスクリビール 1s power4.out, start "top 86%"
4. `[data-giant]`: xPercent 6*dir → -10*dir, scrub 0.6
5. skew演出: スクロール速度→skewY（clamp ±6, velocity/-400, 0.8s power3.outで戻す）
6. `[data-count]`: 0→目標値カウントアップ 1.6s power2.out（data-decimals対応）
7. `.img-ph`: clipPath inset(0 100% 0 0)→0 マスクリビール 1.1s power4.out
8. `.entry__char`: yPercent 60→0 back.out(1.6) stagger 0.07, trigger ".entry" top 70%
9. `.mission__line-inner`: yPercent 110→0 1s power4.out（トップのみ）
10. 全て `gsap.matchMedia('(prefers-reduced-motion: no-preference)')` 内で登録（reduce時は無効化）

skewTargets（ページ別）:
- トップ: `.mission__statement, .works__cards, .interview__cards, .career__list`
- SERVICE: `.svc__points, .svc__steps` / WORKS: `.wrk__rows` / CAREER: `.crr__benefits`
- ENTRY: なし（useSubpageAnimations デフォルト） / インタビュー: `.itv-qa__body`

### 5.4 head / メタ情報（index.html と一致させる）

- `<html lang="ja">`、title「株式会社アイシン | まだ見ぬピースを発明する」
- meta description（React版 index.html の文言をそのまま）、theme-color `#FBF7F2`
- favicon / apple-touch-icon: `logo-mark.png`
- Google Fonts preconnect ×2 + Syne:700,800 / Zen Kaku Gothic New:400,500,700,900

## 6. データ設計（インタビュー）

### 6.1 カスタム投稿タイプ

- post type: `interview`（公開・アーカイブなし・rewrite slug `interview`・supports: title）
- スラッグ: `01` / `02` / `03`（React版のURL `/interview/01` と一致させる）
- 表示順: スラッグ昇順（トップのカード・「ほかの社員も知る。」で使用）

### 6.2 ACFフィールド（PHP登録・interviews.ts の型と1:1対応）

| フィールド | 型 | 対応元 |
|-----------|-----|--------|
| name / name_en / join_year / position / quote / career | テキスト（careerはテキストエリア） | Interview型の同名プロパティ |
| portrait_image / portrait_label | 画像 / テキスト | portraitImgId / portraitImgLabel |
| qa1_heading, qa1_body, qa1_photo, qa1_photo_label | テキスト / テキストエリア / 画像(任意) / テキスト | qa[0]（body は空行区切り→`<p>`分割） |
| qa2_*, qa3_* | 同上 | qa[1], qa[2] |

※qa2はReact版で写真なし（photoは任意フィールド）。初期データ3名分は interviews.ts から全文転記してWP-CLIシードスクリプトで投入する。

### 6.3 トップページとの連動

InterviewTeaser（トップ）と「ほかの社員も知る。」は CPT をクエリして描画（quote / name / position・joinYear / portrait を使用）。カード構造・クラス名はReact版と同一。

## 7. エントリーフォーム仕様（Entry.tsx を忠実に再現）

- 項目: エントリーの種類(select 5択) / きっかけ(select 6択) / お名前 / ご所属 / 電話番号 / メール / メッセージ（全て必須）
- バリデーション（JS・blur時にtouched管理、エラー文言は完全一致）:
  - 空: 「入力してください」（selectは「選択してください」）
  - email: `/^[^\s@]+@[^\s@]+\.[^\s@]+$/`
  - phone: 記号許容・数字10〜11桁「電話番号の形式が正しくありません（半角数字10〜11桁）」
- 個人情報の取り扱い6項目 + 同意チェックで送信ボタン活性化
- 送信 → **実送信せず**「このポートフォリオサイトからは送信できません。」画面へ切替（window.scrollTo(0,0)）

## 8. 非機能要件

| 項目 | 要件 |
|------|------|
| パフォーマンス | React版の「ローディング速度最優先」踏襲。JS/CSSはテーマ同梱・不要プラグインなし・画像は最適化済みopt/を使用 |
| アクセシビリティ | aria属性・role・aria-hidden をReact版と同一に出力。reduced-motion対応 |
| セキュリティ（本番） | HTTPS必須（Let's Encrypt）／AWSアカウントMFA／SSH鍵接続のみ／WP自動更新＋強固な管理者パスワード／ログイン試行制限／Lightsail自動スナップショット（バックアップ） |
| 対応ブラウザ | モダンブラウザ（WebGL非対応時はLiquidBgがCSSフォールバック＝React版と同じ挙動） |
| 検証 | 全ページ×2幅（PC / ≤900px）でReact版とスクリーンショット照合＋インタラクション実機確認 |

## 9. 受け入れ基準

1. 全7テンプレートのレンダリング結果（DOM構造・クラス名・文言）がReact版と一致する
2. PC幅・モバイル幅（900px以下）のスクリーンショット比較で目視差異ゼロ
3. 全アニメーション・インタラクション（ローダー、ピース落下＆ドラッグ、WebGL液体背景、カスタムカーソル、マーキー、リビール群、カウントアップ、フォームバリデーション、ドロワー）がReact版と同挙動
4. インタビューがWP管理画面から追加・編集でき、トップのカードにも自動反映される
5. Lightsail上でHTTPSで公開され、上記1〜4が本番でも成立する
6. ブラウザコンソールにエラーが出ない
