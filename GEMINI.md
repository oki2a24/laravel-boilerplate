# Gemini Code Assistant Context

## プロジェクト概要

このプロジェクトは、RESTful API のバックエンドとシングルページアプリケーション（SPA）のフロントエンドを持つ Web アプリケーションのひな形（ボイラープレート）です。

*   **バックエンド:** JSON API リソースサーバーとして機能する Laravel 9 アプリケーション。
    *   データベース: PostgreSQL
    *   主要ライブラリ: `laravel/sanctum` を用いた認証。
    *   コード品質: 静的解析とフォーマットツールが重点的に設定されており、`larastan/larastan` (PHPStan), `rector/rector`, `laravel/pint`, `barryvdh/laravel-ide-helper` が含まれています。
*   **フロントエンド:** Vue.js 3 アプリケーション。
    *   ビルドツール: 高速な開発とバンドルを実現する Vite が Laravel プロジェクトに統合されています。
    *   UI: Bootstrap 5
    *   ルーティング: `vue-router`
    *   コード品質: コードのリンティングとフォーマットのために `ESLint` と `Prettier` がセットアップされています。
*   **開発環境:** 環境全体が Docker を用いてコンテナ化されており、Docker Compose で管理されています。一般的なコマンドのショートカットとして `Makefile` が提供されています。

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

### フロントエンド (Vue.js/Vite)

これらのコマンドは `make` 経由で実行します:

*   `make npm-dev`: ホットリロード機能付きの Vite 開発サーバーを起動します。
*   `make npm-lint`: ESLint を使って JavaScript と Vue のファイルをリントします。
*   `make npm-format`: Prettier を使ってコードをフォーマットします。
