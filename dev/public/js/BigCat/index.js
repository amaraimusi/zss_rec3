
var crudBase;//AjaxによるCRUD

jQuery(()=> {
	
	
	init();//初期化
	
	$('#big_cat_tbl').show();// 高速表示のためテーブルは最後に表示する
	
});



/**
 *  有名猫画面の初期化
 * 
 * @version 1.0.0
 * @since 2022-1-25
 */
function init(){

	let crud_base_json = jQuery('#crud_base_json').val();
	let crudBaseData = jQuery.parseJSON(crud_base_json);
	
	// 一覧データを取得します。
	let data_json = jQuery('#data_json').val();
	let data = jQuery.parseJSON(data_json);
	
	// CSRFトークンの取得およびセット。CSRFトークンはAjax通信で必須なパラメータです。セキュリティのためです。
	let csrf_token = jQuery('#csrf_token').val();
	crudBaseData['csrf_token'] = csrf_token;
	
	// 一覧テーブルid。  データ一覧テーブルのid属性です。
	crudBaseData['h_tbl_xid'] = 'big_cat_h_tbl';
	
	// 自動保存機能(CrudBaseAutoSave.js)のパラメータ。自動保存メッセージの出力先要素のid属性を指定しています。
	crudBaseData['auto_save_xid'] = 'js_auto_save_msg'; 
	
	// 自動保存機能(CrudBaseAutoSave.js)のAjax通信先URL。 このURLはLaravel側のアクションを指します。
	crudBaseData['auto_save_url'] = 'big_cat/auto_save';

	// フック。特定のイベントが実行されたタイミングで実行されるコールバック関数をセットします。
	$hooks = {
		'afterRowExchange':afterRowRxchange,
		'afterAutoSave':afterAutoSave,
	};
	
//	
	// CRUD基本クラス
	crudBase = new CrudBase(crudBaseData, data);
//	
//	// 検索条件バリデーション情報のセッター
//	let validMethods =_getValidMethods();
//	crudBase.setKjsValidationForJq(
//			'#big_catIndexForm',
//			crudBaseData,
//			validMethods,
//	);
//
//	
//	
//
//	// 表示フィルターデータの定義とセット
//	var disFilData = {
//			// CBBXS-1008
//			'big_cat_flg':{
//				'fil_type':'flg',
//				'option':{'list':['OFF','ON']}
//			},
//			'delete_flg':{
//				'fil_type':'delete_flg',
//			},
//
//			// CBBXE
//			
//	};
//	
//	// CBBXS-2023
//	// 有名猫種別リストJSON
//	let bigCatTypeList = crudBaseData.masters.bigCatTypeList;
//	disFilData['big_cat_type'] ={'fil_type':'select','option':{'list':bigCatTypeList}};
//	// 価格リストJSON
//	let priceList = crudBaseData.masters.priceList;
//	disFilData['price'] ={'fil_type':'select','option':{'list':priceList}};
//
//	// CBBXE
//
//	
//	crudBase.setDisplayFilterData(disFilData);
//
//	//列並替変更フラグがON（列並べ替え実行）なら列表示切替情報をリセットする。
//	if(localStorage.getItem('clm_sort_chg_flg') == 1){
//		this.crudBase.csh.reset();//列表示切替情報をリセット
//		localStorage.removeItem('clm_sort_chg_flg');
//	}
//
//	// 新規入力フォームのinput要素にEnterキー押下イベントを組み込む。
//	$('#ajax_crud_new_inp_form input').keypress(function(e){
//		if(e.which==13){ // Enterキーである場合
//			newInpReg(); // 登録処理
//		}
//	});
//	
//	// 編集フォームのinput要素にEnterキー押下イベントを組み込む。
//	$('#ajax_crud_edit_form input').keypress(function(e){
//		if(e.which==13){ // Enterキーである場合
//			editReg(); // 登録処理
//		}
//	});
//	
//	// CrudBase一括追加機能の初期化
//	var today = new Date().toLocaleDateString();
//	crudBase.crudBaseBulkAdd.init(
//		[
//			// CBBXS-2010
//			{'field':'id', 'inp_type':'textarea'}, 
//			{'field':'big_cat_type', 'inp_type':'select', 'list':bigCatTypeList, 'def':0}, 
//			{'field':'price', 'inp_type':'select', 'list':priceList, 'def':0}, 
//			{'field':'subsc_count', 'inp_type':'textarea'}, 
//			{'field':'big_cat_flg', 'inp_type':'textarea'}, 
//			{'field':'sort_no', 'inp_type':'textarea'}, 
//			{'field':'delete_flg', 'inp_type':'textarea'}, 
//			{'field':'update_user_id', 'inp_type':'textarea'}, 
//
//			// CBBXE
//			
////			{'field':'big_cat_group', 'inp_type':'select', 'list':big_catGroupList, 'def':2}, 
////			{'field':'big_cat_date', 'inp_type':'date', 'def':today}, 
////			{'field':'note', 'inp_type':'text', 'def':'TEST'}, 
////			{'field':'sort_no', 'inp_type':'sort_no', 'def':1}, 
//		],
//		{
//			ajax_url:'big_cat/bulk_reg',
//			csrf_token:csrf_token,
//			ta_placeholder:"Excelからコピーした有名猫名、有名猫数値を貼り付けてください。（タブ区切りテキスト）\n(例)\n有名猫名A\t100\n有名猫名B\t101\n",
//		}
//	);
	
//	crudBase.newVersionReload(); // 新バージョンリロード
}


/**
 * 行入替後コールバック関数。
 * 
 * @desc
 * 行入替機能(RowExchange.js)の行入替後に実行されます
 * 
 */
function afterRowRxchange(){

}


/**
 * 自動保存後コールバック関数。
 * 
 * @desc
 * 自動保存機能(CrudBaseAutoSave.js)のDB更新後に実行されます。
 * いわゆるAjax通信後のレスポンス時に実行される処理です。
 * 当然ながらDB更新はバックエンド側で行われます。
 * 
 */
function afterAutoSave(){

	location.reload(true); // ブラウザをリロードする
}



/**
 * 検索条件バリデーション情報のセッター
 */
function _getValidMethods(){
	let methods = {
			// CBBXS-2011
			kj_id:(cbv, value)=>{
				let err = '';
				// 自然数バリデーション
				if(!cbv.isNaturalNumber(value)){
					err = '自然数で入力してください。';
				}
				return err;
			},
			kj_big_cat_name:(cbv, value)=>{
				let err = '';
				// 文字数バリデーション
				if(!cbv.isMaxLength(value, 255)){
					err = '255文字以内で入力してくだい。';
				}
				return err;
			},
			kj_img_fn:(cbv, value)=>{
				let err = '';
				// 文字数バリデーション
				if(!cbv.isMaxLength(value, 256)){
					err = '256文字以内で入力してくだい。';
				}
				return err;
			},
			kj_note:(cbv, value)=>{
				let err = '';
				// 文字数バリデーション
				if(!cbv.isMaxLength(value, ex)){
					err = 'ex文字以内で入力してくだい。';
				}
				return err;
			},
			kj_update_user_id:(cbv, value)=>{
				let err = '';
				// 自然数バリデーション
				if(!cbv.isNaturalNumber(value)){
					err = '自然数で入力してください。';
				}
				return err;
			},
			kj_ip_addr:(cbv, value)=>{
				let err = '';
				// 文字数バリデーション
				if(!cbv.isMaxLength(value, 40)){
					err = '40文字以内で入力してくだい。';
				}
				return err;
			},

			// CBBXE

	}
	return methods;
}


/**
 * 新規入力フォームを表示
 * @param btnElm ボタン要素
 */
function newInpShow(btnElm, ni_tr_place){
	crudBase.newInpShow(btnElm, {'ni_tr_place':ni_tr_place});
}

/**
 * 編集フォームを表示
 * @param btnElm ボタン要素
 */
function editShow(btnElm){
	
	crudBase.editShow(btnElm, 
			{
				'form_mode':2, // フォームモード 0:ダイアログモード , 1:アコーディオンモード(デフォルト）, 2:一覧非表示＆フォーム表示
				'callBack':(tr,form,ent)=>{
					// 表示処理後のコールバック
				}
			}
		);
		
}



/**
 * 複製フォームを表示（新規入力フォームと同じ）
 * @param btnElm ボタン要素
 */
function copyShow(btnElm){
	//crudBase.copyShow(btnElm);
	crudBase.copyShow(btnElm, {'form_mode':2});
}


/**
 * 削除アクション
 * @param btnElm ボタン要素
 */
function deleteAction(btnElm){
	crudBase.deleteAction(btnElm);
}


/**
 * 有効アクション
 * @param btnElm ボタン要素
 */
function enabledAction(btnElm){
	crudBase.enabledAction(btnElm);
}


/**
 * 抹消フォーム表示
 * @param btnElm ボタン要素
 */
function eliminateShow(btnElm){
	crudBase.eliminateShow(btnElm);
}

/**
 * 詳細検索フォーム表示切替
 * 
 * 詳細ボタンを押した時に、実行される関数で、詳細検索フォームなどを表示します。
 */
function show_kj_detail(){
	$("#kjs2").fadeToggle();
}

/**
 * フォームを閉じる
 * @parma string form_type new_inp:新規入力 edit:編集 delete:削除
 */
function closeForm(form_type){
	crudBase.closeForm(form_type)
}


/**
 * 検索条件をリセット
 * 
 * すべての検索条件入力フォームの値をデフォルトに戻します。
 * リセット対象外を指定することも可能です。
 * @param array exempts リセット対象外フィールド配列（省略可）
 */
function resetKjs(exempts){
	
	crudBase.resetKjs(exempts);
	
}


/**
 * 新規入力フォームの登録ボタンアクション
 */
function newInpReg(){
	crudBase.newInpReg(null,null);
}

/**
 * 編集フォームの登録ボタンアクション
 */
function editReg(){
	crudBase.editReg(null,null);
}

/**
 * 削除フォームの削除ボタンアクション
 */
function deleteReg(){
	crudBase.deleteReg();
}

/**
 * 抹消フォームの抹消ボタンアクション
 */
function eliminateReg(){
	crudBase.eliminateReg();
}


/**
 * リアクティブ機能：TRからDIVへ反映
 * @param div_slt DIV要素のセレクタ
 */
function trToDiv(div_slt){
	crudBase.trToDiv(div_slt);
}

/**
 * 行入替機能のフォームを表示
 * @param btnElm ボタン要素
 */
function rowExchangeShowForm(btnElm){
	crudBase.rowExchangeShowForm(btnElm);
}

/**
 * 自動保存の依頼をする
 * 
 * @note
 * バックグランドでHTMLテーブルのデータをすべてDBへ保存する。
 * 二重処理を防止するメカニズムあり。
 */
function saveRequest(){
	crudBase.saveRequest();
}


/**
 * セッションクリア
 * 
 */
function sessionClear(){
	crudBase.sessionClear();
	
}


/**
 * テーブル変形
 * @param mode_no モード番号  0:テーブルモード , 1:区分モード
 */
function tableTransform(mode_no){

	crudBase.tableTransform(mode_no);

}

/**
 * 検索実行
 */
function searchKjs(){
	crudBase.searchKjs();
}

/**
 * カレンダーモード
 */
function calendarViewKShow(){
	// カレンダービューを生成 
	crudBase.calendarViewCreate('big_cat_date');
}

