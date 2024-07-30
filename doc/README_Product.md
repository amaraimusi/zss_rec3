# 本番環境構築手順 v2.0


① サーバーにデータベースを作成しておく。

データベース名→amaraimusi_zss_rec

backupディレクトリにsqlファイルがいくつかあるので最新のsqlファイルをインポートする。

② サーバー側のphpバージョンを合わせる

サーバーのphpバージョンを8.1にする。

③ ソースコードをサーバーにアップする

00_upload_all.shを実行する

④ シンボリックリンクを作成する

サーバーへログインし、シンボリックリンクを作成する

```
ssh -l amaraimusi amaraimusi.sakura.ne.jp
cd www
ln -s  /home/amaraimusi/www/mng/zss_rec3/dev/public zss_rec3
```

⑤ 確認

以下のURLにアクセスし画面が表示されれば成功

https://amaraimusi.sakura.ne.jp/zss_rec3