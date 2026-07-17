# AWSデプロイ詳細手順書（Lightsail + WordPress）

AWS未経験の方向けに、このサイトをAWSで公開するまでの全手順を解説します。
manual-work.txt の Work 2〜5 は本書を見ながら実施してください。

---

## 0. 全体像 — AWSとLightsailの仕組み

### AWSとは
Amazonが提供するクラウドサービス群です。「サーバー（コンピュータ）を月額で借りる」のが基本で、
今回はその中の **Lightsail（ライトセイル）** というサービスを使います。

### なぜLightsailか
| | Lightsail | EC2（本格VPS） |
|---|---|---|
| 料金 | **月額固定 $5〜** | 従量課金（読みにくい） |
| セットアップ | WordPress入りのサーバーがボタン1つで完成 | OSから自分で構築 |
| 対象 | 小規模サイト・初心者 | 大規模・カスタム構成 |

Lightsailの「WordPress設計図（Bitnami）」を選ぶと、**Linux + Apache + MySQL + PHP + WordPress がインストール済みのサーバー**が数分で起動します。

### 公開までの流れ
```
AWSアカウント作成 → Lightsailインスタンス作成 → 静的IP割当
→ ドメイン取得 → DNS設定（ドメイン→静的IP） → HTTPS化（無料SSL）
→ テーマ・データを本番に反映 → セキュリティ設定 → 公開
```

### 費用（月額目安）
- インスタンス: $5（約750円）
- 静的IP: 無料（インスタンスにアタッチ中は課金なし）
- ドメイン: 年額1円〜1,500円（レジストラによる）
- SSL証明書: 無料（Let's Encrypt）
- スナップショット: 月$0.5前後
- **合計: 月額 約850〜1,000円**

---

## 1. AWSアカウントの作成（Work 2.1）

1. https://aws.amazon.com/jp/ を開き「AWSアカウントを作成」
2. メールアドレス・アカウント名を入力 → 確認コード入力
3. ルートユーザーのパスワードを設定（**強固なものを。パスワードマネージャー推奨**）
4. 連絡先情報（個人を選択）を入力
5. クレジットカードを登録（$1程度の認証課金が入ることがあるが後で戻る）
6. 電話番号のSMS/音声認証
7. サポートプランは「**ベーシックサポート - 無料**」を選択
8. 完了後、https://console.aws.amazon.com/ にルートユーザーでログインできることを確認

## 2. ルートユーザーのMFA設定（Work 2.2）— 必須

アカウント乗っ取り対策の最重要設定です。作成した直後に必ず行ってください。

1. コンソール右上のアカウント名 → 「セキュリティ認証情報」
2. 「多要素認証 (MFA)」→「MFAデバイスの割り当て」
3. デバイス名を入力し「認証アプリケーション」を選択
4. スマホの認証アプリ（Google Authenticator / Microsoft Authenticator 等）でQRコードをスキャン
5. 表示される6桁コードを2回連続で入力 → 完了
6. 以後ログイン時はパスワード＋6桁コードが必要になる

## 3. 請求アラートの設定（Work 2.3）

想定外の課金を早期検知します。

1. コンソール検索窓で「Budgets」→ AWS Budgets を開く
2. 「予算を作成」→ テンプレート「月次コスト予算」
3. 予算額: **$10**、通知先メールアドレスを入力
4. 作成完了（実績が予算の85%/100%を超えるとメールが届く）

## 4. Lightsail WordPressインスタンスの作成（Work 3.1）

1. コンソール検索窓で「Lightsail」→ Lightsailコンソールを開く（画面が独立している）
2. 「インスタンスの作成」をクリック
3. リージョン: **東京 (ap-northeast-1)** を選択（ゾーンはデフォルトでOK）
4. プラットフォーム: **Linux/Unix**
5. 設計図: 「アプリ + OS」→ **WordPress**（Bitnami製・WordPress単体のもの。Multisiteではない方）
6. プラン: **$5 USD**（512MB RAM / 2 vCPU / 20GB SSD）
   ※メモリ不足が出た場合は後から$10プランへスナップショット経由で移行可能
7. インスタンス名: `aishin-wordpress` など分かりやすい名前
8. 「インスタンスの作成」→ 数分で「実行中」になる
9. インスタンスのパブリックIPをブラウザで開き、WordPressのデフォルトサイトが表示されることを確認

## 5. 静的IPの作成とアタッチ（Work 3.2）

インスタンスのIPは再起動で変わるため、固定IPを割り当てます。

1. Lightsail → 「ネットワーキング」タブ → 「静的IPの作成」
2. リージョン: インスタンスと同じ東京
3. アタッチ先: 作成したインスタンスを選択
4. 名前を付けて作成（**アタッチしている限り無料**。アタッチせず放置すると課金されるので注意）
5. 以後この静的IPがサイトのIPアドレスになる

## 6. SSH接続と初期パスワード取得（Work 3.3）

1. Lightsailのインスタンス画面 → オレンジのターミナルアイコン（ブラウザベースSSH）をクリック
2. 黒い画面（ターミナル）が開いたら以下を実行:
   ```bash
   cat ~/bitnami_application_password
   ```
3. 表示された文字列が **WordPress管理画面の初期パスワード**（ユーザー名は `user`）
4. `http://<静的IP>/wp-admin/` にログインできることを確認

> 💡 ローカルのターミナルから接続したい場合は、Lightsailの「アカウント」→「SSHキー」から
> デフォルトキーをダウンロードし `ssh -i <キー.pem> bitnami@<静的IP>` で接続できます。

## 7. ドメイン取得（Work 4.1）

HTTPSには独自ドメインが必須です。予算最小の選択肢:

| レジストラ | 費用感 | 備考 |
|-----------|--------|------|
| **Cloudflare Registrar**（推奨） | .com 約$10/年（原価・更新も同額） | 隠れコストなし。DNSも高速無料 |
| お名前.com | 初年度1円〜キャンペーンあり | **更新料が高いTLDに注意**。自動更新の設定確認必須 |
| Xserverドメイン | 初年度1円〜 | 同上 |

- ポートフォリオ用途なら `.com` / `.net` / `.dev` 等お好みでOK
- Whois情報公開代行（プライバシー保護）が無料のところを選ぶ
- **取得したドメイン名をAIに共有してください**（以後の手順書内の `<ドメイン>` を置換して案内します）

## 8. DNS設定（Work 4.2）

取得したレジストラのDNS管理画面で、ドメインを静的IPに向けます。

| タイプ | 名前（ホスト） | 値 | TTL |
|--------|---------------|-----|-----|
| A | @（またはドメインそのもの） | <静的IP> | 3600（デフォルト可） |
| A | www | <静的IP> | 3600 |

- 反映まで数分〜数時間かかる
- 確認: ターミナルで `dig +short <ドメイン>` が静的IPを返す／ブラウザで `http://<ドメイン>/` が開く
- ※Route 53は使用しない（ホストゾーン月$0.50を節約。レジストラDNSで十分）

## 9. HTTPS化 — Let's Encrypt（Work 4.3）

Bitnami付属の **bncert-tool** で無料SSL証明書を取得し、HTTPS化＋リダイレクト設定を行います。

1. SSHで接続（手順6と同じ）
2. 以下を実行:
   ```bash
   sudo /opt/bitnami/bncert-tool
   ```
3. 対話プロンプトに以下のように回答:
   - `Domain list []:` → `<ドメイン> www.<ドメイン>`（スペース区切り）
   - `Enable HTTP to HTTPS redirection [Y/n]:` → `Y`
   - `Enable non-www to www redirection` / `Enable www to non-www redirection` → どちらか片方を `Y`
     （**non-www に統一を推奨**: 「www to non-www」を Y、もう片方を n）
   - 変更内容の確認 → `Y`
   - メールアドレス入力（証明書の期限通知用）
   - Let's Encrypt利用規約 → `Y`
4. 完了後 `https://<ドメイン>/` が鍵マーク付きで開き、httpアクセスがhttpsへリダイレクトされることを確認
5. **証明書は自動更新される**（bncertが自動更新ジョブを設定する）

### WordPress側のURL更新
HTTPS化後、SSHで以下を実行してWordPressのサイトURLを更新:
```bash
sudo /opt/bitnami/bnconfig --machine_hostname <ドメイン>
# Bitnamiのバージョンによっては以下でも可
sudo wp option update siteurl "https://<ドメイン>" --path=/opt/bitnami/wordpress
sudo wp option update home "https://<ドメイン>" --path=/opt/bitnami/wordpress
```

## 10. テーマ・データの本番反映（Work 5.1）

詳細は `docs/deploy-package.md` を参照（Phase 7.2で作成）。概要:

1. テーマzipを管理画面「外観 > テーマ > 新規追加 > テーマのアップロード」から導入・有効化
2. プラグイン「Advanced Custom Fields」をインストール・有効化
3. シードスクリプト（seed-pages.php / seed-interviews.php）をSSH上のWP-CLIで実行

## 11. セキュリティ初期設定（Work 5.2）

1. **管理者パスワード変更**: 管理画面 > ユーザー > user > プロフィール編集 → 強固なパスワードに変更
2. **不要プラグイン削除**: Bitnamiに同梱のデモ用プラグイン（All-in-One SEO等、使わないもの）を削除
3. **ログイン試行制限**: プラグイン「Limit Login Attempts Reloaded」をインストール・有効化
4. **自動更新**: 管理画面 > ダッシュボード > 更新 でWordPress本体の自動更新を有効化。
   プラグイン一覧で各プラグインの「自動更新を有効化」もクリック
5. **サイトURL確認**: 設定 > 一般 で「WordPressアドレス」「サイトアドレス」が `https://` になっていること

## 12. 自動スナップショット（Work 5.3）

サーバー丸ごとの日次バックアップです。

1. Lightsail → インスタンス → 「スナップショット」タブ
2. 「自動スナップショット」を有効化（時刻は深夜帯を推奨）
3. 7世代まで自動保持される（容量課金: 月$0.5前後）

## 13. 本番動作確認（Work 5.4）

`docs/production-checklist.md` のチェックリストに従って確認してください。

---

## トラブルシューティング

| 症状 | 対処 |
|------|------|
| サイトが表示されない | インスタンスが「実行中」か確認 → 静的IPのアタッチ確認 → DNS反映待ち（`dig <ドメイン>`） |
| bncert-toolがDNSエラー | DNSの反映前。数時間待ってから再実行 |
| 管理画面にログインできない | パスワードは `cat ~/bitnami_application_password`。変更済みならリセット: `sudo wp user update user --user_pass=<新パスワード> --path=/opt/bitnami/wordpress` |
| ページが404になる | 管理画面 > 設定 > パーマリンク で「投稿名」を選択して保存（リライトルール再生成） |
| メモリ不足でサイトが重い | スナップショット取得 → $10プラン（1GB RAM）の新インスタンスとして復元 → 静的IPを付け替え |
| 誤って課金が増えた | Budgetsのアラートを確認 → 不要なリソース（アタッチされていない静的IP・古いスナップショット）を削除 |

## サーバー操作チートシート（SSH）

```bash
# WordPressのパス
cd /opt/bitnami/wordpress

# WP-CLI（Bitnamiに同梱）
sudo wp <コマンド> --path=/opt/bitnami/wordpress

# Apache再起動
sudo /opt/bitnami/ctlscript.sh restart apache

# 全サービス再起動
sudo /opt/bitnami/ctlscript.sh restart
```
