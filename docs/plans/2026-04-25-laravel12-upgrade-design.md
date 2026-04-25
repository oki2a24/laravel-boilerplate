# Laravel 12 アップグレード デザインドキュメント

## 1. 目的
本プロジェクトを Laravel 11 から Laravel 12 へアップグレードし、最新のセキュリティ機能や推奨設定を導入する。

## 2. アプローチ
「推奨設定への追随」アプローチを採用する。単なるバージョンアップに留まらず、Laravel 12 の新しい規約（ストレージパスの変更等）を積極的に取り入れる。

## 3. 変更内容
### 3.1. 依存関係の更新 (`laravel/composer.json`)
- `laravel/framework`: `^12.0`
- `nunomaduro/collision`: `^8.5` (Laravel 12 推奨バージョン)
- その他、`composer update` 実行時に必要となる依存パッケージの更新。

### 3.2. 設定ファイルの最新化
- **`config/filesystems.php`**:
    - `local` ディスクの `root` を `storage_path('app')` から `storage_path('app/private')` へ変更。
    - これは Laravel 12 の新しいデフォルト規約であり、非公開ファイルの隔離を強化する。
- **設定ファイルの同期**:
    - Laravel 12 のクリーンインストール環境（`laravel-12-fresh` を一時的に作成して比較）を参考に、不足している設定値や新しいデフォルト値があれば適用する。

### 3.3. コードとバリデーション
- 現在 `HasUuids` や `image` バリデーションルールは使用されていないため、コードレベルの修正は発生しない見込み。
- 将来的な利用に備え、UUID v7 の採用や SVG バリデーションの変更を `observations/` に記録する。

## 4. 検証計画
1.  **依存関係の解決**: `composer update` がエラーなく完了すること。
2.  **基本動作確認**: `php artisan about` 等のコマンドが正常に動作すること。
3.  **自動テスト**: `make php-test` (PHPUnit) が全件パスすること。
4.  **品質チェック**: `make php-check-all` (Larastan, Pint, Rector) が全件パスすること。

## 5. 考慮事項
- Laravel 11 へのアップグレード時に意図的に維持したファイル（`config/cors.php`, `config/sanctum.php` 等）が Laravel 12 でも正しく動作することを確認する。
