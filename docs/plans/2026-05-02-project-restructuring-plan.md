# プロジェクト構成再構築 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、`executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** Laravelをプロジェクトルートに移動し、Docker構成を最適化してLaravel Boostをネイティブに動作させる。

**アーキテクチャ:** 案A（Modern Standard）。Laravel標準の `.env` をルートで使い、Docker専用変数を `.env.docker` に分離する。

**技術スタック:** Docker Compose (V2), Laravel 12, Makefile

---

### タスク 1: 事前検証（ベースライン確認）

**ファイル:**
- なし（コマンド実行のみ）

- [ ] **ステップ 1: 現状の全テスト・チェック実行**
    実行: `make php-check-all`
    期待値: すべて PASS することを確認。
- [ ] **ステップ 2: コンテナ停止**
    実行: `make stop`

### タスク 2: Docker構成ファイルの先行修正

**ファイル:**
- 新規: `.env.docker` (ルートの `.env` をコピー)
- 変更: `docker-compose.yml` -> `compose.yaml` (リネームおよび内容修正)
- 変更: `docker/php/Dockerfile` (ApacheのDocumentRoot修正)
- 変更: `docker/php/apache2/001-my.conf` (DocumentRoot修正)
- 変更: `docker/php/apache2/my-ssl.conf` (DocumentRoot修正)

- [ ] **ステップ 1: `.env.docker` の作成**
    実行: `cp .env .env.docker`
- [ ] **ステップ 2: `docker-compose.yml` を `compose.yaml` にリネーム**
    実行: `mv docker-compose.yml compose.yaml`
- [ ] **ステップ 3: `compose.yaml` の修正**
    - `env_file: [.env.docker, laravel/.env]` を指定（移行中はまだ `laravel/.env` を参照）。
    - `volumes` から `laravel/` プレフィックスを削除。
    - `working_dir` を `/var/www/html` に修正。
- [ ] **ステップ 4: `Dockerfile` および Apache 設定のパス修正**
    - `laravel/public` -> `public` に置換。

### タスク 3: Makefile の修正

**ファイル:**
- 変更: `Makefile`

- [ ] **ステップ 1: Makefile 内のパス修正**
    - 各コマンドの `docker compose exec` 以降のパスから `laravel/` を削除。
    - `boost-setup-links` (ワークアラウンド用ターゲット) の削除。

### タスク 4: 物理移動とクリーンアップ

**ファイル:**
- 移動: `laravel/*` -> `./`
- 削除: `laravel/` ディレクトリ
- 削除: 既存のワークアラウンド（`.agents` リンク, `boost.json` リンク, `BOOST.md` リンク）

- [ ] **ステップ 1: ワークアラウンド用のリンクを削除**
    実行: `rm .agents boost.json BOOST.md`
- [ ] **ステップ 2: Laravelファイルをルートに移動**
    実行: `mv laravel/.* ./ 2>/dev/null; mv laravel/* ./` (隠しファイル含む)
- [ ] **ステップ 3: 不要なディレクトリの削除**
    実行: `rmdir laravel`
- [ ] **ステップ 4: `.env` の整理**
    - `mv .env .env.old` (もともとルートにあったDocker用を退避)
    - `mv .env.laravel .env` (laravelディレクトリから来たものを正規の名前に)
    ※ 実際のファイル名を確認して正確に行う。

### タスク 5: 環境再構築と最終検証

**ファイル:**
- 変更: `compose.yaml` (最終的な env_file 指定)

- [ ] **ステップ 1: `compose.yaml` の `env_file` を修正**
    - `env_file: [.env.docker, .env]` に修正。
- [ ] **ステップ 2: コンテナのリビルドと起動**
    実行: `make up --build`
- [ ] **ステップ 3: 動作確認**
    実行: `make php-check-all`
    期待値: すべて PASS。
- [ ] **ステップ 4: Boost 起動確認**
    実行: `php artisan boost:mcp` (または `make php-boost-mcp`)
    期待値: 正常に起動し、ルートのファイルを認識すること。
- [ ] **ステップ 5: コミット**
    実行: `git add . && git commit -m "refactor: restructure project to move laravel to root and optimize docker config"`

---
作成日: 2026-05-02
ステータス: 提案中 (Review Required)
