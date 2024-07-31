# フロントエンドのCrudBase開発時における、gulpのコンパイルについて

バージョン 1.0.0

更新日:2024-7-31

## Gulpのインストール

gulpのインストールはシェルが用意されているので、以下のシェルを実行するだけ

```
gulp_dev/1_gulp_install.sh
```

## CrudBaseのコンパイルについて

CrudBaseのコンパイルは「dev/public/js/CrudBase/src」内のすべてのjsファイルを一つのjsファイルにまとめます。
一つにまとめられたjsファイルはCrudBase.min.jsとして「dev/public/js/CrudBase/dist」に配置されます。
コンパイルは以下のシェルを実行するだけす。

```
gulp_dev/2_gulp_crud_base.sh
```

css関連も同様です。「dev/public/css/CrudBase/src」内のcssファイルを
CrudBase.min.cssとしてまとめ、「dev/public/css/CrudBase/dist」に配置されます。
cssのコンパイルは以下のシェルです。

```
3_gulp_crud_base_for_css.sh
```

