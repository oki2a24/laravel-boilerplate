---
name: docker-image-upgrade
description: "Docker イメージ（ベースイメージ）のバージョンアップを行う前に、必ず docs/DOCKER_UPDATE_GUIDE.md と docs/policies/upgrade-policy.md を読む。セキュリティや互換性のためにイメージ版を更新するときに使用（Docker image version update, security patch, compatibility）。"
license: MIT
metadata:
  author: oki2a24
---

# Docker イメージアップデート (docker-image-upgrade)

Docker イメージのバージョンアップを安全に行うためのスキル。
作成動機：アップデート依頼時に `docs/DOCKER_UPDATE_GUIDE.md` を読まずに Dockerfile を直接編集してしまう失敗（RED ベースライン）を防ぐ。

## いつ使用するか
- Docker ベースイージェントのアップデートを行うとき
- PHP / PostgreSQL / Composer など公式イメージのバージョンを変えるとき
- アップデート後、実際の `apt` パッケージ版（`libicu-dev` 等）を確認しピン留めを調整するとき

## コアパターン
1. **ガイド確認**: `docs/DOCKER_UPDATE_GUIDE.md` + `docs/policies/upgrade-policy.md` を読む。
2. **現バージョン確認**: `docker/php/Dockerfile`, `docker/postgres/Dockerfile` の `FROM` を確認。
3. **対象版決定**: Docker Hub で最新パッチ版を確認（メジャー切替は要レビュー）。
4. **更新**: `FROM` をパッチ版まで固定（`php:8.5.12` のように）。apt パッケージは `X.*` ワイルドカード維持。
5. **ビルド**: `make build` / `docker compose build --no-cache`.
6. **インスペクト**: `apt-cache policy` で実際版確認 → メジャーが変わったら `X.*` ピンを更新。
7. **検証**: `make php-test` 通過後コミット。

## クイックリファレンス
| 対象 | 方針 |
|---|---|
| ベースイメージ (`FROM`) | パッチ版固定 (`php:8.5.12`) |
| apt パッケージ | メジャー版ワイルドカード (`libicu-dev=76.*`) |
| 確認コマンド | `apt-cache policy <pkg>` |
| 検証コマンド | `make php-test` |

## よくある間違い
- ガイド未読で直接 `FROM` を書き換える（→メジャーごとのピン留め方針に反する）。
- apt パッケージをピン固定し過ぎる（パッチ版ピン留めは不要、`X.*` に任せる）。
- アップデート後テストを省略する。
