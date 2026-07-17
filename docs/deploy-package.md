# 本番移行手順（テーマ・データの反映）

ローカルで完成したサイトを Lightsail 本番環境へ反映する手順。
前提: aws-deploy-guide.md の手順9（HTTPS化）まで完了していること。

---

## 1. テーマzipの作成（ローカル・AIが実行可能）

```bash
cd /Users/jime0083/aishin-wordpress/wp-content/themes
zip -r aishin-theme.zip aishin -x "*.DS_Store"
```

## 2. テーマのアップロード・有効化（本番管理画面）

1. `https://<ドメイン>/wp-admin/` にログイン
2. 外観 > テーマ > 新規追加 > **テーマのアップロード** → `aishin-theme.zip` を選択してインストール
3. 「有効化」をクリック

## 3. ACFプラグインの導入（本番管理画面）

1. プラグイン > 新規追加 → 「Advanced Custom Fields」を検索
2. 「Advanced Custom Fields」（WP Engine製・無料版）をインストール・有効化

## 4. シードスクリプトの実行（SSH）

ローカルの seed/ を本番サーバーへ転送して実行する。

### 4-1. seedファイルの転送

ローカルのターミナルから（LightsailのSSHキーを使用）:
```bash
scp -i <SSHキー.pem> seed/seed-pages.php seed/seed-interviews.php bitnami@<静的IP>:/tmp/
```

※scpが使えない場合の代替: Lightsailブラウザ内SSHで `nano /tmp/seed-pages.php` を開き、
ファイル内容を貼り付けて保存（seed-interviews.php も同様）。

### 4-2. 実行

SSH上で:
```bash
sudo wp eval-file /tmp/seed-pages.php --path=/opt/bitnami/wordpress
sudo wp eval-file /tmp/seed-interviews.php --path=/opt/bitnami/wordpress
sudo wp rewrite flush --path=/opt/bitnami/wordpress
rm /tmp/seed-pages.php /tmp/seed-interviews.php
```

- 出力に `Success:` が出れば完了
- どちらのスクリプトも**冪等**（再実行しても重複作成されない）

## 5. 反映確認

| URL | 期待 |
|-----|------|
| `https://<ドメイン>/` | トップ（ローダー→FV） |
| `https://<ドメイン>/service/` | SERVICE |
| `https://<ドメイン>/works/` | WORKS |
| `https://<ドメイン>/career/` | CAREER |
| `https://<ドメイン>/entry/` | ENTRY（フォーム） |
| `https://<ドメイン>/interview/01/` 〜 `03/` | インタビュー3名 |
| 存在しないURL | NOT FOUND（404デザイン） |

表示されない場合: 管理画面 > 設定 > パーマリンク → 「投稿名」を選択して「変更を保存」
（リライトルールを再生成）。

## 6. テーマ更新時の再デプロイ

2回目以降のテーマ更新は:
1. 手順1でzipを作り直す
2. 外観 > テーマ > 新規追加 > テーマのアップロード → 「アップロードしたもので現在のものを置き換える」を選択

（コンテンツ＝インタビューはDB管理のため、テーマ更新で消えることはない）
