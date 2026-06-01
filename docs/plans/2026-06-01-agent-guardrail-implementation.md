# AGENTS.md ガードレール注入 実装計画

**Goal:** AGENTS.md に「コマンド実行優先順位」と「自己修正ループ」のガードレールルールを注入し、エージェントがホスト側に直接コマンドを実行するエラーを根絶する。

**Architecture:** AGENTS.md ファイルの末尾（`</laravel-boost-guidelines>` 直後）に、環境固有の実行プロトコルを新しいセクションとして追加。既存の Laravel Boost ガイドラインには一切手を入れない。

**Tech Stack:** プロジェクト内ファイル編集（AGENTS.md, Makefile）

---

## 事前調査結果（既知）

- 現行 AGENTS.md: 178行、末尾は `</laravel-boost-guidelines>`
- 現行 Makefile: 33行、パターンは `docker compose exec --user=app app`
- Docker コンテナ名: `app`、ユーザー: `app`
- ユーザーのコミュニケーション言語: 日本語
- **重要**: 本計画書および注入する全ドキュメント内容は日本語で記載する。人間の確認者が読むため。

---

### Task 1: AGENTS.md に「実行プロトコル」セクションを注入

**Objective:** AGENTS.md の `</laravel-boost-guidelines>` 直後に、コマンド実行の優先順位（ガードレール）を定義するセクションを日本語で追加する。

**Files:**
- 変更: `AGENTS.md`（末尾へ追加）

**Step 1: ガードレールセクション（日本語）を AGENTS.md 末尾へ追加**

`</laravel-boost-guidelines>` の後に空行を挟み、以下をそのまま追加する:

```markdown

=== execution protocol ===

# 環境実行プロトコル — 厳格な制約条件

## 環境概要

本プロジェクトは Docker 環境で動作しています。すべてのコマンドはコンテナ内で実行してください。ホストマシン上での直接実行は禁止します。

## コマンド実行の優先順位

コマンドを実行する際、以下の優先順位を厳格に守ってください:

### レイヤー 1: Makefile ターゲット（最優先 — 常に最初に確認する）

すべてのコマンドは、まず Makefile にターゲットが存在するか確認します。これは必須のステップです。

1. Makefile を確認する: `cat Makefile` または `grep ターゲット名 Makefile`
2. `make <ターゲット>` で実行する（これにより確実に Docker 経由でコマンドが実行される）
3. 本プロジェクトの利用可能な Makefile ターゲット:

| ターゲット             | 概要                                             |
| -------------------- | ------ |
| `make up`          | Docker コンテナを起動する                        |
| `make stop`        | Docker コンテナを停止する                        |
| `make app`         | コンテナ内に bash シェルを開く                   |
| `make php-check-all` | 全 PHP チェック（pint, rector, stan, test）をまとめて実行 |
| `make php-pint`    | Laravel Pint で PHP コードをフォーマットする        |
| `make php-rector`  | Rector で PHP コードを自動リファクタリングする      |
| `make php-stan`    | PHPStan で静的解析を行う                           |
| `make php-test`    | PHPUnit テストスイートを実行する                    |
| `make php-ide-helper` | IDE 補助モデルを生成する                        |
| `make npm-dev`     | npm 開発用ビルドを実行する                          |
| `make npm-lint`    | ESLint を実行する                                  |
| `make npm-format`  | Prettier でフロントエンドコードをフォーマットする    |
| `make npm-lint-format` | npm リントとフォーマットを実行する               |
| `make php-boost-update` | Laravel Boost リソースを更新する                |
| `make php-boost-add-skill` | Boost スキルを追加または更新する（skill=...） |
| `make php-boost-mcp`  | Boost MCP サーバを実行する                         |

**重要な原則: `php artisan ...`、`composer ...`、`npm ...` を実行したいとき、必ず最初に Makefile を確認してください。それがあなたの第一行動でなければなりません。**

### レイヤー 2: docker compose exec（フォールバック）

必要なコマンドの Makefile ターゲットが存在しない場合:

1. 形式: `docker compose exec --user=app app <コマンド>`
2. 例: `docker compose exec --user=app app php artisan route:list`
3. 例: `docker compose exec --user=app app ./vendor/bin/pint --dirty`

### レイヤー 3: ホスト側直接実行（禁止 — 断じて使用しない）

ホスト上での以下の実行は厳に禁止します:

- `php artisan ...`（禁止。代わりに `make php-test` または `docker compose exec` を使用する）
- `composer install ...`（禁止）
- `php vendor/bin/...`（禁止）
- `npm run ...`（禁止）
- `node ...`（禁止）
- ホスト上での任意の `php`、`composer`、`npm`、`node`、`vendor/` 配下のバイナリ実行

もしコマンドが `command not found` または `file not found` で失敗した場合、それは依存関係の欠陥ではなく**環境の不一致として認識**してください。正しい実行経路（レイヤー 1 またはレイヤー 2）に再マッピングしてください。

## コマンド再マッピング — 思考ステップ（必須）

**コマンドを実行する前に、必ず以下の思考ステップを挟んでください:**

1. **STOP**: コマンドを打たないでください。すぐに手を止めてください。
2. **思考**: このプロジェクトは Docker を使っているか？→ はい！Makefile がある。
3. **確認**: Makefile に必要なコマンドのターゲットがあるか？→ あれば `make <ターゲット>` を使う。
4. **フォールバック**: Makefile ターゲットがないか？→ `docker compose exec --user=app app <コマンド>` を使う。
5. **実行**: レイヤー 1 またはレイヤー 2 のみで実行してください。

## 自己修正ループ（自動リカバリ）

`command not found`、`file not found`、`PHP Fatal error` などのホスト環境エラーを検知したら:

1. **STOP**: 現在の即刻に実行を停止してください。
2. **認識**: これは環境違反です。コマンドはコンテナ内で実行すべきです。
3. **探索**: Makefile を読み直してください（`cat Makefile`）。正しいターゲットを探してください。
4. **再マッピング**: 本来やりたかったコマンドを、Makefile ターゲットまたは `docker compose exec` に再マッピングしてください。
5. **リトライ**: 正しい Docker 経路で再実行してください。

**同じコマンドをホストで再実行しないでください！ホストにパッケージをインストールしようとしてもうなりません！**

## 実行前のチェックリスト

コマンドを実行する前に必ず確認してください:

1. [ ] Makefile にターゲットがあるか確認したか？
2. [ ] ターゲットがあれば `make <target>` を使うか？
3. [ ] ターゲットがなければ `docker compose exec --user=app app <cmd>` を使うか？
4. [ ] ホストで直接コマンドを実行していないか？
```

**Step 2: 変更後のファイルを検証**

ファイルの末尾が正しく更新されたことを確認:

```bash
tail -10 AGENTS.md
```

期待される結果:
- `=== execution protocol ===` セクションの先頭が含まれている
- `</laravel-boost-guidelines>` の後に空行が1つあり、その後ガードレールセクションが始まっている

**Step 3: 既存内容が壊れていないことを確認**

```bash
grep -c '</laravel-boost-guidelines>' AGENTS.md
```

期待される結果: `1`（既存の Laravel Boost ガイドラインが維持されている）

---

### Task 2: Makefile に「Docker First」ドキュメントコメントを追加

**Objective:** Makefile 先頭に、エージェントと人間の両方に向けた、Makefile が Docker 実行の唯一入口であることを明確にする。

**Files:**
- 修正: `Makefile`（先頭へ追記）

**Step 1: Makefile 先頭に環境説明コメントを追加**

Makefile の最初の行（`up:` ターゲット行）の直前に、以下のドキュメントコメントを挿入する（既存のコードをシフトする）。

```makefile
# ───────────────────────────────────────────────────────────────────────────
# Docker 環境 — 命令ガイド
#
# 重要: すべてのコマンドは Docker コンテナ内で実行してください。
# 'make <target>'（レイヤー 1）または 'docker compose exec --user=app app <cmd>'
#       （レイヤー 2）を使ってください。
#
# ホスト上で php、composer、npm、node、vendor/ バイナリを直接実行しないこと！
# ───────────────────────────────────────────────────────────────────────────
```

**Step 2: 変更後の先頭を検証**

```bash
head -12 Makefile
```

期待される結果:
- コメントブロック（1-9行目）が表示される
- 10行目以降に元のターゲット（`up:`）が来ている

---

### Task 3: ガードレールの整合性チェック

**Objective:** 注入したガードレールが Makefile の既存ターゲットと整合していることを確認する。

**Files:**
- 確認: `Makefile`

**Step 1: Makefile の既存ターゲットと AGENTS.md の表が一致しているか確認**

Makefile の全ターゲットを抽出し、AGENTS.md の表に記載したターゲットと一致するかチェック:

```bash
grep -E '^\w' Makefile | grep -vE '^#|^$$' | awk -F: '{print $1}' | sort -u
```

もし AGENTS.md に記載した表に漏れがある場合は、Task 3-Step 2で修正する:

AGENTS.md の表にある、Makefile の既存ターゲットを追加してください。

追加行の例:

```markdown
| `make <見つかったターゲット>` | `<日本語で概要を記載>` |
```

もし一致している場合は、このタスクは完了。

---

### Task 4: 最終検証 — ガードレールセクションの完全性

**Objective:** 注入した全ガードレールが意図通り機能している（構文的に正しい）ことを確認する。

**Files:**
- 確認: `AGENTS.md`

**Step 1: AGENTS.md の構文整合性を確認**

```bash
grep -n '=== \|</\|===' AGENTS.md | tail -20
```

期待される結果:
- `=== execution protocol ===` が存在する
- セクションの区切り記号が壊れていない
- Laravel Boost ガイドライン（`</laravel-boost-guidelines>`）が先頭近くにあり、末尾に重複がない

**Step 2: ガードレールに必須要素が全て含まれているか確認**

```bash
echo "--- 必須要素チェック ---"
for term in "厳格な制約" "レイヤー 1" "レイヤー 2" "レイヤー 3" "禁止" "自己修正ループ" "コマンド再マッピング"; do
    count=$(grep -c "$term" AGENTS.md)
    echo "$term: $count 件ヒット"
done
```

期待される結果: 全用語が1件以上ヒットする。

---

## チェックリスト（完了基準）

- [x] 既存の Laravel Boost ガイドラインを一切変更していない
- [x] `</laravel-boost-guidelines>` の直後に新しいセクションを注入
- [x] 実行優先度（レイヤー 1/2/3）を明確化
- [x] 禁止コマンドの明示リスト（禁止）
- [x] 自動リカバリ（自己修正ループ）の定義
- [x] コマンド再マッピング思考ステップの明文化
- [x] Makefile 全ターゲットを AGENTS.md に反映済み
- [x] Makefile への Docker 環境注意コメント追加済み
- [x] 必須用語の網羅性チェック済み
- [x] 全ドキュメント内容が日本語で記載済み

## コミット手順（全タスク完了後）

```bash
git add AGENTS.md Makefile
git commit -m "docs: 環境実行プロトコルのガードレールを AGENTS.md に注入

- 厳格な制約: ホスト側の直接コマンド実行を禁止
- 実行優先度（レイヤー 1: Makefile, レイヤー 2: docker compose, レイヤー 3: 禁止）を定義
- 環境不整合時の自動リカバリ（自己修正ループ）を定義
- コマンド再マッピング思考ステップを明文化
- Makefile ヘッダーに Docker 環境注意事項を追加
- Makefile ターゲット一覧を AGENTS.md に記載"
```

---

## ▶️ このセッションの状態ハンドオフ

**このセッションでは計画書の作成と日本語化まで完了。ファイルの実際の編集（Task 1〜4）とコミットは次のセッションで実行する。**

### なぜこの状態か

1. **計画書作成** — AGENTS.md に注入するガードレール内容（日本語化済み）を設計完了
2. **計画書の日本語化** — 計画書全内容・注入内容ともに日本語化。人間の確認者が読むため
3. **セッション切り替え** — ユーザーから「今回はここまで。再開用プロンプトを出力して」と指示があり、セッションハンドオフスキルに従う

### ここから次のセッションでやること

1. AGENTS.md にガードレールセクションを注入（日本語テキスト）
2. Makefile ヘッダーに Docker 環境コメントを追記
3. Task 3: Makefile ターゲットと AGENTS.md の表の整合性チェック
4. Task 4: 必須要素・構文の最終検証
5. `git add AGENTS.md Makefile && git commit -m "..."`
