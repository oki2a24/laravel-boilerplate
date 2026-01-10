# 開発ガイドライン（入力処理と設計方針）

このドキュメントは、本プロジェクトにおける  
**入力処理（FormRequest）と Controller 設計の基本方針**をまとめたものです。

本プロジェクトは比較的小規模であり、  
過度なレイヤー分割やアーキテクチャ導入は行いません。

その一方で、

- 入力の扱いを曖昧にしない
- バリデーション後のデータを安全に扱う
- 将来の変更に耐えられる形を保つ

ことを目的としています。

---

## 1. 基本方針

### 1.1 FormRequest は「境界」である

HTTP リクエストは外部入力であり、  
**そのまま Controller や内部処理に流してはいけない**と考えます。

- 外部 → 内部 の境界は **FormRequest**
- 境界の中で検証・整形・意味付けを完結させる
- Controller 以降は「信頼済みの値」だけを扱う

---

### 1.2 `validated()` は境界の途中の姿

`$request->validated()` が返す配列は、

- バリデーションは通っている
- しかし意味付けや構造化は終わっていない

**「境界処理の途中状態」**です。

そのため、本プロジェクトでは次を原則とします。

- ❌ Controller で `validated()` を直接使わない
- ✅ FormRequest 内でのみ `validated()` を使用する

---

## 2. Parse, don’t validate の考え方

本プロジェクトでは  
**「Parse, don’t validate」** https://lexi-lambda.github.io/blog/2019/11/05/parse-don-t-validate/ の考え方を次のように解釈します。

- 単に「正しいかどうか」を判定するだけで終わらせない
- 内部で使うための形に **整形・構造化してから渡す**
- Controller は値を疑わずに使える状態にする

これは「必ず Value Object を作る」という意味ではありません。

- string のままで良い場合もある
- ただし「意味のある値」として取り出す

ことを重視します。

---

## 3. FormRequest の責務

FormRequest は次の責務を持ちます。

- バリデーション（rules）
- 正規化（trim / 型変換 など）
- サブ構造へのまとめ（parse）

### 3.1 推奨される取り出し方

```php
$email = $request->email();
$address = $request->address();
````

* 配列ではなくメソッドで取得する
* Controller は入力構造を知らない

---

### 3.2 サブ構造へのまとめ

項目数が多い場合、意味のある単位でまとめます。

```php
public function address(): AddressData
{
    return new AddressData(
        zip: $this->validated('zip'),
        prefecture: $this->validated('prefecture'),
        city: $this->validated('city'),
    );
}
```

---

## 4. Data クラスについて

複数項目をまとめる場合は、
**Data クラス（単純な構造体）**を使用します。

### 4.1 配置場所

```text
app/
└─ Data/
   └─ AddressData.php
```

* 振る舞いは持たない
* public プロパティのみ
* 型と構造を表すためのクラス

---

## 5. Controller の役割

Controller は次のことだけを行います。

* FormRequest から「信頼済みの値」を受け取る
* 条件分岐・保存・レスポンス生成
* Laravel の機能を素直に使う

設計のための設計は行いません。

---

## 6. やらないこと

本プロジェクトでは、現時点では以下を行いません。

* Service / UseCase 層の導入
* Repository パターンの導入
* アーキテクチャを前提としたディレクトリ分割

必要になった場合にのみ検討します。

---

## 7. 迷ったときの判断基準

迷ったときは次を優先します。

1. Controller に配列を渡していないか
2. `validated()` が境界の外に漏れていないか
3. Controller が入力構造を知りすぎていないか

この3点を満たしていれば、
設計としては十分に健全です。
