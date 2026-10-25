---
name: dependency-update
description: "PHP (composer) または Node (npm) の依存パッケージを更新する際に使用。Use when PHP (composer) or Node (npm) package dependencies need updating for security, features, or compatibility."
license: MIT
metadata:
  author: oki2a24
---

# 依存パッケージ更新 (dependency-update)

`composer update` または `npm update` の実行をガイドするスキル。
作成動機：依存パッケージの更新を手順なしに行うリスク（テスト忘れ、非推奨警告の放置、コミットの一貫性喪失）を防ぐ。

## いつ使用するか
- `composer.json` / `package.json` の依存に新しいパッチ/マイナー版があるとき
- セキュリティパッチを反映する必要があるとき
- ベースイメージのアップデートに伴い依存を調整するとき

## コアパターン
1. **依存確認**: `composer show --direct` / `npm list --depth=0` で現在版を確認。
2. **更新実行**: コンテナ内で `composer update` / `npm update` を実行。
3. **再ビルド**: `docker compose build --no-cache`（必要に応じて）。
4. **インスペクト**: `apt-cache policy` / `composer show` で実際のパッケージ版を確認。
5. **検証**: `make php-test` でテストを通過させる。
6. **コミット**: Conventional Commits + 日本語 Body（なぜそうしたか）。

## クイックリファレンス
| 対象 | コマンド | ピン方針 |
|---|---|---|
| PHP 依存 | `composer update` | `composer.json` の制約に従う |
| Node 依存 | `npm update` | `package.json` の `^` に従う |
| 確認 | `composer show --direct` | － |

## よくある間違い
- `composer update` の後に `make php-test` を省略する（非推奨警告を見逃す）。
- `npm update` 後に `npm run build` やテストを忘れる。
- 更新理由をコミット本文に記載せず、なぜそうしたかを残さない。
