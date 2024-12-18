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
	<script src="{{ asset('/js/Sales/show.js')  . $ver_str}} }}" defer></script>
	
	<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
	<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	<link href="{{ asset('/css/common/style.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/Sales/show.css')  . $ver_str}}" rel="stylesheet">
	
	<title>教えカテゴリ管理・詳細表示</title>
	
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
	<li class="breadcrumb-item"><a href="{{ url('kl_category') }}">教えカテゴリ管理・一覧</a></li>
	<li class="breadcrumb-item active" aria-current="page">教えカテゴリ管理・詳細フォーム</li>
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
		<form method="POST" action="{{ url('kl_category/update') }}">
			@csrf
			
			<input type="hidden" name="id" value="{{old('id', $ent->id)}}" />
			
			<div class="row">
				<label for="id" class="col-12 col-md-5 col-form-label">id</label>
				<div class="col-12 col-md-7">{{ $ent-> id}}</div>
			</div>
			<!-- CBBXS-6008 -->
			<div class="row">
				<label for="id" class="col-12 col-md-5 col-form-label">ID</label>
				<div class="col-12 col-md-7">{{ $ent-> id}}</div>
			</div>

			<div class="row">
				<label for="kl_category_name" class="col-12 col-md-5 col-form-label">カテゴリ名</label>
				<div class="col-12 col-md-7">{{ $ent-> kl_category_name}}</div>
			</div>

			<div class="row">
				<label for="category_code" class="col-12 col-md-5 col-form-label">カテゴリコード</label>
				<div class="col-12 col-md-7">{{ $ent-> category_code}}</div>
			</div>


			<!-- CBBXE -->

			<div class="row">
				<div class="col-12" style="text-align:center">
					<a href="edit?id={{$ent->id}}" class="btn btn-primary">編集</a>
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