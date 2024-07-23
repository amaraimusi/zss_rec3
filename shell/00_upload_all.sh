#!/bin/sh
echo 'すべてソースコードをアップ'

rsync -auvz --exclude='.env' ../dev amaraimusi@amaraimusi.sakura.ne.jp:www/mng/zss_rec3/

rsync -auvz  ../dev/.env_p amaraimusi@amaraimusi.sakura.ne.jp:www/mng/zss_rec3/dev/.env

echo "------------ アップロード完了"
#cmd /k