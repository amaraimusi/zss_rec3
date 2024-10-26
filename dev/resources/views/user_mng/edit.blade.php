<?php
use App\Helpers\CrudBaseHelper;

$ver_str = '?v=' . $this_page_version;

$cbh = new CrudBaseHelper($crudBaseData);
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<script src="{{ asset('/js/app.js') }}" defer></script>
	<script src="{{ asset('/js/common/jquery-3.6.0.min.js') }}" defer></script>
	{!! $cbh->crudBaseJs(1, $this_page_version) !!}
	<script src="{{ asset('/js/UserMng/edit.js')  . $ver_str}} }}" defer></script>
	
	<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
	<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	{!! $cbh->crudBaseCss(0, $this_page_version) !!}
	<link href="{{ asset('/css/UserMng/edit.css')  . $ver_str}}" rel="stylesheet">
	
	<title>ユーザー管理管理・編集フォーム</title>
	
</head>

<body>
@include('layouts.common_header')
<div class="container-fluid">

<div id="app"><!-- vue.jsの場所・未使用 --></div>

<div class="d-flex flex-row m-1 m-sm-4 px-sm-5 px-1">
<main class="flex-fill mx-sm-2 px-sm-5 mx-1 px-1 w-100">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="{{ url('/') }}">ホーム</a></li>
	<li class="breadcrumb-item"><a href="{{ url('user_mng') }}">ユーザー管理管理・一覧</a></li>
	<li class="breadcrumb-item active" aria-current="page">ユーザー管理管理・編集フォーム</li>
  </ol>
</nav>

<!-- バリデーションエラーの表示 -->
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div>
	<div class="form_w" >
		<form id="form1" method="POST" action="{{ url('user_mng/update') }}" enctype="multipart/form-data">
			@csrf
			
			<div class="row">
				<div class="col-12" style="text-align:right">
					<button  class="btn btn-warning btn-lg js_submit_btn" onclick="return onSubmit1()">変更</button>
					<div class="text-danger js_valid_err_msg"></div>
					<div class="text-success js_submit_msg" style="display:none" >データベースに登録中です...</div>
				</div>
			</div>
			
			<input type="hidden" name="id" value="{{old('id', $ent->id)}}" />
			
			<!-- CBBXS-6091 -->
			<div class="row">
				<label for="name" class="col-12 col-md-5 col-form-label">ユーザー/アカウント名</label>
				<div class="col-12 col-md-7">
					<input name="name" type="text"  class="form-control form-control-lg" placeholder="name" value="{{old('name', $ent->name)}}" required  title="ユーザー/アカウント名を入力してください。">
				</div>
			</div>
			
			<div class="row">
				<label for="email" class="col-12 col-md-5 col-form-label">メールアドレス</label>
				<div class="col-12 col-md-7">
					<input name="email" type="text"  class="form-control form-control-lg" placeholder="email" value="{{old('email', $ent->email)}}" required  title="メールアドレスを入力してください。">
				</div>
			</div>

			<div class="row">
				<label for="nickname" class="col-12 col-md-5 col-form-label">名前</label>
				<div class="col-12 col-md-7">
					<input name="nickname" type="text"  class="form-control form-control-lg" placeholder="nickname" value="{{old('nickname', $ent->nickname)}}" required  title="名前を入力してください。">
				</div>
			</div>
			
			<div class="row">
				<label for="password" class="col-12 col-md-5 col-form-label">パスワード</label>
				<div class="col-12 col-md-7">
					<input name="password" type="text"  class="form-control form-control-lg" placeholder="password" value="{{old('password', $ent->password)}}" required  title="パスワードを入力してください。">
				</div>
			</div>

			<div class="row">
				<label for="role" class="col-12 col-md-5 col-form-label">権限</label>
				<div class="col-12 col-md-7">
					<select name="role" class="form-control form-control-lg">
						@foreach ($roleList as $role => $role_name)
							<option value="{{ $role }}" @selected(old('role', $ent->role) == $role)>
								{{ $role_name }}
							</option>
						@endforeach
					</select>
				</div>
			</div>

			<!-- CBBXE -->

			<div class="row">
				<div class="col-12" style="text-align:right">
					<button  class="btn btn-warning btn-lg js_submit_btn" onclick="return onSubmit1()">変更</button>
					<div class="text-danger js_valid_err_msg"></div>
					<div class="text-success js_submit_msg" style="display:none" >データベースに登録中です...</div>
				</div>
			</div>
			
			
		</form>
		
	</div>
</div>

</main>
</div><!-- d-flex -->

</div><!-- container-fluid -->

@include('layouts.common_footer')

<!-- JSON埋め込み -->
<input type="hidden" id="csrf_token" value="{{ csrf_token() }}" >
{!! $cbh->embedJson('crud_base_json', $crudBaseData) !!}

</body>
</html>