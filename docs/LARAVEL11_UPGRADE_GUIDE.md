# Laravel 11 アプリケーション構造アップグレードガイド

## はじめに

このドキュメントは、Laravel 9 (初期状態) から Laravel 11 の新しいアプリケーション構造への移行作業の記録です。本プロジェクトはLaravelのボイラープレートであるため、Laravel 11で導入された抜本的な構造変更（Slim Skeleton）に対応することを目的としています。

このガイドは、将来のメンテナンス作業や、同様のアップグレードを行う際の参考となるよう、詳細な手順、遭遇した問題、およびその解決策を記録します。

## 比較対象のLaravel 11クリーンインストール環境

今回の移行作業は、以下のコマンドで入手したLaravel 11の最新のひな形を「正解」として、既存のプロジェクトと比較しながら進められました。

```bash
git clone --depth 1 -b 11.x https://github.com/laravel/laravel.git laravel-11-fresh
```

## アップグレード計画（最終決定版）

以下の計画に基づいてアップグレード作業が実行されました。

**基本方針:**
公式リポジトリを「正解」としつつ、国内外の具体的な移行事例（[no-hack-no.life様](https://no-hack-no.life/post/2024-06-03-Slim-Skeleton-migration-guide-and-a-comprehensive-review-of-Laravel/)様の記事など）で示された実践的なテクニックを取り入れ、手動での移行作業を可能な限り具体的かつ安全に進める。

---
#### フェーズ 1: 準備
1.  **ベースラインの確立:**
    *   **内容:** 各種テストと静的解析を実行し、アップグレード前のコードが健全であることを確認済み。
2.  **「正解」のダウンロード:**
    *   **内容:** `git clone --depth 1 -b 11.x https://github.com/laravel/laravel.git laravel-11-fresh` を実行し、比較元となる最新のLaravel 11を `laravel-11-fresh` ディレクトリにダウンロードした。

---
#### フェーズ 2: 依存関係の更新

3.  **`composer.json` / `package.json` の更新:**
    *   **内容:** `laravel-11-fresh` 内の `composer.json` と `package.json` を参照し、このプロジェクトのファイルを更新した。
    *   **情報源:** [公式 `composer.json` (11.x)](https://github.com/laravel/laravel/blob/11.x/composer.json), [公式 `package.json` (11.x)](https://github.com/laravel/laravel/blob/11.x/package.json)

4.  **パッケージのインストール:**
    *   **内容:** `composer update` と `npm install` を実行した。競合発生時は `composer why-not laravel/framework 11.0` で原因を調査した。
    *   **情報源:** [laraveldaily.com - "How to Upgrade Laravel 10 to 11"](https://laraveldaily.com/post/how-to-upgrade-laravel-10-to-11-the-ultimate-guide)

---
#### フェーズ 3: アプリケーション構造の移行（詳細化）

5.  **【構造移行: ミドルウェア】古い `Kernel.php` から `bootstrap/app.php` へ設定を移行する:**
    *   **内容:**
        1.  `laravel-11-fresh/bootstrap/app.php` を現在のプロジェクトにコピーした。
        2.  古い `app/Http/Kernel.php` を参考に、ミドルウェア設定を `withMiddleware(...)` メソッド内に移植した。
    *   **情報源:** [no-hack-no.life - Laravel 11 構造移行ガイド](https://no-hack-no.life/post/2024-06-03-Slim-Skeleton-migration-guide-and-a-comprehensive-review-of-Laravel/)

6.  **【構造移行: ルーティングとコマンド】APIルーティングを再構築し、タスクスケジュールを移行する:**
    *   **内容:** `php artisan install:api` を実行しAPIルーティングを再構築した。古い `app/Console/Kernel.php` のタスクスケジュールは存在しなかったため移行は不要だった。
    *   **情報源:** [laraveldaily.com - "What's New in Routing"](https://laraveldaily.com/post/laravel-11-whats-new-in-routing-api-channels-websockets), [no-hack-no.life - "Task schedule" section](https://no-hack-no.life/post/2024-06-03-Slim-Skeleton-migration-guide-and-a-comprehensive-review-of-Laravel/)

7.  **【構造移行: サービスプロバイダ】カスタムロジックを `AppServiceProvider` へ統合する:**
    *   **内容:** `RouteServiceProvider` のレート制限を `AppServiceProvider` に移植。`EventServiceProvider` の `shouldDiscoverEvents(false)` の移植を `AppServiceProvider` に試みたが `BadMethodCallException` が発生したため中止。最終的に、`EventServiceProvider` を再作成し、`shouldDiscoverEvents()` を `false` に設定して `bootstrap/providers.php` に登録した。
    *   **情報源:** [no-hack-no.life - 「サービスプロバイダの統合」セクション](https://no-hack-no.life/post/2024-06-03-Slim-Skeleton-migration-guide-and-a-comprehensive-review-of-Laravel/)

8.  **【構造移行: エラー処理】古い `Handler.php` から `bootstrap/app.php` へ設定を移行する:**
    *   **内容:** 古い `app/Exceptions/Handler.php` にカスタムロジックがなかったため、移行は不要だった。
    *   **情報源:** [no-hack-no.life - 「エラー処理」セクション](https://no-hack-no.life/post/2024-06-03-Slim-Skeleton-migration-guide-and-a-comprehensive-review-of-Laravel/)

9.  **【構造移行: 設定ファイルと削除】不要なファイルを精査・削除し、移行を終えた古い構造ファイルを削除する:**
    *   **内容:**
        *   古い構造ファイル (`Kernel.php`, `Handler.php`, 各種 `ServiceProvider.php` ) を削除した。
        *   `config/` ディレクトリから `broadcasting.php`, `hashing.php`, `view.php` を削除した。`cors.php`, `sanctum.php`, `ide-helper.php` は維持した。
        *   `routes/channels.php` を削除した。

---
#### フェーズ 4: 最終化と検証

10. **後処理コマンドを実行し、総合テストを再実行する:**
    *   **内容:**
        *   `php artisan migrate` でSanctumのマイグレーションを実行。
        *   `php artisan optimize:clear` でキャッシュをクリア。
        *   `make php-test`, `make php-check-all`, `make npm-lint`, `make npm-format` を実行し、すべてパスすることを確認した。
    *   **遭遇した問題と解決策:**
        *   `make php-test` 実行時に `405 Method Not Allowed` および `Route [verification.verify] not defined` エラーが大量に発生。原因は `bootstrap/app.php` の `withRouting()` メソッドからAPIルートの定義が抜けていたため。
            *   **解決策:** `bootstrap/app.php` の `withRouting()` に `api: __DIR__.'/../routes/api.php',` を追加した。
        *   `php artisan migrate` 実行時に `Class "App\Providers\AuthServiceProvider" not found` エラーが発生。原因は古い `config/app.php` にプロバイダリストが残っていたこと。
            *   **解決策:** `laravel/config/app.php` を `laravel-11-fresh/config/app.php` で上書きし、キャッシュをクリアし、オートロードを再生成した。
        *   `make php-test` 実行時に `MissingRateLimiterException: Rate limiter [api] is not defined.` エラーが発生。原因は `AppServiceProvider` が `bootstrap/app.php` で正しく登録されていなかったため。
            *   **解決策:** `bootstrap/app.php` から `->withProviders(...)` を削除し、Laravel 11の推奨に従い `bootstrap/providers.php` を作成して `AppServiceProvider` を登録した。
        *   `make php-test` 実行時に `BadMethodCallException: Method Illuminate\Events\Dispatcher::shouldDiscoverEvents does not exist.` エラーが発生。原因は `AppServiceProvider` から `Event::shouldDiscoverEvents(false);` を呼び出したため。
            *   **解決策:** `AppServiceProvider` から該当行を削除。その後、元のプロジェクトの `EventServiceProvider` を再作成し、`shouldDiscoverEvents()` を `false` に設定した上で、`bootstrap/providers.php` に登録した。
        *   `make php-check-all` 実行時に `phpstan` エラーが発生 (`Access to constant HOME on an unknown class App\Providers\RouteServiceProvider.`)。原因は `Http/Middleware/RedirectIfAuthenticated.php` が削除済みの `RouteServiceProvider::HOME` を参照していたため。
            *   **解決策:** `Http/Middleware/RedirectIfAuthenticated.php` の `return redirect(RouteServiceProvider::HOME);` を `return redirect('/home');` に修正した。