# Gemini Code Assistant Context

## プロジェクト概要

このプロジェクトは、RESTful API のバックエンドとシングルページアプリケーション（SPA）のフロントエンドを持つ Web アプリケーションのひな形（ボイラープレート）です。

*   **バックエンド:** JSON API リソースサーバーとして機能する Laravel 12 アプリケーション。
    *   データベース: PostgreSQL
    *   主要ライブラリ: `laravel/sanctum` を用いた認証、`laravel/boost` による AI 開発支援。
    *   コード品質: 静的解析とフォーマットツールが重点的に設定されており、`larastan/larastan` (PHPStan), `rector/rector`, `laravel/pint`, `barryvdh/laravel-ide-helper` が含まれています。
*   **フロントエンド:** Vue.js 3 アプリケーション。
    *   ビルドツール: 高速な開発とバンドルを実現する Vite が Laravel プロジェクトに統合されています。
    *   UI: Bootstrap 5
    *   ルーティング: `vue-router`
    *   コード品質: コードのリンティングとフォーマットのために `ESLint` 和 `Prettier` がセットアップされています。
*   **開発環境:** 環境全体が Docker を用いてコンテナ化されており、Docker Compose で管理されています。一般的なコマンドのショートカットとして `Makefile` が提供されています。

## AI エージェントへの重要指示 (AI Guidelines)

本プロジェクトでは **Laravel Boost** を導入しています。AI エージェント（私を含む）は、以下の規律を遵守しなければなりません。

1.  **詳細規律の参照**: 具体的なコーディング規約や Laravel 固有の作法については、プロジェクトルートの **`GEMINI.md`**（本ファイル）内にある `<laravel-boost-guidelines>` セクションおよび `.agents/` 配下のガイドラインを最優先で参照すること。
2.  **スキルの活用**: `laravel-best-practices` スキルを常に念頭に置き、Laravel の慣習に沿った実装を行うこと。
3.  **ツールの使用**: 可能であれば `make php-boost-mcp` 等を通じて Boost の MCP ツールを活用し、正確なプロジェクト情報を取得すること。

## プロジェクトのビルドと実行

以下の手順は、プロジェクトの `README.md` と `Makefile` に基づいています。

1.  **初期セットアップ:**
    *   Docker 環境ファイルを作成します: `cp .env.example .env.docker`
    *   Laravel アプリケーションの環境ファイルを作成します: `cp .env.example .env`

2.  **コンテナの起動:**
    ```bash
    make up
    ```

3.  **依存関係のインストール:**
    ```bash
    make app
    # コンテナ内で
    composer install
    npm install
    ```

4.  **アプリケーションの初期化:**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **開発サーバーの実行:**
    *   フロントエンド用の Vite 開発サーバーを起動します:
        ```bash
        make npm-dev
        ```

6.  **アプリケーションへのアクセス:**
    *   上記の手順が完了すると、アプリケーションは `http://localhost` でアクセス可能になります。

## 開発の規約とコマンド

`Makefile` と `package.json` には、開発ワークフローのためのいくつかのコマンドが定義されています。

### バックエンド (PHP/Laravel)

これらのコマンドは `make` 経由で実行します:

*   `make app`: `app` コンテナ内で bash シェルを開きます。
*   `make php-check-all`: すべての PHP コード品質チェックを実行します。
*   `make php-ide-helper`: オートコンプリートを改善するための IDE ヘルパーファイルを生成します。
*   `make php-pint`: Laravel Pint を実行して PHP コードをフォーマットします。
*   `make php-rector`: Rector を実行してコードの自動リファクタリングを行います。
*   `make php-stan`: Larastan (PHPStan) を実行して静的解析を行います。
*   `make php-test`: PHPUnit テストスイートを実行します。
*   **Laravel Boost 連携**:
    *   `make php-boost-update`: AI ガイドラインとスキルを最新状態に更新します（パッケージ更新時に自動実行されます）。
    *   `make php-boost-add-skill skill=...`: 指定したスキルを追加します。
    *   `make php-boost-mcp`: AI コンテキストサーバー (MCP) を起動します。

### フロントエンド (Vue.js/Vite)

これらのコマンドは `make` 経由で実行します:

*   `make npm-dev`: ホットリロード機能付きの Vite 開発サーバーを起動します。
*   `make npm-lint`: ESLint を使って JavaScript と Vue のファイルをリントします。
*   `make npm-format`: Prettier を使ってコードをフォーマットします。

## Gemini Added Memories

*   このプロジェクトは Laravel 12 にアップグレード済みである。過去の Laravel 11 へのアップグレード経緯は `docs/LARAVEL11_UPGRADE_GUIDE.md` を参照すること。
*   Laravel 12 の新構造（`bootstrap/app.php` でのルーティング・ミドルウェア管理等）に準拠している。
*   AI 開発支援ツールとして **Laravel Boost** が導入されており、`boost.json` および `.agents/` でガイドラインが管理されている。
*   `composer update` 実行時に `php artisan boost:update` が自動実行され、AI ガイドラインが最新化される。
*   本プロジェクトでは `generalist` サブエージェントの使用を禁止する。全ての作業はメインエージェントが直接実行し、各ステップでの `git status` 確認と小まめなコミットを通じて、変更の透明性と制御を完全に維持すること。

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v13
- rector/rector (RECTOR) - v2
- eslint (ESLINT) - v9
- prettier (PRETTIER) - v3
- vue (VUE) - v3

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
