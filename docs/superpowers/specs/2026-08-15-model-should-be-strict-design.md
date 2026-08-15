# `Model::shouldBeStrict()` 導入の設計書

## 目的
Eloquent のサイレントバグ（遅延ロード・属性のサイレント破棄・欠損属性へのアクセス）を検出可能にする。これらを `Model::shouldBeStrict()` を利用して、テスト全体と非本番の実行環境で有効化する。

## 要件
- `shouldBeStrict()` が有効化すると有効になる3つの保護を検出対象とする:
  - `preventLazyLoading()` — 関係の遅延ロード時に例外（`LazyLoadingViolationException`）
  - `preventSilentlyDiscardingAttributes()` — `create()` 等への未定義属性のサイレント破棄時に例外
  - `preventAccessingMissingAttributes()` — 欠損属性のアクセス時に例外（`MissingAttributeException`）
- テスト環境: 全テストで有効化。
- 実行環境: 本番以外（開発・ローカル）で有効化。本番は影響がないものとする。
- 既存の全テストが strict 下でも通過する（既存コードに上記の混入がないこと）。

## 設計詳細

### `tests/TestCase.php`
`setUp()` 内で `Model::shouldBeStrict()` を呼ぶ。これによりこの `TestCase` を継承する全テストで3つの保護が有効になる。

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

### `app/Providers/AppServiceProvider.php`
`boot()` 内で `Model::shouldBeStrict(! $this->app->isProduction())` を呼ぶ。本番を除く環境で実行時の保護が有効になり、本番では既存の挙動を維持する。

```php
use Illuminate\Database\Eloquent\Model;

// ...
public function boot(): void
{
    // 既存の RateLimiter・Event 登録

    Model::shouldBeStrict(! $this->app->isProduction());
}
```

### `tests/Feature/ModelStrictModeTest.php`（新規）
`setUp()` で保護が実際に行きわたっていることを、フレームワークの公開ゲッタで検証する。

```php
use Illuminate\Database\Eloquent\Model;

public function test_lazy_loading_is_prevented(): void
{
    $this->assertTrue(Model::preventsLazyLoading());
}

public function test_silently_discarding_attributes_is_prevented(): void
{
    $this->assertTrue(Model::preventsSilentlyDiscardingAttributes());
}

public function test_accessing_missing_attributes_is_prevented(): void
{
    $this->assertTrue(Model::preventsAccessingMissingAttributes());
}
```

## 非対象・注意事項
- 本番環境では `AppServiceProvider` 側で `shouldBeStrict(false)` の扱い（`! isProduction()` で `false`）となるため、既存のコードパスへの影響はない。
- `.claude/settings.local.json` は本作業とは無関係のローカル設定であり、変更対象外とする。

## クレテリア（成功条件）
- `tests/TestCase.php` と `app/Providers/AppServiceProvider.php` に上記の呼び出しがあること。
- `tests/Feature/ModelStrictModeTest.php` の3テストがパスすること。
- 既存の全テストが strict 有効化下でも通過すること。
- `make php-pint` と `make php-stan` ともエラーがないこと。

---
*Spec written by opencode.*
