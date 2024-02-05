<?php 
$ver_str = '?=1.0.0';
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('/js/app.js') }}" defer></script>
		<script src="{{ asset('/js/jquery-3.6.1.min.js') }}" defer></script>
		<script src="{{ asset('/js/LineDemo/AudienceModel.js')  . $ver_str}} }}" defer></script>
		<script src="{{ asset('/js/LineDemo/audience.js')  . $ver_str}} }}" defer></script>
		
		<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
		<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/common/style.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	
        <title>LINEオーディエンス一覧/登録</title>
    </head>
    <body><div class="container-fluid">
    	
    	
    	<h2>LINEオーディエンス一覧/登録</h2>
		<div id="app"></div>

		<div id="err" class="text-danger"></div>
		<div id="res" class="text-success"></div>
		
		<div>
			<div><button type="button" onclick="audience_list()" class="btn btn-primary">一覧</button></div>
			<div id="audience_list"></div>

		</div>
		
		<div>
		
			<table id="form_tbl" class="table" style="max-width:1600px">
				<thead>
					<tr><th style="width:10%;">名称</th><th style="width:40%;">入力</th><th style="width:50%;">説明</th></tr>
				</thead>
				<tbody>
					<tr><td>アクセストークン</td><td colspan="2"><textarea  name="access_token" class="form-control"></textarea></td></tr>
					<tr><td>オーディエンス名</td><td><input type="text" name="description" value=""  class="form-control"/></td><td></td></tr>
					<tr><td>IFAフラグ</td><td><input type="text" name="isIfaAudience" value=""  class="form-control"/></td>
						<td><div style="width:300px;">送信対象アカウントをIFAで指定する場合は、trueを指定します。<br>送信対象アカウントをユーザーIDで指定する場合は、falseを指定するか、isIfaAudienceプロパティを省略します。</div></td></tr>
					<tr><td>ジョブ説明</td><td><textarea  name="uploadDescription" class="form-control"></textarea></td><td>ジョブ（jobs[].description）に登録する説明</td></tr>
					<tr><td>ユーザー名リスト</td><td colspan="2"><textarea  name="audiences" class="form-control"></textarea><br>ユーザーIDまたはIFAの配列(コンマで連結)。省略すると、空のオーディエンスが作成されます。</td></tr>
				</tbody>
			</table>

			<button type="button" class="btn btn-success" onclick="regAudiense()">登録</button>
		</div>
		
		<input type="hidden" id="csrf_token" value='{{ csrf_token() }}'; />
    	
    	
   </div></body>
</html>
