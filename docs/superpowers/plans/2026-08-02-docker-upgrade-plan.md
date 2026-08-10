# Docker環境アップグレード 実装プラン (2026-08-02)

> **エージェント作業用:** REQUIRED SUB-SKILL: `superpowers:subagent-driven-development` (recommended) または `superpowers:executing-plans` を使用して、このプランの各タスクを順次実行してください。タスクはチェックボックス (`- [ ]`) 形式で管理します。

**目標:** Docker環境コンポーネント（PHP, Node.js, system libraries）を最新の安定バージョンへアップグレードし、動作確認済みの正確なバージョンで環境を固定する。

**アーキテクチャ (逆引きピン留め戦略):**
1.  **制約の解放 (Release Constraints):** `Dockerfile` からパッケージ（Node.js, system libraries等）のバージョン指定を完全に削除し、ビルド時にその時点での最新版が取得されるようにする。
2.  **検証 (Verification):** アプリケーションと品質チェックが正常に動作することを確認する。
3.  **インスペクション & 固定 (Inspect & Pin):** 実際にコンテナ内で動いている正確なバージョンを調査し、以下のポリシーに基づき `Dockerfile` を書き換える：
    *   **Dockerベースイメージ (PHP):** **パッチバージョンまで厳密に固定**する（例: `php:8.4.21-apache`）。
    *   **イメージ内パッケージ (Node.js, system libraries等):** **メジャー・ワイルドカード形式で固定**する（例: `nodejs=24.*`, `libicu-dev=76.*`）ことで、パッチレベルの更新は許容しつつ安定性を確保する。

---

### Task 1: 最新バージョンの調査 (Completed)
- [x] **Step 1: 現在のバージョン制約を特定**
- [x] **Step 2: ポリシーに適合する最新版（PHPパッチ, Node.jsメジャー）を調査**
- [x] **Step 3: 調査結果のまとめ**

### Task 2: 制約の解放とビルド (Release & Build)
*※バージョン指定を完全に削除することで、イメージ内で最新のものが取得されるようにする。*

**Files:**
- Modify: `docker/php/Dockerfile`

**Interfaces:**
- Consumes: Task 1 の調査結果
- Produces: 制約が解除された `Dockerfile` とビルド済みコンテナ

- [ ] **Step 1: PHPベースイメージを最新のパッチバージョンへ厳密に固定する (例: `8.4.21-apache`)**
- [ ] **Step 2: Node.js およびシステムライブラリのバージョン指定（`=...` または `.*`）を完全に削除する**
- [ ] **Step 3: コンテナをビルドし、起動を確認する**

### Task 3: 検証 (Verification)
*※「最も新しい（メジャーバージョン内最新）」構成でアプリケーションが正しく動くかを検証する。*

**Files:**
- Execute: `docker compose build`
- Execute: `make php-check-all`
- Execute: `make npm-lint-format`
- Execute: `make npm-dev`

**Interfaces:**
- Consumes: Task 2 で作成したコンテナ環境
- Produces: すべての Makefile コマンドが正常に終了すること

- [ ] **Step 1: PHP品質・テストスイートを実行する (`make php-check-all`)**
- [ ] **Step 2: フロントエンドのLint/開発ツールを実行する (`make npm-lint-format`, `make npm-dev`)**

### Task 4: インスペクションとピン留め (Inspect & Pin)
*※「実際に動いたバージョン」を特定し、最終的な構成を決定づける。*

**Files:**
- Modify: `docker/php/Dockerfile`

**Interfaces:**
- Consumes: タスク3で成功したコンテナの実行結果
- Produces: 最終的なバージョンの指定が書き込まれた `Dockerfile`

- [ ] **Step 1: コンテナ内部を調査し、実際にインストールされているバージョンを取得する**
    - PHP (`php -v`), Node.js (`node -v`), システムライブラリ (`dpkg -l | grep [パッケージ名]`)
- [ ] **Step 2: 取得したバージョンに基づき `Dockerfile` を最終更新する**
    - PHP $\rightarrow$ **パッチバージョンまで厳密に固定** (例: `8.4.21-apache`)
    - Node.js / ライブラリ $\rightarrow$ **メジャー・ワイルドカード形式で固定** (例: `nodejs=24.*`, `76.*`)

### Task 5: 最終確認 (Final Verification)
*※ピン留めされた構成が、意図通りに動作することを確認する。*

- [ ] **Step 1: 最終的な `Dockerfile` でイメージをビルドする (`docker compose build`)**
- [ ] **Step 2: 品質チェックおよびテストを実行し、すべてパスすることを確認する**
    - `make php-check-all`, `make npm-lint-format`, `make npm-dev`

### Task 6: 自己修復 (Conditional)
- タスク3または4においてエラーが発生した場合, `docs/policies/upgrade-policy.md` に基づき自律的な修正ループを実行する。