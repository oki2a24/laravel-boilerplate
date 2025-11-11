### **【指示書】Laravel バージョンアップ手順**

**目的:**
このリポジトリのLaravelフレームワークを、指定したターゲットバージョンにアップグレードする。

---

### **1. アップグレード情報の収集**

1.  **バージョンの特定:**
    *   **現在:** `laravel/composer.json` を開き、`require`セクションの`laravel/framework`の値（例: `^10.0`）から現在のメジャーバージョンを特定する。
    *   **ターゲット:** アップグレード先のLaravelのバージョンを決定する（例: `11.x`）。

2.  **公式アップグレードガイドの熟読:**
    *   Google等で「laravel *[現在のメジャーバージョン]* to *[ターゲットのメジャーバージョン]* upgrade guide」と検索し、**必ずLaravel公式サイトのアップグレードガイドを見つける。**
    *   以降の作業は、基本的にこの公式ガイドに沿って進める。ガイドには、依存パッケージの更新、非推奨機能、破壊的変更など、アップグレードに必要な全ての情報が含まれている。

---

### **2. アップグレード手順**

1.  **事前準備 (環境要件の確認):**
    *   公式アップグレードガイドで要求されている**PHP**と**Composer**のバージョンを確認する。
    *   `docker compose exec --user=app app bash` でコンテナに入り、`php --version` と `composer --version` を実行し、現在の環境が要件を満たしていることを確認する。

2.  **依存関係の更新:**
    *   `laravel/composer.json` を開き、公式アップグレードガイドの指示に従って、`require` および `require-dev` セクション内のパッケージのバージョンを更新する。
        *   `laravel/framework` 本体だけでなく、`laravel/sanctum` や `nunomaduro/collision` など、関連パッケージの更新も含まれる場合が多い。
    *   `docker compose exec --user=app app composer update --with-all-dependencies` を実行し、依存関係を更新する。

3.  **コードと設定の修正:**
    *   **A. 自動修正 (Rector):**
        1.  `laravel/rector.php` を開き、`sets` に登録されている `LaravelSetList` を、ターゲットバージョンに対応するものに更新する。（例: `LaravelSetList::LARAVEL_110`）
        2.  `docker compose exec --user=app app ./vendor/bin/rector process --dry-run` を実行し、適用される変更内容を事前に確認する。
        3.  問題がなければ、`--dry-run` を外してRectorを実行し、コードを自動修正する。
    *   **B. 手動修正:**
        *   公式アップグレードガイドを参照し、Rectorで自動修正されなかった項目や、プロジェクト固有のコードで対応が必要な箇所を修正する。
        *   **各バージョンアップには固有の変更点があるため、必ず公式ガイドの「Upgrading」セクション全体を確認すること。**
        *   *（参考）バージョン9→10のアップグレードでは、`App\Http\Kernel.php`のプロパティ名変更などが必要でした。*

4.  **検証と静的解析環境の調整:**
    1.  `make php-check-all` を実行し、静적解析やテストがすべて成功することを確認する。

    2.  **静的解析エラーが発生した場合:**
        *   エラーメッセージを注意深く読み、どのツール（`phpstan`, `pint`, `rector`など）が原因か、どのファイルで発生しているかを特定する。
        *   **原因の切り分け:**
            *   **Laravel本体の変更に起因するか？** → 公式アップグレードガイドに対応方法が記載されていないか確認する。
            *   **周辺ツール間の競合に起因するか？** （例: `ide-helper`の生成コードと`phpstan`の解析ルールの衝突など）
        *   **解決策の検討:**
            1.  **ツールの設定変更を検討する:** 各ツールの責任範囲を明確にし、設定ファイル（`phpstan.neon`, `pint.json`, `rector.php`など）で不要なルールを無効化したり、必要な設定を追加したりする。**静的解析の堅牢性を損なわない範囲での調整を優先する。**
            2.  **コード側の修正を検討する:** ツールの要求に合わせて、PHPDocアノテーションの追加や修正を行う。
            3.  **最終手段としてエラーを無視する:** `phpstan.neon`の`ignoreErrors`などでエラーを無視するのは、問題がツール側のバグであるなど、明確な理由がある場合に限定する。
        *   **修正と再検証:** 変更を加えた後、再度 `make php-check-all` を実行し、エラーが解消されるまでこのプロセスを繰り返す。

    3.  ローカル環境でアプリケーションを起動し、主要な機能が手動テストでも正常に動作することを確認する。

---

### **3. トラブルシューティング**

*   **Rectorの実行エラー:**
    *   **事象:** Rectorの実行時に `Process ... not found` のようなエラーが発生する。
    *   **原因:** Rector本体、または `driftingly/rector-laravel` のバージョンが古い可能性がある。
    *   **解決策:** `composer.json` を編集して、`driftingly/rector-laravel` のバージョンを最新のメジャーバージョン（例: `^1.0`）に更新し、`rector/rector` のバージョン指定を削除してから `composer update` を実行する。

*   **php-stanの実行エラー:**
    *   **事象:** php-stanの実行時に `PHPStan process crashed because it reached configured PHP memory limit` のようなエラーが発生する。
    *   **原因:** php-stanに割り当てられたメモリが不足している。
    *   **解決策:** `php-stan` コマンドに `--memory-limit` オプションを追加してメモリ制限を増やす。（例: `./vendor/bin/phpstan analyse --memory-limit=512M`）

---