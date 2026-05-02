# laravel12-boilerplate
Laravel 12 を RESTful API リソースサーバーとしたアプリの雛形です。

## 構成
- ミドルウェア
    - Docker, Docker Compose
- バックエンド
    - Laravel の API
    - PostgreSQL
- フロントエンド
    - Laravel 内に統合された Vite
    - Vue.js

## セットアップ手順

1. **環境変数の設定**

   プロジェクトルートに、Docker Compose用の環境変数ファイルを作成します。

   ```bash
   cp .env.example .env.docker
   ```

   次に、Laravelアプリケーション用の環境変数ファイルを作成します。

   ```bash
   cp .env.example .env
   ```

2. **コンテナの起動**

   ```bash
   make up
   ```

3. **依存関係のインストール**

   ```bash
   docker compose exec --user=app app composer install
   docker compose exec --user=app app npm install
   ```

4. **アプリケーションの初期設定**

   アプリケーションキーを生成します。

   ```bash
   docker compose exec --user=app app php artisan key:generate
   ```

   データベースのテーブルを作成します。

   ```bash
   docker compose exec --user=app app php artisan migrate
   ```

5. **開発サーバーの起動**

   Viteの開発サーバーを起動します。

   ```bash
   make npm-dev
   ```

6. **アプリケーションへのアクセス**

   ブラウザで `http://localhost` または `http://localhost/login` を開きます。


## TODO
- [x] Docker Compose 環境を構築する
    - [ ] apache2 起動時のワーニングを解消する
- [x] Laravel をインストールする
- [x] Laravel で Vite を使えるようにする
- [x] ESLint Prettier
- [x] PHP CS Fixer
- [x] Vue Router
- [x] VS Code Dev Container
- [x] laravel/pint へ移行
- [x] Larastan
- [x] Supervisor の設定を見直す
- [x] app 用の cron
- [x] Health エンドポイント
- [x] Makefile を導入する
- [x] https://github.com/barryvdh/laravel-ide-helper を導入する
- [x] Bootstrap5 を導入する
- [ ] Xdebug ステップデバッギングを可能にする
- [ ] VS Code 連携を強化する
- [ ] Laravel 10 へアップグレードする
- [ ] MSW を導入する
- [ ] Storybook を導入する
- [ ] メニューを再現する
