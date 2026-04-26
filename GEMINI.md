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

1.  **詳細規律の参照**: 具体的なコーディング規約や Laravel 12 固有の作法については、プロジェクトルートの **`BOOST.md`**（実体は `laravel/GEMINI.md`）および `.agents/` 配下のガイドラインを最優先で参照すること。
2.  **スキルの活用**: `laravel-best-practices` スキルを常に念頭に置き、Laravel の慣習に沿った実装を行うこと。
3.  **ツールの使用**: 可能であれば `make php-boost-mcp` 等を通じて Boost の MCP ツールを活用し、正確なプロジェクト情報を取得すること。

## プロジェクトのビルドと実行

以下の手順は、プロジェクトの `README.md` と `Makefile` に基づいています。

1.  **初期セットアップ:**
    *   Docker 環境ファイルを作成します: `cp .env.example .env`
    *   Laravel アプリケーションの環境ファイルを作成します: `cp laravel/.env.example laravel/.env`

2.  **コンテナの起動:**
    ```bash
    make up
    ```

3.  **依存関係のインストール:**
    *   PHP の依存関係をインストールします:
        ```bash
        docker compose exec --user=app app composer install
        ```
    *   JavaScript の依存関係をインストールします:
        ```bash
        docker compose exec --user=app app npm install
        ```

4.  **アプリケーションの初期化:**
    *   アプリケーションキーを生成します:
        ```bash
        docker compose exec --user=app app php artisan key:generate
        ```
    *   データベースマイグレーションを実行します:
        ```bash
        docker compose exec --user=app app php artisan migrate
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
*   AI 開発支援ツールとして **Laravel Boost** が導入されており、`laravel/boost.json` および `laravel/.agents/` でガイドラインが管理されている。
*   `composer update` 実行時に `php artisan boost:update` が自動実行され、AI ガイドラインが最新化される。
*   本プロジェクトでは `generalist` サブエージェントの使用を禁止する。全ての作業はメインエージェントが直接実行し、各ステップでの `git status` 確認と小まめなコミットを通じて、変更の透明性と制御を完全に維持すること。
