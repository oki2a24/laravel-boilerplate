# README.md リニューアル 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、`executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** 現在の `README.md` を、モダンで正確なセットアップ手順と技術情報を備えた内容に刷新し、古い TODO を移行する。

**アーキテクチャ:**
1.  現在の `README.md` の不要なセクションを削除し、デザイン案に基づいた新しいセクションを段階的に構築する。
2.  `Makefile` および環境変数設定の整合性を物理的に検証してからドキュメントに記述する。
3.  `code-reviewer` エージェントによる自動レビューを経て、最終的にユーザーによるコードレビューを受けて完了とする。

**技術スタック:**
- Markdown (README.md)
- Makefile (コマンド検証)
- Git (コミット管理)

---

### タスク 1: 新しい README.md のスケルトン作成とヘッダー構築

**ファイル:**
- 変更: `README.md`

- [ ] **ステップ 1: 現在の README.md の内容をリセットし、ヘッダーとバッジを記述する**

```markdown
# 🚀 Laravel Modern Boilerplate

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

Laravel 13 を RESTful API サーバーとし、Vue.js 3 + Vite によるモダンな SPA を統合したフルスタック・ボイラープレートです。
開発効率を最大化するための Docker 環境、静的解析ツール、そして AI (Gemini) との高度な連携機能を備えています。
```

- [ ] **ステップ 2: コミット**

```bash
git add README.md
git commit -m "docs: READMEのリセットとヘッダー/バッジの追加"
```

---

### タスク 2: 技術スタックとクイックスタートセクションの構築

**ファイル:**
- 変更: `README.md`

- [ ] **ステップ 1: 技術スタックセクションを追加する**

```markdown
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
```

- [ ] **ステップ 2: クイックスタートセクションを追加する**

```markdown
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
```

- [ ] **ステップ 3: コミット**

```bash
git add README.md
git commit -m "docs: READMEに技術スタックとクイックスタートを追加"
```

---

### タスク 3: 開発コマンドと AI 連携セクションの構築

**ファイル:**
- 変更: `README.md`

- [ ] **ステップ 1: 開発コマンド（Makefile）の一覧表を追加する**

```markdown
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
```

- [ ] **ステップ 2: AI (Gemini/Boost) 連携セクションを追加する**

```markdown
---

## 🤖 AI (Gemini/Boost) との連携

このプロジェクトは **Laravel Boost** を導入しており、Gemini 等の AI エージェントがコードベースを深く理解できるように最適化されています。

- **AI コンテキストサーバー:** `make php-boost-mcp` で MCP サーバーを起動できます。
- **ガイドラインの更新:** `make php-boost-update` で最新のコーディング規約を同期します。
- **指示書:** 詳細な規律は `GEMINI.md` に記載されています。

---

## 📄 ライセンス

このプロジェクトは [MIT License](LICENSE) の下で公開されています。
```

- [ ] **ステップ 3: コミット**

```bash
git add README.md
git commit -m "docs: READMEに開発コマンドとAI連携セクションを追加"
```

---

### タスク 4: 最終検証とコードレビュー

**ファイル:**
- 検証: `README.md`, `Makefile`, `compose.yaml`

- [ ] **ステップ 1: 物理的検証**
`README.md` に記載した Makefile の全コマンドが実際に `Makefile` に存在し、文法的に正しいことを確認する。

実行: `grep "^[a-z-]*:" Makefile`

- [ ] **ステップ 2: `code-reviewer` による自動レビュー**
`invoke_agent(agent_name="code-reviewer", ...)` を使用し、リニューアルした `README.md` がデザインドキュメントの要件を満たし、正確であることを検証する。

- [ ] **ステップ 3: ユーザーによるコードレビュー**
「README.md のリニューアルが完了しました。code-reviewer による検証も済んでおります。内容の最終確認をお願いします。」と伝え、承認を得る。

- [ ] **ステップ 4: 最終コミット**

```bash
git status
```
