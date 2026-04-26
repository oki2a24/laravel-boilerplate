# Laravel Boost 導入および AI 開発環境の最適化計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、`executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** Laravel Boost を Docker 環境に導入し、Laravel 12 に最適化された AI 開発環境を構築する。また、プロジェクト全体のドキュメントの整合性を確保する。

**アーキテクチャと意思決定:**
1.  **更新の二重化**:
    - **自動**: `composer.json` の `post-update-cmd` に `boost:update` を追加し、パッケージ更新時に AI ガイドラインも自動的に最新化されるようにする。
    - **手動**: `Makefile` にショートカットを提供し、開発者が任意のタイミングで同期や MCP サーバーの起動を行えるようにする。
2.  **ドキュメントの同期**:
    - `GEMINI.md` を含む既存ドキュメントが「Laravel 9」のままになっている箇所を全て「Laravel 12」に修正し、現在のプロジェクト実態と一致させる。

**技術スタック:** Laravel 12, Docker Compose, Laravel Boost, GNU Make

---

### タスク 1: 依存関係の追加と初期化

**ファイル:**
- 変更: `laravel/composer.json`
- 作成: `.github/laravel-boost/` (自動生成)

- [ ] **ステップ 1: `laravel/boost` のインストール**
    - 実行: `docker compose exec --user=app app composer require laravel/boost --dev`
    - 期待値: パッケージが `require-dev` に追加されること。

- [ ] **ステップ 2: 初期設定の実行**
    - 実行: `docker compose exec --user=app app php artisan boost:install`
    - ※ AI エージェントの選択肢は Cursor/Claude Code 等、汎用的なものを選択する。
    - 期待値: `.github/laravel-boost/guidelines/` 等が生成されること。

- [ ] **ステップ 3: 基本スキルの追加**
    - 実行: `docker compose exec --user=app app php artisan boost:add-skill laravel-best-practices`
    - 期待値: ベストプラクティス用のガイドラインファイルが生成されること。

- [ ] **ステップ 4: コミット**
    - `git add laravel/composer.json laravel/composer.lock .github/laravel-boost/`
    - `git commit -m "feat: install laravel boost and initialize guidelines"`

---

### タスク 2: コマンドの統合 (Composer & Makefile)

**ファイル:**
- 変更: `laravel/composer.json`
- 変更: `Makefile`

- [ ] **ステップ 1: Composer 自動更新設定の追加**
    - `laravel/composer.json` の `scripts.post-update-cmd` 配列に `@php artisan boost:update --ansi` を追加する。

- [ ] **ステップ 2: Makefile への運用コマンド追加**
    - 以下のコマンドを追加し、ホスト側からの操作性を確保する。
    ```makefile
    .PHONY: php-boost-update
    php-boost-update: ## Update Laravel Boost resources (Guidelines/Skills)
    	docker compose exec --user=app app php artisan boost:update

    .PHONY: php-boost-add-skill
    php-boost-add-skill: ## Add/Update a boost skill (usage: make php-boost-add-skill skill=...)
    	docker compose exec --user=app app php artisan boost:add-skill $(skill)

    .PHONY: php-boost-mcp
    php-boost-mcp: ## Run boost:mcp (AI context server)
    	docker compose exec --user=app app php artisan boost:mcp
    ```

- [ ] **ステップ 3: コミット**
    - `git add laravel/composer.json Makefile`
    - `git commit -m "chore: integrate boost update commands into composer and makefile"`

---

### タスク 3: ドキュメントの刷新と整合性確保

**ファイル:**
- 変更: `GEMINI.md`
- 変更: `README.md`
- 変更: `docs/development-guidelines.md`

- [ ] **ステップ 1: GEMINI.md の全面修正**
    - 「Laravel 9」の記述を「Laravel 12」に更新。
    - 「Added Memories」にある Laravel 11 へのアップグレード履歴を「過去の経緯」として整理し、現在の 12 基準の運用（Boost 含む）を追記。
    - `Makefile` セクションに新設した Boost コマンドを追記。

- [ ] **ステップ 2: README.md および開発ガイドの更新**
    - AI 開発者向けに、`.github/laravel-boost/guidelines` を遵守すべき規律として参照するよう追記。

- [ ] **ステップ 3: コミット**
    - `git add GEMINI.md README.md docs/development-guidelines.md`
    - `git commit -m "docs: update project documentation to reflect Laravel 12 and Boost integration"`

---

### タスク 4: 最終検証

- [ ] **ステップ 1: Boost 更新コマンドの動作確認**
    - 実行: `make php-boost-update`
    - 期待値: 正常終了し、ガイドラインが最新化されること。

- [ ] **ステップ 2: MCP サーバーの疎通テスト**
    - 実行: `make php-boost-mcp`
    - 期待値: サーバーが起動し、JSON-RPC 待機状態になること（Ctrl+C で終了）。

- [ ] **ステップ 3: 全体整合性の最終確認**
    - `git status` および各ドキュメントの目視確認。
