# 本番環境構築手順 v2.0


## サーバーにデータベースを作成しておく。

データベース名→amaraimusi_zss_rec

backupディレクトリにsqlファイルがいくつかあるので最新のsqlファイルをインポートする。

## サーバー側のphpバージョンを合わせる

サーバーのphpバージョンを8.1にする。

## ソースコードをサーバーにアップする

00_upload_all.shを実行する

## シンボリックリンクを作成する

サーバーへログインし、シンボリックリンクを作成する

```
ssh -l amaraimusi amaraimusi.sakura.ne.jp
cd www/zss_rec3
ln -s  /home/amaraimusi/www/mng/zss_rec3/dev/public
```

## 確認

以下のURLにアクセスし画面が表示されれば成功

https://amaraimusi.sakura.ne.jp/zss_rec3