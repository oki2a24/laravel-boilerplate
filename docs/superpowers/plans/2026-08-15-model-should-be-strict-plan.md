# `Model::shouldBeStrict()` 導入 実装プラン (2026-08-15)

> **エージェント作業用:** REQUIRED SUB-SKILL: `superpowers:subagent-driven-development` (recommended) または `superpowers:executing-plans` を使用して、このプランの各タスクを順次実行してください。タスクはチェックボックス (`- [ ]`) 形式で管理します。

**目標:** Eloquent のサイレントバグ（遅延ロード・属性のサイレント破棄・欠損属性へのアクセス）を、テスト全体と非本番の実行環境で `Model::shouldBeStrict()` により検出可能にする。

**アーキテクチャ:**
- テスト基盤（`tests/TestCase.php`）で `Model::shouldBeStrict()` を有効化し、全テストで3つの保護が働く。
- 実行時（`app/Providers/AppServiceProvider.php`）は `! $this->app->isProduction()` により本番以外で有効化し、本番への影響を排除する。
- 3つの公開ゲッタ（`preventsLazyLoading()` / `preventsSilentlyDiscardingAttributes()` / `preventsAccessingMissingAttributes()`）が `true` であることを検証テストで保証する。

**Tech Stack:** PHP 8.4, Laravel 13, PHPUnit 13, Laravel Pint。

**現状:** ブランチ `feat/model-should-be-strict` 上にはコード（下記 Task1〜3 の対象ファイル）と新規テストが既に存在し、`make php-test` は20件パス済み。本プランはその検証・整形・コミット手順を確定づけるもの。

---

### Task 1: 保護が有効化されていること検証テストを作成

**Files:**
- Create: `tests/Feature/ModelStrictModeTest.php`

- [ ] **Step 1: 検証テストを書く（フレームワークの公開ゲッタが真であること）**

```php
<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ModelStrictModeTest extends TestCase
{
      /**
       * It should prevent lazy loading of relations.
       */
    public function test_lazy_loading_is_prevented(): void
      {
          $this->assertTrue(Model::preventsLazyLoading());
       }

      /**
       * It should prevent silently discarding attributes.
       */
    public function test_silently_discarding_attributes_is_prevented(): void
      {
          $this->assertTrue(Model::preventsSilentlyDiscardingAttributes());
       }

      /**
       * It should prevent accessing missing attributes.
       */
    public function test_accessing_missing_attributes_is_prevented(): void
      {
          $this->assertTrue(Model::preventsAccessingMissingAttributes());
       }
}
```

- [ ] **Step 2: 実行して失敗を確認（`tests/TestCase.php` の `Model::shouldBeStrict()` 未実装時のみ）**

Run: `php artisan test --compact tests/Feature/ModelStrictModeTest.php`
Expected: `Task 2` 完了前は `false` で失敗。`Task 2` 後は3件とも PASS。

---

### Task 2: テスト基盤で strict モードを有効化

**Files:**
- Modify: `tests/TestCase.php`（`setUp()` 内）

- [ ] **Step 1: `setUp()` に呼び出しを追加**

```php
use Illuminate\Database\Eloquent\Model;

// ...
protected function setUp(): void
{
    parent::setUp();

    Model::shouldBeStrict();

     // 既存の manifest 生成処理
}
```

- [ ] **Step 2: テストを実行して全テストが strict 下でも通過することを確認**

Run: `php artisan test --compact`
Expected: 全20件（新規3件含む）PASS。

---

### Task 3: 実行時（非本番）で strict モードを有効化

**Files:**
- Modify: `app/Providers/AppServiceProvider.php`（`boot()` 内）

- [ ] **Step 1: `boot()` に呼び出しを追加（本番は除外）**

```php
use Illuminate\Database\Eloquent\Model;

// ...
public function boot(): void
{
     // 既存の RateLimiter・Event 登録

    Model::shouldBeStrict(! $this->app->isProduction());
}
```

- [ ] **Step 2: 品質チェックを実行**

Run: `make php-pint` 後、`make php-stan`
Expected: ともにエラーなし。

---

### Task 4: コミット

**Files:**
- Commit

- [ ] **Step 1: 変更を追加してコミット**

```bash
git add tests/Feature/ModelStrictModeTest.php tests/TestCase.php app/Providers/AppServiceProvider.php
git commit -m "feat: Model::shouldBeStrict() をテストと非本番実行で有効化"
```

---

## 非対象
- `.claude/settings.local.json`（本作業と無関係のローカル設定）

## 成功条件
- `tests/TestCase.php` と `app/Providers/AppServiceProvider.php` に呼び出しが存在する。
- `tests/Feature/ModelStrictModeTest.php` の3テストがパスする。
- 既存の全テストが strict 有効化下でも通過する。
- `make php-pint` / `make php-stan` がエラーなし。

---
*Plan written by opencode.*
