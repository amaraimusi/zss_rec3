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
	<script src="{{ asset('/js/Knowledge/edit.js')  . $ver_str}} }}" defer></script>
	
	<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
	<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	{!! $cbh->crudBaseCss(0, $this_page_version) !!}
	<link href="{{ asset('/css/Knowledge/edit.css')  . $ver_str}}" rel="stylesheet">
	
	<title>教え管理・編集フォーム</title>
	
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
	<li class="breadcrumb-item"><a href="{{ url('knowledge') }}">教え管理・一覧</a></li>
	<li class="breadcrumb-item active" aria-current="page">教え管理・編集フォーム</li>
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
		<form id="form1" method="POST" action="{{ url('knowledge/update') }}" enctype="multipart/form-data">
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
				<label for="id" class="col-12 col-md-5 col-form-label">ID</label>
				<div class="col-12 col-md-7">
					<input name="id" type="number"  class="form-control form-control-lg" placeholder="id" value="{{old('id', $ent->id)}}">
				</div>
			</div>
			
			<div class="row">
				<label for="kl_text" class="col-12 col-md-5 col-form-label">心得テキスト</label>
				<div class="col-12 col-md-7">
					<textarea name="kl_text" id="kl_text" class="form-control form-control-lg" placeholder="心得テキスト"  maxlength="2000">{{old('kl_text', $ent->kl_text)}}</textarea>
				</div>
			</div>

			<div class="row">
				<label for="xid" class="col-12 col-md-5 col-form-label">XID</label>
				<div class="col-12 col-md-7">
					<input name="xid" type="number"  class="form-control form-control-lg" placeholder="xid" value="{{old('xid', $ent->xid)}}">
				</div>
			</div>
			
			<div class="row">
				<label for="kl_category" class="col-12 col-md-5 col-form-label">カテゴリ</label>
				<div class="col-12 col-md-7">
					<select name="kl_category" class="form-control form-control-lg">
						@foreach ($klCategoryList as $kl_category => $kl_category_name)
							<option value="{{ $kl_category }}" @selected(old('kl_category', $ent->kl_category) == $kl_category)>
								{{ $kl_category_name }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			
			<div class="row">
				<label for="contents_url" class="col-12 col-md-5 col-form-label">内容URL</label>
				<div class="col-12 col-md-7">
					<textarea name="contents_url" id="contents_url" class="form-control form-control-lg" placeholder="内容URL"  maxlength="2000">{{old('contents_url', $ent->contents_url)}}</textarea>
				</div>
			</div>

			<div class="row">
				<label for="doc_name" class="col-12 col-md-5 col-form-label">文献名</label>
				<div class="col-12 col-md-7">
					<input name="doc_name" type="text"  class="form-control form-control-lg" placeholder="doc_name" value="{{old('doc_name', $ent->doc_name)}}" required  title="文献名を入力してください。">
				</div>
			</div>
			
			<div class="row">
				<label for="doc_text" class="col-12 col-md-5 col-form-label">文献テキスト</label>
				<div class="col-12 col-md-7">
					<textarea name="doc_text" id="doc_text" class="form-control form-control-lg" placeholder="文献テキスト"  maxlength="2000">{{old('doc_text', $ent->doc_text)}}</textarea>
				</div>
			</div>

			<div class="row">
				<label for="dtm" class="col-12 col-md-5 col-form-label">学習日時</label>
				<div class="col-12 col-md-7">
					<input name="dtm" type="text"  class="form-control form-control-lg" placeholder="dtm" value="{{old('dtm', $ent->dtm)}}" pattern="[0-9]{4}(-|/)[0-9]{1,2}(-|/)[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}" title="日時（Y-m-d H:i:s)を入力してください。(例  2012-12-12 12:12:12)">
				</div>
			</div>
			
			<div class="row">
				<label for="next_dtm" class="col-12 col-md-5 col-form-label">次回日時</label>
				<div class="col-12 col-md-7">
					<input name="next_dtm" type="text"  class="form-control form-control-lg" placeholder="next_dtm" value="{{old('next_dtm', $ent->next_dtm)}}" pattern="[0-9]{4}(-|/)[0-9]{1,2}(-|/)[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}" title="日時（Y-m-d H:i:s)を入力してください。(例  2012-12-12 12:12:12)">
				</div>
			</div>
			
			<div class="row">
				<label for="level" class="col-12 col-md-5 col-form-label">学習レベル</label>
				<div class="col-12 col-md-7">
					<input name="level" type="number"  class="form-control form-control-lg" placeholder="level" value="{{old('level', $ent->level)}}">
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