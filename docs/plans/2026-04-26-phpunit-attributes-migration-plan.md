# PHPUnit 10+ Test Attributes Migration 実装計画

> **AIエージェントへの指示:** REQUIRED SUB-SKILL: この計画をタスクごとに実装するには、移植された `subagent-driven-development` スキル（推奨）または `executing-plans` スキルを `activate_skill` で起動して使用してください。ステップには追跡用のチェックボックス (`- [ ]`) を使用します。

**目標:** 旧来の `/** @test */` アノテーションを `#[Test]` 属性に移行し、必要に応じて `void` 戻り値型を追加する。

**アーキテクチャ:** 文字列置換によりアノテーションを属性に置き換え、インポート文を追加する。

**技術スタック:** PHP 8, PHPUnit 10

---

### タスク 1: RegisteredUserControllerTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/RegisteredUserControllerTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行して、このファイルに関連するテストがパスすることを確認。

---

### タスク 2: VerifyEmailControllerTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/VerifyEmailControllerTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - 全ての `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 3: AuthenticatedSessionController/DestroyTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/AuthenticatedSessionController/DestroyTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 4: AuthenticatedSessionController/StoreTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/AuthenticatedSessionController/StoreTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - 全ての `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 5: EmailVerificationNotificationController/StoreTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/EmailVerificationNotificationController/StoreTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - 全ての `/** @test */` を削除し、`#[Test]` を追加。
  - `メール確認済みの場合はメールを送信しないこと` メソッドに `: void` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 6: NewPasswordController/StoreTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/NewPasswordController/StoreTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - 全ての `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 7: PasswordResetLinkController/StoreTest.php のリファクタリング

**ファイル:**
- 変更: `laravel/tests/Feature/Http/Controllers/Auth/PasswordResetLinkController/StoreTest.php`

- [ ] **ステップ 1: ファイルを修正**
  - `use PHPUnit\Framework\Attributes\Test;` を追加。
  - 全ての `/** @test */` を削除し、`#[Test]` を追加。
- [ ] **ステップ 2: 検証**
  - `make php-test` を実行してパスすることを確認。

---

### タスク 8: 全体検証とコミット

- [ ] **ステップ 1: 全テスト実行**
  - `make php-test`
- [ ] **ステップ 2: コード品質チェック**
  - `make php-check-all`
- [ ] **ステップ 3: コミット**
  - `git commit -am "refactor: migrate test annotations to attributes"`
