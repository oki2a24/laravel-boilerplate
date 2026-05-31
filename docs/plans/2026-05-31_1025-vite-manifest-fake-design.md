# 設計: Vite マニフェストの偽装実装

## 目的
テスト環境において `npm run build` を実行することなく、`Illuminate\Foundation\ViteManifestNotFoundException` を解消する。

## 現在のコンテキスト
- アプリケーションは Blade テンプレート内で `@vite` ディレクティブを使用している。
- コンパイル済みの `public/build/manifest.json` が存在しない場合、Laravel はテスト中に 500 エラーをスローする。
- `tests/TestCase.php` は、すべての機能テストおよびユニットテストのベースクラスである。

## 提案されたアプローチ: 提案 1 (Minimal Fake - 最小構成の偽装)

テストのライフサイクル内で、「ジャストインタイム」にダミーのマニフェストファイルを作成する。

### 実装の詳細
**ファイル: `tests/TestCase.php`**

1.  **`setUp()` メソッドの変更**:
    - 対象パスの特定: `public/build/manifest.json`。
    - `mkdir($dir, 0777, true)` を使用して、`public/build` ディレクトリの存在を確認・作成する。
    - **ファイルが存在しない場合のみ**、`file_put_contents` を使用して、空の `manifest.json` ファイルを作成する。これにより、既存のビルド成果物を破壊しないようにする。
2.  **`tearDown()` メソッドの変更**:
    - テスト完了後に、テスト用に作成したダミーの `manifest.json` ファイルを削除する。これにより、開発者のローカルな `public/build` ディレクトリが「偽の」状態によって汚染されるのを防ぐ。

### ステップバイステップの計画
1.  **`tests/TestCase.php` の修正**: `setUp` にディレクトリ作成およびファイル作成ロジックを追加する。
2.  **クリーンアップ処理の追加**: ファイル削除ロジックを `tearDown` に追加する。
3.  **検証**: 
    - `php artisan test` を実行する。
    - `ViteManifestNotFoundException` エラーが解消されたことを確認する。
    - テスト実行後にダミーファイルが正しく削除されていることを確認する。

## 変更するファイル
- `tests/TestCase.php`

## 検証と成功基準
- **成功**: `php artisan test` が 17 件の全テストとともにパスすること。
- **検証**: テスト終了後、`public/build/manifest.json` ファイルが存在しないこと。

## リスクとフォールバック
- **リスク**: アプリケーションのコード（またはパッケージ）が、JSON 内の特定のキー（例: `assets/app.css`）を読み取ろうとした場合、空のファイルでは `JsonException` 等が発生する可能性がある。
- **フォールバック**: 提案 1 が失敗した場合は、**「提案 2 (Structured Fake - 構造化された偽装)」**を採用し、最小限必要なキー（`app.css` と `app.js`）を JSON に含める実装に切り替える。
