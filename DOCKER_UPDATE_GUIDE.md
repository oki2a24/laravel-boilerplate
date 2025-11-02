### **【指示書】Docker環境のバージョンアップ手順**

**目的:**
このリポジトリのDocker環境（ベースイメージおよび内包するaptパッケージ）を最新版に更新する。

---

### **最新バージョンの特定方法**

更新先の「最新版」を特定する方法は以下の通りです。

1.  **Dockerイメージ (例: `postgres`)**
    *   **Docker Hub** を確認するのが最も確実です。
    *   Docker Hubの公式ページ（例: `https://hub.docker.com/_/postgres`）にアクセスし、「Tags」タブで使用可能なバージョン一覧を確認します。ここで、プロジェクトの形式に合った最新のタグ（例: `16.3`）を見つけます。

---

### **手順**

1.  **現状の確認:**
    *   `git diff HEAD` を実行し、現在加えられている変更（特にバージョン番号）を確認する。
    *   `docker/php/Dockerfile` と `docker/postgres/Dockerfile` の内容を読み込み、現在のバージョン指定方法を把握する。

2.  **バージョニング方針の適用:**
    以下の2つの方針に従って、各ファイルのバージョン番号を修正する。

    *   **方針1: Dockerイメージ (`FROM`命令)**
        *   **ルール:** `composer`, `php`, `postgres` などのベースイメージは、**既存のバージョン形式（例: `X.Y.Z` や `X.Y`）を維持したまま**、最新のバージョンに更新する。
        *   **例:**
            *   `composer:2.4.4` → `composer:2.8.12`
            *   `postgres:14.5` → `postgres:16.3`
            *   `php:8.2.0-apache` → `php:8.3-apache`

    *   **方針2: `apt` パッケージ**
        *   **ルール:** `docker/php/Dockerfile`内で`apt-get install`を使ってインストールされるライブラリは、すべて**メジャーバージョンで固定 (`X.*`)** する。
        *   **手順:**
            1. `docker/php/Dockerfile` を開き、更新対象の `apt` パッケージのバージョン指定（例: `=X.Y.*`）をすべて削除する。
            2. `docker compose build --no-cache` を実行し、Dockerイメージを再ビルドする。`--no-cache` をつけることで、`apt-get update` が確実に実行され、最新のパッケージリストが取得される。
            3. `docker compose up -d` を実行し、コンテナを起動する。
            4. `docker compose exec app bash` を実行し、`app`コンテナに入る。
            5. コンテナ内で `apt-cache policy <package-name>` を実行し、インストールされたパッケージのバージョンを確認する。
                *   例: `apt-cache policy git`
            6. 確認したバージョンを、`X.*` の形式で `docker/php/Dockerfile` に書き戻す。
                *   例: `git` のバージョンが `1:2.43.0-1` であれば、`git=1:2.*` と記述する。

3.  **修正の実行:**
    *   `replace` ツールを使い、上記の方針に基づいて `docker/php/Dockerfile` の内容を修正する。ファイル全体を一度に書き換える方法を推奨する。

4.  **最終確認:**
    *   再度 `git diff HEAD` を実行し、`apt` パッケージがすべて `X.*` の形式になっていること、およびDockerイメージのバージョンが意図通りに更新されていることを確認する。

5.  **検証:**
    *   `docker compose build` を実行し、イメージが正常にビルドできることを確認する。
    *   `docker compose up -d` を実行し、コンテナが正常に起動することを確認する。

6.  **トラブルシューティング:**
    *   **ビルドエラー:**
        *   **事象:** `apt-get install` で指定したパッケージのバージョンが見つからず、ビルドが失敗する。
        *   **原因:** Dockerのベースイメージ（OS）が更新されたことで、`apt` のリポジトリで提供されるパッケージのバージョンが変更された可能性があります。
        *   **解決策:**
            *   エラーメッセージ（例: `E: Version 'X.Y' for 'package-name' was not found`）を確認する。
            *   Dockerfileの `FROM` で指定されているOSのバージョン（例: `php:8.3-apache` であればDebian Trixie）を特定する。
            *   Debianの公式パッケージ検索サイトで、そのOSバージョンで利用可能なパッケージのバージョンを再調査し、Dockerfileを修正する。
            *   **例:** `libpq-dev` のバージョンを `16.*` から `17.*` に修正。