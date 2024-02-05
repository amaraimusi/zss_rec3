
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <form action="aws_post" method="post" enctype="multipart/form-data">
        @csrf
        <p>
            <input type="file" name="datafile">
        </p>
        <p>
            <input type="submit" value="送信する">
        </p>
    </form>
</body>
</html>