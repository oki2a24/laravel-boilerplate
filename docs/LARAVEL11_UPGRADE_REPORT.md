# Laravel 11 スリムスケルトン・アップグレード完了レポート

## 概要
本プロジェクトを Laravel 9 から Laravel 11 の最新構造（スリムスケルトン / Slim Skeleton）へと完全にアップグレードしました。レガシーなファイル構造を廃止し、フレームワークの組み込み機能と集約された設定パターンを採用することで、保守性の高い最新のボイラープレートへと進化させました。

## 主要な変更点

### 1. データベース構造の刷新
- **マイグレーションの集約**: 古い個別のマイグレーションファイルを削除し、Laravel 11 標準の集約形式（`0001_01_01_...`）に変更しました。
- **テーブル名の標準化**: パスワードリセット用テーブルを `password_resets` から Laravel 11 標準の `password_reset_tokens` へ移行しました。
- **環境変数の統一**: データベース接続 URL を参照する環境変数を、Laravel 11 のデフォルトである `DB_URL` に統一しました（これに伴い `docker-compose.yml` も更新）。

### 2. アプリケーション構造のスリム化
- **ミドルウェアの排除**: `app/Http/Middleware/` 配下のレガシーファイルをすべて削除しました。
- **設定の集約**: ミドルウェアの設定（認証リダイレクト、エイリアス等）を `bootstrap/app.php` に集約しました。
- **プロバイダーの統合**: `EventServiceProvider` を廃止し、その機能を `AppServiceProvider` に統合しました。

### 3. 設定ファイルの最新化
- `config/` 内の主要な設定ファイルを Laravel 11 の最小構成形式に刷新しました。
- `cors.php` を削除し、フレームワークのデフォルト機能に委ねる形にしました。
- `sanctum.php` のミドルウェア参照をフレームワーク内蔵クラスへ更新しました。

## 移行後のディレクトリ構造（主要部分）
```text
laravel/
├── app/
│   ├── Http/
│   │   └── Middleware/ (空)
│   └── Providers/
│       └── AppServiceProvider.php (イベントリスナーを統合)
├── bootstrap/
│   ├── app.php (ミドルウェア・ルーティング・例外の設定を集約)
│   └── providers.php (AppServiceProvider のみ登録)
├── config/ (Laravel 11 最小構成)
└── database/
    └── migrations/ (0001_01_01_... 形式)
```

## 検証結果
- **Unit/Feature Tests**: 17 passed (100%)
- **Static Analysis (PHPStan)**: No errors (Level 9 / Larastan)
- **Code Style (Pint)**: Fixed & Passed
- **Refactoring (Rector)**: Applied & Passed

## 未来への申し送り
- 今後、独自のミドルウェアが必要になった場合は、`php artisan make:middleware` で作成し、`bootstrap/app.php` の `withMiddleware` 内で登録してください。
- イベントリスナーの追加は、原則として `AppServiceProvider` の `boot` メソッド内で行います。
- `laravel-11-fresh` ディレクトリは本作業をもって完全に削除されました。
