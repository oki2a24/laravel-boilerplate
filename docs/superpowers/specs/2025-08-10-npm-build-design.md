# `make npm-build` コマンドの設計書

## 目的
Vite を使用したフロントエンドアセットのコンパイルを簡素化するために、Makefile のターゲット `npm-build` を作成する。

## 要件
- Docker コンテナ内で `npm run build` を実行すること。
- 既存の `Makefile` ターゲット（`docker compose exec --user=app app` を使用）のパターンに従うこと。
- ターゲット名: `npm-build`

## 設計詳細
ルートの `Makefile` に新しいターゲットとして実装する：
```makefile
npm-build:
	docker compose exec --user=app app npm run build
```

## クレテリア（成功条件）
- `make npm-build` を実行すると、コンテナ内で `vite build` が正しく実行されること。
- コマンドがプロジェクトの Docker 実行規約に従っていること。

---
*Spec written by opencode.*
