# デザイン仕様書：Laravel 11 スリムスケルトン・アップグレードの完遂

## 1. 目的
本プロジェクトを Laravel 11 の「Slim Skeleton」構造に完全に適合させ、不要なレガシーファイルを排除し、最新のベストプラクティス（集約されたマイグレーション、ミドルウェア設定の集約など）を適用する。

## 2. 変更内容の詳細

### 2.1 ミドルウェアとプロバイダーのクリーンアップ
- **アクション**:
    - `app/Http/Middleware/` 配下の全ファイルを削除。
    - `app/Providers/EventServiceProvider.php` を削除。
    - `bootstrap/app.php` および `bootstrap/providers.php` を更新し、内蔵ミドルウェアと `AppServiceProvider` に機能を統合する。
    - `config/sanctum.php` 等のミドルウェア参照パスを内蔵クラスへ更新。
- **検証**: 削除・統合の各ステップ後に `make php-test` を実行。

### 2.2 設定ファイルの最新化
- **アクション**:
    - `config/` 内の `auth.php`, `database.php` 等を Laravel 11 形式に刷新。
    - 環境変数 `DATABASE_URL` への対応や、テーブル名 `password_reset_tokens` への変更を反映。
    - `cors.php` を削除（`bootstrap/app.php` での管理に移行）。
- **検証**: 設定変更ごとに `php artisan config:show` 等で意図通りの値が読み込まれているか確認し、テストを実行。

### 2.3 マイグレーションの集約とDB整合性
- **アクション**:
    1.  既存のマイグレーションをすべてロールバック (`php artisan migrate:rollback`)。
    2.  古いマイグレーションファイルを削除。
    3.  Laravel 11 標準の集約ファイル (`0001_01_01_...`) を配置。
    4.  新規マイグレーションを実行 (`php artisan migrate`)。
- **検証**: `php artisan migrate:status` で全マイグレーションが適用されていることを確認し、テストを実行。

### 2.4 完了レポートの作成
- **アクション**:
    - `docs/LARAVEL11_UPGRADE_REPORT.md` を作成。
    - 変更されたファイルの一覧、移行した設定、発生した問題とその解決策、および最終的なディレクトリ構造を記録する。

## 3. 成功基準
- `make php-test` がすべてのテストでパスすること。
- `make php-check-all` がすべてパスすること。
- `app/Http/Middleware/` が空、または削除されていること。
- 構造が Laravel 11 スリムスケルトンと一致していること。
- 詳細な完了レポートが作成されていること。

## 4. リスクと対策
- **ロールバックの失敗**: DB接続や依存関係によりロールバックに失敗する可能性がある。事前に `php artisan migrate:status` を確認し、一括または段階的にロールバックする。
- **ミドルウェアの欠落**: `bootstrap/app.php` の記述ミスによる認証エラー。自動テスト（Feature Test）によって、認証が必要なエンドポイントが正しくガードされているか確認する。
