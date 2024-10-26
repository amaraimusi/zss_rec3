

<div id="form_spa" style="display:none">
<div style="max-width:840px;margin: auto;">

	<div class="row">
		<div class="col-md-3">
			<h2 class="text-success js_create_mode" >新規入力</h2>
			<h2 class="text-primary js_edit_mode">編集</h2>
		</div>
		<div class="col-md-9" style="text-align:right">
		
			<span class="text-danger js_valid_err_msg">エラーメッセージ</span>
			<span class="text-success js_registering_msg"  >データベースに登録中です...</span>
			<button type="button" class="btn btn-success  btn-lg js_submit_btn js_create_mode" onclick="regAction();">登録</button>
			<button type="button" class="btn btn-warning  btn-lg js_submit_btn js_edit_mode" onclick="regAction();">変更</button>
			<button type="button" class="btn btn-outline-secondary btn-lg close" aria-label="閉じる" onclick="closeForm()" >閉じる</button>
		</div>
	</div>

	<div class="err text-danger"></div>
	
	
	<input type="hidden" name="sort_no">
	
	<div class="row js_edit_mode">
		<div class='col-md-12'>
			ID:<span data-display="id"></span>
			<input type="hidden" name="id" value=''  />
		</div>
	</div>
	
	<!-- CBBXS-6007 -->
	<div class="row mt-2">
		<div class='col-md-2 ' >ユーザー/アカウント名</div>
		<div class='col-md-10'>
			<input type="text" name="name" class="form-control form-control-lg " value=""  maxlength="255"  required title="ユーザー/アカウント名は255文字以内で入力してください" />
			<span class="text-danger" data-valid-err='name'></span>
		</div>
	</div>

	<div class="row mt-2">
		<div class='col-md-2 ' >メールアドレス</div>
		<div class='col-md-10'>
			<input type="text" name="email" class="form-control form-control-lg " value=""  maxlength="255"  required title="メールアドレスは255文字以内で入力してください" />
			<span class="text-danger" data-valid-err='email'></span>
		</div>
	</div>

	<div class="row mt-2">
		<div class='col-md-2 ' >名前</div>
		<div class='col-md-10'>
			<input type="text" name="nickname" class="form-control form-control-lg " value=""  maxlength="50"  required title="名前は50文字以内で入力してください" />
			<span class="text-danger" data-valid-err='nickname'></span>
		</div>
	</div>

	<div class="row mt-2">
		<div class='col-md-2 ' >パスワード</div>
		<div class='col-md-10'>
			<button id="pw_change_btn" class="btn btn-warning btn-sm" onclick="clickPwChangeBtn()">パスワードを変更する</button>
			<div id="pw_div" style="display:none">
				<input type="text" name="password" class="form-control form-control-lg " value=""  maxlength="255"  required title="パスワードは255文字以内で入力してください" />
				<span class="text-danger" data-valid-err='password'></span>
			</div>
		</div>
	</div>

	<div class="row mt-2">
		<div class='col-md-2' >権限 </div>
		<div class='col-md-10'>
			<select name="role" class="form-control form-control-lg">
				@foreach ($roleList as $role => $role_name)
					<option value="{{ $role }}" @selected(old('role', $searches['role']) == $role)>
						{{ $role_name }}
					</option>
				@endforeach
			</select>
		</div>
	</div>

	<!-- CBBXE -->
	
	<div class="row mt-2">
		<div class='col-md-2' >削除</div>
		<div class='col-md-10'>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" value="" id="delete_flg" name="delete_flg">
				<label class="form-check-label" for="delete_flg">チェックすると削除扱いになります</label>
			</div>
		</div>
	</div>
	

	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-9" style="text-align:right">
		
			<span class="text-danger js_valid_err_msg">エラーメッセージ</span>
			<span class="text-success js_registering_msg"  >データベースに登録中です...</span>
			<button type="button" class="btn btn-success  btn-lg js_submit_btn js_create_mode" onclick="regAction();">登録</button>
			<button type="button" class="btn btn-warning  btn-lg js_submit_btn js_edit_mode" onclick="regAction();">変更</button>
			<button type="button" class="btn btn-outline-secondary btn-lg close" aria-label="閉じる" onclick="closeForm()" >閉じる</button>
		</div>
	</div>
	
	<div class="cbf_inp_wrap js_edit_mode" style="padding:5px;">
		<input type="button" value="更新情報" class="btn btn-secondary btn-sm" onclick="$('#edit_detail_info').toggle(300)" /><br>
		<aside id="edit_detail_info" style="display:none">
			<div>更新日時: <span data-display="updated_at"></span></div>
			<div>生成日時: <span data-display="created_at"></span></div>
			<div>更新ユーザー名: <span data-display="update_user"></span></div>
			<div>IPアドレス: <span data-display="ip_addr"></span></div>
		</aside>
	</div>

</div><!-- max-width -->
</div><!-- form_spa -->