#!/bin/sh
echo 'ソースコードを差分アップロードします。'

rsync -auvz --exclude='.git/' ../zss_rec3 amaraimusi@amaraimusi.sakura.ne.jp:www


echo "------------ 送信完了"
#cmd /k