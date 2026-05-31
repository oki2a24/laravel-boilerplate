# Vite Manifest Fake Implementation Plan

> **For Hermes:** Use subagent-driven-development skill to implement this plan task-by-task.

**Goal:** `tests/TestCase.php` にダミーの Vite マニフェスト作成・削除ロジックを実装し、テスト環境での `ViteManifestNotFoundException` を解消する。

**Architecture:** `tests/TestCase.php` の `setUp` で `public/build/manifest.json` を（存在しない場合のみ）作成し、`tearDown` で削除する。

**Tech Stack:** PHP, Laravel, PHPUnit

---

### Task 1: `setUp` にマニフェスト作成ロジックを追加

**Objective:** `setUp` メソッドに、テスト開始時に `public/build/manifest.json` を作成するロジックを挿入する。

**Files:**
- Modify: `tests/TestCase.php`

**Step 1: Write failing test**
（※ すでにエラーが再現されているため、ここでは「既存のテストがエラーを投げる状態」を確認するのみとする。エラーとならない場合はすでに npm run build によってファイルが作成されてしまった状態なため、一度対照ファイルを削除すること）
Run: `php artisan test`
Expected: FAIL — `Illuminate\Foundation\V13\ViteManifestNotFoundException`

**Step 2: Implement minimal implementation**

```php
    protected function setUp(): void
    {
        parent::setUp();

        $manifestPath = public_path('build/manifest.json');
        $manifestDir = public_path('build');

        if (!file_exists($manifestPath)) {
            if (!is_dir($manifestDir)) {
                mkdir($manifestDir, 0777, true);
            }
            file_put_contents($manifestPath, '{}');
        }
    }
```

**Step 3: Run test to verify pass**

Run: `php artisan test`
Expected: PASS (or at least, the `ViteManifestNotFoundException` is gone)

**Step 4: Commit**

```bash
git add tests/TestCase.php
git commit -m "test: add dummy vite manifest creation in setUp"
```

---

### Task 2: `tearDown` にクリーンアップロジックを追加

**Objective:** `tearDown` メソッドに、テスト終了後に作成したダミーファイルを削除するロジックを挿入する。

**Files:**
- Modify: `tests/TestCase.php`

**Step 1: Write failing test (Verification of pollution)**
(Manually check if the file persists after a test run)
Run: `ls public/build/manifest.json`
Expected: File exists (showing that we need cleanup)

**Step 2: Implement minimal implementation**

```php
    protected function tearDown(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            unlink($manifestPath);
        }

        parent::tearDown();
    }
```

**Step 3: Run test to verify pass (Cleanup check)**

Run: `php artisan test`
Then check: `ls public/build/manifest.json`
Expected: `ls: cannot access 'public/build/manifest.json': No such file or directory`

**Step 4: Commit**

```bash
git add tests/TestCase.php
git commit -m "test: add dummy vite manifest cleanup in tearDown"
```

---

### Task 3: Final Verification

**Objective:** 全てのテストが成功し、クリーンアップが完全であることを最終確認する。

**Files:**
- Test: `php artisan test`

**Step 1: Run all tests**

Run: `php artisan test`
Expected: PASS (All tests pass)

**Step 2: Verify no leftover files**

Run: `ls public/build/manifest.json`
Expected: `No such file or directory`

**Step 3: Commit**

```bash
git add .
git commit -m "test: complete implementation of vite manifest fake"
```
