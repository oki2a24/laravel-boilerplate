# Laravel 11 スリムスケルトン・アップグレード完遂 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、移植された `subagent-driven-development` スキル（推奨）または `executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** レガシーファイルを排除し、Laravel 11 の標準構造（スリムスケルトン）へと完全に移行する。

**アーキテクチャ:** ボトムアップ・アプローチ（DB -> 設定 -> 構造）を採用し、依存関係の矛盾を抑えつつ集約・スリム化を行う。

**技術スタック:** Laravel 11, PHP 8.2+, Sanctum

---

### タスク 1: 既存DBのロールバックとマイグレーション刷新

**ファイル:**
- 削除: `laravel/database/migrations/2014_10_12_000000_create_users_table.php`
- 削除: `laravel/database/migrations/2014_10_12_100000_create_password_resets_table.php`
- 削除: `laravel/database/migrations/2019_08_19_000000_create_failed_jobs_table.php`
- 作成: `laravel/database/migrations/0001_01_01_000000_create_users_table.php` (freshからコピー)
- 作成: `laravel/database/migrations/0001_01_01_000001_create_cache_table.php` (freshからコピー)
- 作成: `laravel/database/migrations/0001_01_01_000002_create_jobs_table.php` (freshからコピー)

- [ ] **ステップ 1: 既存のマイグレーションをロールバック**
実行: `docker compose exec --user=app app php artisan migrate:rollback`
※全テーブルが削除されるまで（または status がすべて Pending になるまで）実行。

- [ ] **ステップ 2: 古いマイグレーションファイルを削除**
実行: `rm laravel/database/migrations/2014_10_12_000000_create_users_table.php laravel/database/migrations/2014_10_12_100000_create_password_resets_table.php laravel/database/migrations/2019_08_19_000000_create_failed_jobs_table.php`

- [ ] **ステップ 3: Laravel 11 標準の集約マイグレーションファイルをコピー**
実行: `cp laravel-11-fresh/database/migrations/0001_01_01_*.php laravel/database/migrations/`

- [ ] **ステップ 4: 新しいマイグレーションを実行**
実行: `docker compose exec --user=app app php artisan migrate`

- [ ] **ステップ 5: テストを実行**
実行: `make php-test`
期待値: テーブル名 `password_resets` が `password_reset_tokens` に変わったため、一部の認証テストが FAIL することを確認。

- [ ] **ステップ 6: コミット**
```bash
git add laravel/database/migrations/
git commit -m "feat: Laravel 11形式の集約マイグレーションに刷新"
```

---

### タスク 2: 設定ファイルの最新化 (Auth, Database, Logging)

**ファイル:**
- 変更: `laravel/config/auth.php`
- 変更: `laravel/config/database.php`
- 変更: `laravel/config/logging.php`
- 削除: `laravel/config/cors.php`

- [ ] **ステップ 1: 設定ファイルを刷新 (freshベース)**
実行:
`cp laravel-11-fresh/config/auth.php laravel/config/auth.php`
`cp laravel-11-fresh/config/database.php laravel/config/database.php`
`cp laravel-11-fresh/config/logging.php laravel/config/logging.php`
`rm laravel/config/cors.php`

- [ ] **ステップ 2: database.php の環境変数対応 (PostgreSQL)**
変更: `laravel/config/database.php`
内容: `connections.pgsql.url` を `env('DATABASE_URL')` に、デフォルトを `env('DB_CONNECTION', 'pgsql')` に設定。

- [ ] **ステップ 3: auth.php の確認**
内容: `passwords.users.table` が `password_reset_tokens` になっていることを確認（freshのデフォルト）。

- [ ] **ステップ 4: テストを実行**
実行: `make php-test`
期待値: PASS (DBスキーマと設定が一致するため)

- [ ] **ステップ 5: コミット**
```bash
git add laravel/config/
git commit -m "feat: 設定ファイルをLaravel 11形式に最新化"
```

---

### タスク 3: ミドルウェアの統合とクリーンアップ

**ファイル:**
- 変更: `laravel/bootstrap/app.php`
- 変更: `laravel/config/sanctum.php`
- 削除: `laravel/app/Http/Middleware/*.php` (ディレクトリ配下すべて)

- [ ] **ステップ 1: bootstrap/app.php を内蔵ミドルウェア使用に更新**
コード例:
```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->alias([
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/home');
    })
```

- [ ] **ステップ 2: config/sanctum.php のミドルウェア参照を内蔵クラスに更新**
変更: `laravel/config/sanctum.php`
内容: `'verify_csrf_token' => \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,` など。

- [ ] **ステップ 3: レガシーミドルウェアファイルの削除**
実行: `rm laravel/app/Http/Middleware/*.php`

- [ ] **ステップ 4: テストを実行**
実行: `make php-test`

- [ ] **ステップ 5: コミット**
```bash
git add laravel/bootstrap/app.php laravel/config/sanctum.php laravel/app/Http/Middleware/
git commit -m "refactor: ミドルウェアを内蔵機能に統合し、レガシーファイルを削除"
```

---

### タスク 4: サービスプロバイダーの統合

**ファイル:**
- 変更: `laravel/app/Providers/AppServiceProvider.php`
- 変更: `laravel/bootstrap/providers.php`
- 削除: `laravel/app/Providers/EventServiceProvider.php`

- [ ] **ステップ 1: AppServiceProvider にイベントリスナーを追加**
変更: `laravel/app/Providers/AppServiceProvider.php`
```php
public function boot(): void
{
    \Illuminate\Support\Facades\Event::listen(
        \Illuminate\Auth\Events\Registered::class,
        \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
    );
}
```

- [ ] **ステップ 2: bootstrap/providers.php から EventServiceProvider を削除**

- [ ] **ステップ 3: EventServiceProvider.php を削除**
実行: `rm laravel/app/Providers/EventServiceProvider.php`

- [ ] **ステップ 4: テストを実行**
実行: `make php-test`

- [ ] **ステップ 5: コミット**
```bash
git add laravel/app/Providers/ laravel/bootstrap/providers.php
git commit -m "refactor: EventServiceProviderをAppServiceProviderに統合"
```

---

### タスク 5: 最終検証とレポート作成

- [ ] **ステップ 1: 全品質チェックの実行**
実行: `make php-check-all`

- [ ] **ステップ 2: 完了レポートの作成**
作成: `docs/LARAVEL11_UPGRADE_REPORT.md`
内容: 変更概要、ディレクトリ構造、移行のポイントを記述。

- [ ] **ステップ 3: laravel-11-fresh の削除**
実行: `rm -rf laravel-11-fresh/`

- [ ] **ステップ 4: 最終コミット**
```bash
git add docs/
git commit -m "chore: アップグレード完了レポートを作成し、参照用ディレクトリを削除"
```
