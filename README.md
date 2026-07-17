# 株式会社アイシン コーポレートサイト（WordPress版）

React SPA版（https://jime0083.github.io/aishin/ ）と完全に同一デザインのWordPressサイト。
プロジェクトのルール・要件は `CLAUDE.md` / `requirements.md` を参照。

## ローカル開発環境（Docker）

### 前提
- Docker Desktop がインストール・起動済みであること（manual-work.txt Work 1.1）

### 起動

```bash
docker compose up -d
```

| URL | 内容 |
|-----|------|
| http://localhost:8000 | WordPress（フロント） |
| http://localhost:8000/wp-admin | WordPress 管理画面 |
| http://localhost:8080 | phpMyAdmin |

### WordPress初期インストール（初回のみ・WP-CLIで実行）

```bash
docker compose run --rm wpcli core install \
  --url=http://localhost:8000 \
  --title="株式会社アイシン" \
  --admin_user=admin \
  --admin_password=admin \
  --admin_email=dev@example.com \
  --locale=ja
docker compose run --rm wpcli language core install ja --activate
docker compose run --rm wpcli theme activate aishin
```

※ `admin / admin` は**ローカル開発専用**の認証情報（本番では使用しない）。

### WP-CLI の使い方

```bash
docker compose run --rm wpcli <コマンド>
# 例
docker compose run --rm wpcli core version
docker compose run --rm wpcli theme list
```

### 停止・初期化

```bash
docker compose down          # 停止（データは保持）
docker compose down -v       # 停止してDB・WP本体を初期化（テーマは ./wp-content に残る）
```

## ディレクトリ構成

```
├── docker-compose.yml            # ローカル環境定義
├── wp-content/themes/aishin/     # 自作テーマ（Git管理の中心）
│   ├── style.css                 # テーマヘッダー（スタイルは書かない）
│   ├── functions.php
│   ├── assets/css/               # React版CSSの無改変コピー
│   ├── assets/images/            # React版から複製した画像素材
│   ├── assets/vendor/            # gsap / ScrollTrigger / matter-js（同梱）
│   └── assets/js/                # 自作JS（React版ロジックの移植）
├── seed/                         # 初期データ投入スクリプト（WP-CLI用）
└── docs/                         # AWSデプロイ手順書 等
```

## 記録ファイル

- `progress.txt` — AI作業タスク（Phase管理）
- `manual-work.txt` — 人間作業タスク（Work管理）
- `problem.txt` — 問題・修正の記録
