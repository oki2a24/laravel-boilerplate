# laravel9-boilerplate
Laravel 9 を REATful API リソースサーバーとしたアプリの雛形です。

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

   プロジェクトルートに、Docker Compose用の`.env`ファイルを作成します。

   ```bash
   cp .env.example .env
   ```

   次に、Laravelアプリケーション用の`.env`ファイルを作成します。

   ```bash
   cp laravel/.env.example laravel/.env
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
    - 参考: 
        - https://github.com/vitejs/awesome-vite#vue-3
        - https://github.com/oki2a24/laravel8-boilerplate/tree/main/laravel
- [x] PHP CS Fixer
- [x] Vue Router
- [x] VS Code Dev Container
- [x] laravel/pint へ移行
- [x] Larastan
- [x] Supervisor の設定を見直す
- [x] ~~root でないユーザーで Supervisor を動かす~~ → crond は root で動かすことが前提と考えるとこれは不要かもしれない
- [x] app 用の cron
- [x] Health エンドポイント
- [x] Makefile を導入する
- [x] https://github.com/barryvdh/laravel-ide-helper を導入する
- [x] Bootstrap5 を導入する
- [ ] Xdebug ステップデバッギングを可能にする
- [ ] VS Code 連携を強化する
  - [ ] Dev Container 設定ファイルのフォーマット変更へ対応する
  - [ ] Prettier と連携する
  - [ ] ESLint と連携する
- [ ] Laravel 10 へアップグレードする
- [ ] MSW を導入する https://mswjs.io/docs/getting-started/install
- [ ] Storybook を導入する
- [ ] メニューとして https://getbootstrap.jp/docs/5.0/getting-started/introduction/ 等の上と左メニューを再現する
