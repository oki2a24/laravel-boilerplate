# データベーステスト環境の分離 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、移植された `subagent-driven-development` スキル（推奨）または `executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** PostgreSQLコンテナ内で手動確認用DB（`laravel`）とテスト用DB（`laravel_test`）を分離し、開発データ保護と堅牢なテスト実行環境を両立させる。また、そのナレッジを人間用・AI用ドキュメントに記録する。

**アーキテクチャ:** 
- Docker起動時に `docker-entrypoint-initdb.d/` 内のスクリプトでテスト用DBを自動作成する。
- 環境変数 `CREATE_TEST_DB=true` が設定されている場合のみテストDBを作成し、本番環境での不要な作成を防止する（デフォルトは `true`）。
- `phpunit.xml` でテスト接続先を `laravel_test` に固定し、テスト実行時は `RefreshDatabase` で高速にリセットする。

**技術スタック:** Docker Compose, PostgreSQL 16, PHPUnit 13, Laravel 13

---

## 変更対象ファイル構成
- **新規作成:**
  - `docker/postgres/init-db.sh`（テストDB自動作成スクリプト）
- **変更:**
  - `docker/postgres/Dockerfile`（初期化スクリプトの組み込み）
  - [compose.yaml](file:///Users/oki2a24/laravel-boilerplate/compose.yaml)（環境変数の引き渡し）
  - [phpunit.xml](file:///Users/oki2a24/laravel-boilerplate/phpunit.xml)（テストDB接続先の上書き）
  - [README.md](file:///Users/oki2a24/laravel-boilerplate/README.md)（開発者用手順）
  - [GEMINI.md](file:///Users/oki2a24/laravel-boilerplate/GEMINI.md) / [AGENTS.md](file:///Users/oki2a24/laravel-boilerplate/AGENTS.md)（AIエージェント用規律の追加）

---

## 実装タスク一覧

### タスク 1: PostgreSQL初期化スクリプト `init-db.sh` の新規作成

**ファイル:**
- 新規作成: `docker/postgres/init-db.sh`

- [ ] **ステップ 1: スクリプトファイルの作成**
  以下の内容でファイルを作成します。
  
  ```bash
  #!/bin/bash
  set -e

  # CREATE_TEST_DB が true の場合のみテストDBを作成する
  if [ "$CREATE_TEST_DB" = "true" ]; then
      echo "Creating testing database: laravel_test..."
      psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
          CREATE DATABASE laravel_test;
          GRANT ALL PRIVILEGES ON DATABASE laravel_test TO "$POSTGRES_USER";
  EOSQL
      echo "Testing database created successfully."
  else
      echo "Skipping testing database creation (CREATE_TEST_DB=$CREATE_TEST_DB)"
  fi
  ```

- [ ] **ステップ 2: コミット**
  ```bash
  git add docker/postgres/init-db.sh
  git commit -m "feat: テスト用DBを作成する初期化スクリプトを追加"
  ```

---

### タスク 2: `docker/postgres/Dockerfile` の更新

**ファイル:**
- 変更: `docker/postgres/Dockerfile`

- [ ] **ステップ 1: Dockerfile の書き換え**
  `init-db.sh` をイメージの `/docker-entrypoint-initdb.d/` にコピーし、実行権限を付与する命令を追加します。

  ```dockerfile
  FROM postgres:16.10
  COPY init-db.sh /docker-entrypoint-initdb.d/
  RUN chmod +x /docker-entrypoint-initdb.d/init-db.sh
  ```

- [ ] **ステップ 2: コミット**
  ```bash
  git add docker/postgres/Dockerfile
  git commit -m "feat: PostgreSQLのDockerfileに初期化スクリプトのコピーを追加"
  ```

---

### タスク 3: `compose.yaml` の更新

**ファイル:**
- 変更: [compose.yaml](file:///Users/oki2a24/laravel-boilerplate/compose.yaml)

- [ ] **ステップ 1: compose.yaml の書き換え**
  `db` サービスの `environment` 設定に `CREATE_TEST_DB` を追加します。
  
  ```yaml
    db:
      build:
        context: ./docker/postgres
      environment:
        - CREATE_TEST_DB=${CREATE_TEST_DB:-true}
        - LANG=C.UTF-8
        - POSTGRES_PASSWORD=${DB_PASSWORD}
        - POSTGRES_USER=${DB_USERNAME}
        - POSTGRES_DB=${DB_DATABASE}
        - POSTGRES_INITDB_ARGS=--encoding=UTF-8 --locale=C.UTF-8
        - TZ=Asia/Tokyo
  ```

- [ ] **ステップ 2: コミット**
  ```bash
  git add compose.yaml
  git commit -m "feat: compose.yamlでdbコンテナにCREATE_TEST_DBを渡すよう設定"
  ```

---

### タスク 4: `phpunit.xml` の更新

**ファイル:**
- 変更: [phpunit.xml](file:///Users/oki2a24/laravel-boilerplate/phpunit.xml)

- [ ] **ステップ 1: phpunit.xml の書き換え**
  コメントアウトされているSQLite用の設定を削除し、`DB_CONNECTION=pgsql`、`DB_DATABASE=laravel_test` を有効化します。

  ```xml
    <php>
      <env name="APP_ENV" value="testing"/>
      <env name="BCRYPT_ROUNDS" value="4"/>
      <env name="CACHE_DRIVER" value="array"/>
      <env name="DB_CONNECTION" value="pgsql"/>
      <env name="DB_DATABASE" value="laravel_test"/>
      <env name="MAIL_MAILER" value="array"/>
      <env name="QUEUE_CONNECTION" value="sync"/>
      <env name="SESSION_DRIVER" value="array"/>
      <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
  ```

- [ ] **ステップ 2: コミット**
  ```bash
  git add phpunit.xml
  git commit -m "test: PHPUnitのテスト用DBにlaravel_testを使用するよう設定"
  ```

---

### タスク 5: ローカル開発環境の再構築とDB分離の検証

- [ ] **ステップ 1: 既存ボリュームのクリアとコンテナ起動**
  既存のPostgreSQLボリュームを削除して、初期化スクリプトが動作するように再起動・再ビルドします。
  
  実行するコマンド:
  ```bash
  make stop
  docker compose down -v
  make up
  ```

- [ ] **ステップ 2: DB作成の確認**
  データベースの一覧を取得し、`laravel` と `laravel_test` の2つが作成されていることを確認します。
  
  実行するコマンド:
  ```bash
  docker compose exec db psql -U app -d laravel -l
  ```
  期待値: 出力結果のName一覧に `laravel` と `laravel_test` が存在すること。

- [ ] **ステップ 3: テストの実行とデータ保護の確認**
  既存テストが正常にパスし、かつ開発用DBに干渉しないことを確認します。
  
  実行するコマンド:
  ```bash
  make php-test
  ```
  期待値: テストがすべて PASS すること。手動で `laravel` DBに入れたデータ（もしあれば）が消去されないこと。

---

### タスク 6: ドキュメンテーションとAI用ナレッジの更新

**ファイル:**
- 変更: [README.md](file:///Users/oki2a24/laravel-boilerplate/README.md), [GEMINI.md](file:///Users/oki2a24/laravel-boilerplate/GEMINI.md), [AGENTS.md](file:///Users/oki2a24/laravel-boilerplate/AGENTS.md)

- [ ] **ステップ 1: README.md の変更**
  「テスト環境の実行」またはデータベースに関するセクションに、テストDBが独立していること、およびDB初期化の注意点を追記します。
  
  追加する内容:
  ```markdown
  ### テストデータベースの分離について
  本プロジェクトでは、ローカル手動確認データと自動テストデータを分離するため、同一の PostgreSQL 内に以下の2つのDBを作成しています。
  - `laravel`: 手動動作確認用DB
  - `laravel_test`: 自動テスト（PHPUnit）用DB
  
  テストを実行しても手動確認用のデータは削除されません。
  もしテスト用DBが作成されていない場合は、以下のコマンドでボリュームを削除して再ビルドを行ってください。
  ```bash
  docker compose down -v && make up
  ```
  ```

- [ ] **ステップ 2: GEMINI.md / AGENTS.md の変更**
  AIエージェント向けのルール（規律）として、以下の文言を追記します。
  
  追加する内容:
  ```markdown
  - **テスト環境でのデータベース分離**: ローカルテスト実行時は必ず PostgreSQL の `laravel_test` データベースを使用する構成になっており、開発DB `laravel` を破壊しないよう設計されています。環境変数 `CREATE_TEST_DB=true` によって自動で準備されます。テストを高速化する目的であっても、勝手にインメモリ SQLite 等へテスト設定を切り替えてはいけません。
  ```

- [ ] **ステップ 3: コミット**
  ```bash
  git add README.md GEMINI.md AGENTS.md
  git commit -m "docs: 開発者およびAI向けにテストDB分離構成のドキュメントを追加"
  ```
