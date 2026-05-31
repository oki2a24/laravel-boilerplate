# Brainstorming 知見 (L4 Project)

## ドキュメンテーションと SSOT の規律
- **設定ファイル優先 (SSOT)**: `README.md` や技術スタックの記述を更新する際は、必ず `composer.json`, `package.json`, `compose.yaml` 等の物理的な設定ファイルを「唯一の真実のソース (SSOT)」として読み込み、それに基づいた記述を行わなければならない。ドキュメントの内容と設定ファイルに乖離がある場合は、設定ファイルの値を正とする。

## 調査プロセスの安定化
- **ツールエラーへの即時対応**: `application-info` 等の MCP ツールがエラー（JSON Syntax Error 等）を返した場合は、深追いせず直ちに `read_file` や `grep_search` を使用して、ソースコード（`composer.json` 等）から直接情報を収集する代替手順に切り替えなければならない。
