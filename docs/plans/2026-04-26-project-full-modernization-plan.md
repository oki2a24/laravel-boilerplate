# プロジェクト全面最新化（2026年4月版）実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、`executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** インフラ、バックエンド、フロントエンドのすべての依存関係を 2026年4月時点の最新安定版に更新し、動作を検証する。

**アーキテクチャ:** 段階的なアップデート。各領域の更新後に必ずビルドとテストを実行し、品質を担保する。

**技術スタック:** PHP 8.4.20+, Node.js 24.x LTS, Laravel 13, PHPUnit 13, Vite 8, Vue 3.5, Vue Router 5, ESLint 10

---

### タスク 1: インフラ (Docker) の最新化

**ファイル:**
- 変更: `docker/php/Dockerfile`

- [ ] **ステップ 1: PHP イメージの更新**
    - `php:8.4.14-apache` を `php:8.4.20-apache` に変更。
- [ ] **ステップ 2: Node.js インストールスクリプトの更新**
    - `setup_24.x` を使用するように調整（現在は 24.x ですが、確実に最新パッチが当たるよう確認）。
- [ ] **ステップ 3: コンテナの再ビルド**
    - 実行: `docker compose build --no-cache app`
- [ ] **ステップ 4: バージョン確認（検証ゲート）**
    - 実行: `docker compose exec --user=app app php -v` (期待: 8.4.20+)
    - 実行: `docker compose exec --user=app app node -v` (期待: 24.x.x)
- [ ] **ステップ 5: コミット**
    - 実行: `git add docker/php/Dockerfile && git commit -m "chore: Docker 環境の PHP 8.4.20 および Node.js 24 LTS への更新"`

### タスク 2: バックエンド (PHP) の最新化

**ファイル:**
- 変更: `laravel/composer.json`

- [ ] **ステップ 1: パッケージバージョンの更新**
    - `phpunit/phpunit` を `^13.0` に。
    - その他 `spatie/laravel-ignition` などを最新版に検討。
- [ ] **ステップ 2: 依存関係の更新**
    - 実行: `docker compose exec --user=app app composer update -W`
- [ ] **ステップ 3: 全テスト実行（検証ゲート）**
    - 実行: `make php-test`
- [ ] **ステップ 4: 静的解析実行（検証ゲート）**
    - 実行: `make php-stan`
- [ ] **ステップ 5: コミット**
    - 実行: `git add laravel/composer.json laravel/composer.lock && git commit -m "chore: PHPUnit 13 および周辺パッケージの最新化"`

### タスク 3: フロントエンド (JS) の最新化

**ファイル:**
- 変更: `laravel/package.json`

- [ ] **ステップ 1: 主要パッケージをメジャーアップデート**
    - `vite` -> `^8.0.0`
    - `vue-router` -> `^5.0.0`
    - `eslint` -> `^10.0.0`
    - `laravel-vite-plugin` -> `^3.0.0`
- [ ] **ステップ 2: パッケージのインストール**
    - 実行: `docker compose exec --user=app app npm install`
- [ ] **ステップ 3: ビルド検証（検証ゲート）**
    - 実行: `docker compose exec --user=app app npm run build`
- [ ] **ステップ 4: リント検証（検証ゲート）**
    - 実行: `make npm-lint`
- [ ] **ステップ 5: コミット**
    - 実行: `git add laravel/package.json laravel/package-lock.json && git commit -m "chore: Vite 8, Vue Router 5, ESLint 10 へのフロントエンド最新化"`

### タスク 4: 最終統合確認

- [ ] **ステップ 1: 開発サーバーの起動テスト**
    - 実行: `make npm-dev` (数秒起動してエラーが出ないか確認し停止)
- [ ] **ステップ 2: 全品質チェックのパス**
    - 実行: `make php-check-all`
