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
- [ ] Supervisor の設定を見直す
- [ ] root でないユーザーで Supervisor を動かす → crond は root で動かすことが前提と考えるとこれは不要かもしれない
- [ ] Health エンドポイント
