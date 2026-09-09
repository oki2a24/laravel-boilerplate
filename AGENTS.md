<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.4. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

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

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

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
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== tests rules ===

# Test Enforcement

- Test every code change by adding or updating a test.
- Run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

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

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>

=== execution protocol ===

# 環境実行プロトコル — 厳格な制約条件

## 環境概要

本プロジェクトは Docker 環境で動作しています。すべてのコマンドはコンテナ内で実行してください。ホストマシン上での直接実行は禁止します。

## コマンド実行の優先順位

コマンドを実行する際、以下の優先順位を厳格に守ってください:

### レイヤー 1: Makefile ターゲット（最優先 — 常に最初に確認する）

すべてのコマンドは、まず Makefile にターゲットが存在するか確認します。これは必須のステップです。

1. Makefile を確認する: `cat Makefile` または `grep ターゲット名 Makefile`
2. `make <ターゲット>` で実行する（これにより確実に Docker 経由でコマンドが実行される）
3. 本プロジェクトの利用可能な Makefile ターゲット:

| ターゲット             | 概要                                             |
| -------------------- | ------ |
| `make up`          | Docker コンテナを起動する                        |
| `make stop`        | Docker コンテナを停止する                        |
| `make app`         | コンテナ内に bash シェルを開く                   |
| `make php-check-all` | 全 PHP チェック（pint, rector, stan, test）をまとめて実行 |
| `make php-pint`    | Laravel Pint で PHP コードをフォーマットする        |
| `make php-rector`  | Rector で PHP コードを自動リファクタリングする      |
| `make php-stan`    | PHPStan で静的解析を行う                           |
| `make php-test`    | PHPUnit テストスイートを実行する                    |
| `make php-ide-helper` | IDE 補助モデルを生成する                        |
| `make npm-dev`     | npm 開発用ビルドを実行する                          |
| `make npm-lint`    | ESLint を実行する                                  |
| `make npm-format`  | Prettier でフロントエンドコードをフォーマットする    |
| `make npm-lint-format` | npm リントとフォーマットを実行する               |
| `make php-boost-update` | Laravel Boost リソースを更新する                |
| `make php-boost-add-skill` | Boost スキルを追加または更新する（skill=...） |
| `make php-boost-mcp`  | Boost MCP サーバを実行する                         |

**重要な原則: `php artisan ...`、`composer ...`、`npm ...` を実行したいとき、必ず最初に Makefile を確認してください。それがあなたの第一行動でなければなりません。**

### レイヤー 2: docker compose exec（フォールバック）

必要なコマンドの Makefile ターゲットが存在しない場合:

1. 形式: `docker compose exec --user=app app <コマンド>`
2. 例: `docker compose exec --user=app app php artisan route:list`
3. 例: `docker compose exec --user=app app ./vendor/bin/pint --dirty`

### レイヤー 3: ホスト側直接実行（禁止 — 断じて使用しない）

ホスト上での以下の実行は厳に禁止します:

- `php artisan ...`（禁止。代わりに `make php-test` または `docker compose exec` を使用する）
- `composer install ...`（禁止）
- `php vendor/bin/...`（禁止）
- `npm run ...`（禁止）
- `node ...`（禁止）
- ホスト上での任意の `php`、`composer`、`npm`、`node`、`vendor/` 配下のバイナリ実行

もしコマンドが `command not found` または `file not found` で失敗した場合、それは依存関係の欠陥ではなく**環境の不一致として認識**してください。正しい実行経路（レイヤー 1 またはレイヤー 2）に再マッピングしてください。

## コマンド再マッピング — 思考ステップ（必須）

**コマンドを実行する前に、必ず以下の思考ステップを挟んでください:**

1. **STOP**: コマンドを打たないでください。すぐに手を止めてください。
2. **思考**: このプロジェクトは Docker を使っているか？→ はい！Makefile がある。
3. **確認**: Makefile に必要なコマンドのターゲットがあるか？→ あれば `make <ターゲット>` を使う。
4. **フォールバック**: Makefile ターゲットがないか？→ `docker compose exec --user=app app <コマンド>` を使う。
5. **実行**: レイヤー 1 またはレイヤー 2 のみで実行してください。

## 自己修正ループ（自動リカバリ）

`command not found`、`file not found`、`PHP Fatal error` などのホスト環境エラーを検知したら:

1. **STOP**: 現在の即刻に実行を停止してください。
2. **認識**: これは環境違反です。コマンドはコンテナ内で実行すべきです。
3. **探索**: Makefile を読み直してください（`cat Makefile`）。正しいターゲットを探してください。
4. **再マッピング**: 本来やりたかったコマンドを、Makefile ターゲットまたは `docker compose exec` に再マッピングしてください。
5. **リトライ**: 正しい Docker 経路で再実行してください。

**同じコマンドをホストで再実行しないでください！ホストにパッケージをインストールしようとしてもうなりません！**

## 実行前のチェックリスト

コマンドを実行する前に必ず確認してください:

1. [ ] Makefile にターゲットがあるか確認したか？
2. [ ] ターゲットがあれば `make <target>` を使うか？
3. [ ] ターゲットがなければ `docker compose exec --user=app app <cmd>` を使うか？
4. [ ] ホストで直接コマンドを実行していないか？
