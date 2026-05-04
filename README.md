# 🚀 Laravel Modern Boilerplate

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

Laravel 13 を RESTful API サーバーとし、Vue.js 3 + Vite によるモダンな SPA を統合したフルスタック・ボイラープレートです。
開発効率を最大化するための Docker 環境、静的解析ツール、そして AI (Gemini) との高度な連携機能を備えています。

---

## 🏗️ 技術スタック

### Backend (Laravel 13 Ecosystem)
- **Framework:** Laravel 13 (PHP 8.4+)
- **Auth:** Laravel Sanctum (SPA Authentication)
- **Analysis:** Larastan (Static Analysis), Laravel Pint (Code Style)
- **Testing:** PHPUnit 13
- **Automation:** Rector (Refactoring), Laravel Boost (AI Context Optimization)

### Frontend (Vue.js 3 Ecosystem)
- **Framework:** Vue.js 3 (Composition API)
- **Bundler:** Vite 6
- **Router:** Vue Router 4
- **Styling:** Bootstrap 5
- **Lint/Format:** ESLint 9, Prettier 3

### Infrastructure
- **Container:** Docker / Docker Compose
- **Web:** Apache 2.4 (SSL Support)
- **DB:** PostgreSQL

---

## ⚡ クイックスタート

### 1. 環境変数の準備
プロジェクトルートで以下のコマンドを実行し、環境変数ファイルをコピーします。

```bash
cp .env.docker.example .env.docker
cp .env.example .env
```

### 2. コンテナの起動
Makefile を使用して環境を立ち上げます。

```bash
make up
```

### 3. 初期化処理
コンテナに入り、依存関係のインストールと初期設定を行います。

```bash
make app
# コンテナ内蔵 bash に入ります
composer install
npm install
php artisan key:generate
php artisan migrate
exit
```

### 4. 開発サーバーの起動
Vite のホットリロードを有効にするため、開発サーバーを起動します。

```bash
make npm-dev
```

ブラウザで `http://localhost` にアクセスしてください。

---

## 🛠️ 開発コマンド (Makefile)

このプロジェクトでは、頻繁に使用するコマンドを `Makefile` に集約しています。

| コマンド | 説明 |
| :--- | :--- |
| `make up` | Docker コンテナをバックグラウンドで起動 |
| `make stop` | Docker コンテナを停止 |
| `make app` | `app` コンテナの bash に接続 |
| `make php-check-all` | 静的解析、フォーマット、テストを全実行 |
| `make php-test` | PHPUnit テストの実行 |
| `make npm-dev` | Vite 開発サーバーの起動 |
| `make npm-lint-format` | フロントエンドのリントとフォーマット |

---

## 🤖 AI (Gemini/Boost) との連携

このプロジェクトは **Laravel Boost** を導入しており、Gemini 等の AI エージェントがコードベースを深く理解できるように最適化されています。

- **AI コンテキストサーバー:** `make php-boost-mcp` で MCP サーバーを起動できます。
- **ガイドラインの更新:** `make php-boost-update` で最新のコーディング規約を同期します。
- **指示書:** 詳細な規律は `GEMINI.md` に記載されています。

---

## 📄 ライセンス

このプロジェクトは [MIT License](LICENSE) の下で公開されています。
