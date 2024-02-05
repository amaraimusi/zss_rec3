

var g_crudBaseData;

$(()=>{
	
	let crud_base_json = $('#crud_base_json').val();
	g_crudBaseData = JSON.parse(crud_base_json);
	
	// FileUploadKによる画像ファイルアップロード関連の初期化処理をする
	_initFileUploadK(g_crudBaseData);
 
	
});


/**
 * FileUploadKによる画像ファイルアップロード関連の初期化処理をする
 */
function _initFileUploadK(crudBaseData){
	
	let fileUploadK = new FileUploadK();
	
	let ent = crudBaseData.ent;
	fileUploadK.addEvent('img_fn', {'valid_ext':'image'});
	let fps = [ent.img_fn];
	let midway_dp = crudBaseData.paths.public_url + '/';
	fileUploadK.setFilePaths('img_fn', fps, {'midway_dp':midway_dp,});
}



function onSubmit1(){
	// バリデーション
	$('#valid_err_msg').html('');
	//　Form内の各input要素内のバリデーションを実行する
	let valid_err_msg = g_onsubmitValidation('form1');
	if(valid_err_msg){
		$('.js_valid_err_msg').html(valid_err_msg);
		return false;
	}
	
	// Submitボタン2重押下対策
	$('.js_submit_btn').hide(); // Submitボタンを隠す。
	$('.js_submit_msg').show(); // Submitメッセージを表示
	
	return true;
}