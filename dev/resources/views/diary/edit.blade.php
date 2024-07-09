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
	<script src="{{ asset('/js/Diary/edit.js')  . $ver_str}} }}" defer></script>
	
	<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
	<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	{!! $cbh->crudBaseCss(0, $this_page_version) !!}
	<link href="{{ asset('/css/Diary/edit.css')  . $ver_str}}" rel="stylesheet">
	
	<title>日誌管理・編集フォーム</title>
	
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
	<li class="breadcrumb-item"><a href="{{ url('diary') }}">日誌管理・一覧</a></li>
	<li class="breadcrumb-item active" aria-current="page">日誌管理・編集フォーム</li>
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
		<form id="form1" method="POST" action="{{ url('diary/update') }}" enctype="multipart/form-data">
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
				<label for="category" class="col-12 col-md-5 col-form-label">カテゴリ</label>
				<div class="col-12 col-md-7">
					<input name="category" type="text"  class="form-control form-control-lg" placeholder="category" value="{{old('category', $ent->category)}}" required  title="カテゴリを入力してください。">
				</div>
			</div>
			
			<div class="row">
				<label for="diary_date" class="col-12 col-md-5 col-form-label">日誌日付</label>
				<div class="col-12 col-md-7">
					<input name="diary_date" type="date"  class="form-control form-control-lg" placeholder="diary_date" value="{{old('diary_date', $ent->diary_date)}}">
				</div>
			</div>
			
			<div class="row">
				<label for="diary_note" class="col-12 col-md-5 col-form-label">日誌</label>
				<div class="col-12 col-md-7">
					<textarea name="diary_note" id="diary_note" class="form-control form-control-lg" placeholder="日誌"  maxlength="2000">{{old('diary_note', $ent->diary_note)}}</textarea>
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