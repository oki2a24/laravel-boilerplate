# PHPUnit 10+ Test Attributes Migration Design

## 1. 概要
Laravel のテストファイルにおいて、旧来の `/** @test */` アノテーションを PHP 8 の `#[Test]` 属性に移行する。これにより、PHPUnit 10 以降の標準的な記法に合わせ、コードの可読性と型安全性を向上させる。

## 2. 背景と目的
- PHPUnit 10 から導入された属性（Attributes）を活用する。
- メソッドの戻り値型 `void` を明示することで、より厳密な型定義を行う。
- プロジェクト全体のコーディング規約を最新の状態に保つ。

## 3. 変更内容
対象となる 7 つのテストファイルに対して、以下の変更を行う。

### 3.1. インポートの追加
各ファイルの `namespace` 宣言の後の `use` セクションに以下を追加する。
```php
use PHPUnit\Framework\Attributes\Test;
```

### 3.2. アノテーションの置換
既存の `/** @test */` または `@test` を含む DocBlock を削除し、メソッドの直前に `#[Test]` 属性を追加する。

**Before:**
```php
/** @test */
public function some_test_case()
{
    // ...
}
```

**After:**
```php
#[Test]
public function some_test_case(): void
{
    // ...
}
```

### 3.3. 戻り値型の補完
メソッドに `void` 戻り値型が指定されていない場合は、一貫性のために追加する。

## 4. 対象ファイル
1. `laravel/tests/Feature/Http/Controllers/Auth/RegisteredUserControllerTest.php`
2. `laravel/tests/Feature/Http/Controllers/Auth/VerifyEmailControllerTest.php`
3. `laravel/tests/Feature/Http/Controllers/Auth/AuthenticatedSessionController/DestroyTest.php`
4. `laravel/tests/Feature/Http/Controllers/Auth/AuthenticatedSessionController/StoreTest.php`
5. `laravel/tests/Feature/Http/Controllers/Auth/EmailVerificationNotificationController/StoreTest.php`
6. `laravel/tests/Feature/Http/Controllers/Auth/NewPasswordController/StoreTest.php`
7. `laravel/tests/Feature/Http/Controllers/Auth/PasswordResetLinkController/StoreTest.php`

## 5. 検証計画
- 修正後、`make php-test` を実行し、すべてのテストがパスすることを確認する。
- `make php-check-all` を実行し、静的解析（Larastan）やフォーマット（Pint）に問題がないか確認する。
