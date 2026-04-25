# Laravel 12 アップグレード 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、`executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** Laravel 11 から 12 へのアップグレードを完了し、推奨設定を適用する。

**アーキテクチャ:** 依存関係を Laravel 12 に更新し、規約変更に伴う設定ファイルの修正を行う。アプローチ2（推奨設定への追随）に基づき、ストレージパスなどを最新化する。

**技術スタック:** Laravel 12, PHP 8.2+, Composer, Docker

---

### タスク 2.1: アップグレード用ブランチの作成

- [ ] **ステップ 1: 新しいブランチを作成**
    - 実行: `git checkout -b feat/laravel-12-upgrade`
- [ ] **ステップ 2: 確認**
    - 実行: `git branch --show-current`

### タスク 2.2: composer.json の更新

**ファイル:**
- 変更: `laravel/composer.json`

- [ ] **ステップ 1: 依存関係のバージョンを更新**
    - `laravel/framework` を `^12.0` に、`nunomaduro/collision` を `^8.5` に更新する。
- [ ] **ステップ 2: composer update の実行**
    - 実行: `docker compose exec --user=app app composer update`
- [ ] **ステップ 3: 基本動作確認**
    - 実行: `docker compose exec --user=app app php artisan --version`
- [ ] **ステップ 4: コミット**
    - 実行: `git add laravel/composer.json laravel/composer.lock && git commit -m "chore: Laravel 12 への依存関係の更新"`

### タスク 3.1: 設定ファイルの修正（ストレージパス）

**ファイル:**
- 変更: `laravel/config/filesystems.php`

- [ ] **ステップ 1: local ディスクのルートを変更**
    - `root` を `storage_path('app/private')` に変更。
- [ ] **ステップ 2: 動作確認**
    - 実行: `docker compose exec --user=app app php artisan about`
- [ ] **ステップ 3: コミット**
    - 実行: `git add laravel/config/filesystems.php && git commit -m "style: local ストレージのルートを private へ変更 (Laravel 12 推奨)"`

### タスク 3.2: 全テストの実行と検証

- [ ] **ステップ 1: テスト実行**
    - 実行: `make php-test`
- [ ] **ステップ 2: 静的解析実行**
    - 実行: `make php-check-all`
- [ ] **ステップ 3: コミット（もし修正が発生した場合）**
    - 必要に応じて修正とコミットを行う。
