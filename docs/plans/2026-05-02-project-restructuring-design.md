# プロジェクト構成再構築デザイン：Laravelルート移動とBoost最適化

## 1. 背景と目的
現在、Laravelソースコードが `laravel/` サブディレクトリに隔離されています。シンボリックリンクや `GEMINI.md` の参照指示といったワークアラウンドにより、現状でも Laravel Boost 等のツールはある程度機能していますが、これらはあくまで「苦肉の策」であり、構成上の歪みを生んでいます。
本プロジェクトでは、最新のDocker Compose機能を活用することで、これらのワークアラウンドを撤廃し、Laravel本来の「ルート配置」による自然な構成と、AIツールの完全なネイティブ動作を両立させることを目的とします。

## 2. 解決策：案A (Modern Standard)
Laravelをプロジェクトルートに展開し、Docker側の設定を分離・最適化します。

### 2.1 環境変数の管理方針
ご指摘通り、既にファイルが分離されている現状を活かします。
- **`.env` (現 `laravel/.env`)**: Laravelアプリケーション専用。ArtisanやBoostが直接参照。
- **`.env.docker` (現 `.env` をリネーム)**: Docker Compose専用。ホスト側のポート番号やコンテナ共通設定を保持。

### 2.2 ディレクトリ構造（移行後）
```text
/Users/oki2a24/laravel-boilerplate/
├── .env                  # Laravel標準 (旧 laravel/.env)
├── .env.docker           # Docker用 (旧 .env)
├── compose.yaml          # 旧 docker-compose.yml
├── artisan               # ルートに移動
├── app/                  # ルートに移動
├── config/               # ルートに移動
├── ... (その他のLaravelファイル)
├── docker/               # Dockerfile等のインフラ資産のみを格納
└── docs/plans/           # デザイン・実装ドキュメント
```

## 3. 主な変更点とリスク対策

### 3.1 Dockerパスの修正
- **Volumes**: `./:/var/www/html` に統一（内部で `laravel/` を参照している箇所を修正）。
- **Working Directory**: `/var/www/html` に変更（現在は `/var/www/html/laravel`）。
- **Apache DocumentRoot**: `/var/www/html/public` に変更（現在は `/var/www/html/laravel/public`）。

### 3.2 コマンド実行環境の維持
- Docker環境であることは継続するため、`php artisan` 等のコマンドは引き続きコンテナ経由で実行します。
- `Makefile` 内の各ターゲットを修正し、パスの整合性を保ちつつ `docker compose exec` を通じたスムーズな運用を維持します。

### 3.3 Laravel Boost の最適化
- ルートに `artisan` と `.agents/` が配置されるため、シンボリックリンクによる「苦肉の策」を撤廃します。

## 4. 成功基準 (DoD)
1.  `make up` でコンテナが正常に起動し、ブラウザでアプリケーションにアクセスできる。
2.  `Makefile` または `docker compose exec` を通じて、プロジェクトルートから `artisan` コマンドが期待通り動作する。
3.  **Laravel Boost (MCP)** がワークアラウンド（シンボリックリンク等）なしにルートで正常に起動し、プロジェクトの全ファイルを認識できる。
4.  `.env` (Laravel) と `.env.docker` (Docker) の役割が完全に分離されている。
5.  **すべての自動テストが成功する**: `make php-check-all` を実行し、静的解析・フォーマット・テストがすべてパスすること。

---
作成日: 2026-05-02
ステータス: 提案中 (Review Required)
