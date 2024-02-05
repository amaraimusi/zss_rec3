<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>LINE DEMO</title>
    </head>
    <body>
    	<h2>LINEデモ</h2>


		<div>
			<ol>
				<li><a href="line_demo/audience">オーディエンス一覧/登録</a></li>
			</ol>
		</div>
    	
    </body>
</html>
