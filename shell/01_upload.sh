#!/bin/sh
echo 'ソースコードを差分アップロードします。'

rsync -auvz --exclude='.env' --exclude='vendor' --exclude='storage' --exclude='tmp' --exclude='node_modules' ../dev amaraimusi@amaraimusi.sakura.ne.jp:www/mng/zss_rec3/

rsync -auvz  ../dev/.env_p amaraimusi@amaraimusi.sakura.ne.jp:www/mng/zss_rec3/dev/.env

echo "------------ アップロード完了"
#cmd /k