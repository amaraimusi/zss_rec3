<?php

namespace App\Http\Controllers;

use App\Consts\crud_base_function;
use Illuminate\Http\Request;
use App\Models\RichMenuArea;
use CrudBase\CrudBase;
use App\Consts\ConstCrudBase;

/**
 * ネコ管理画面
 * @since 2023-9-28
 * @version 1.0.0
 * @author amaraimusi
 *
 */
class RichMenuAreaController extends CrudBaseController{
	
	// 画面のバージョン → 開発者はこの画面を修正したらバージョンを変更すること。バージョンを変更するとキャッシュやセッションのクリアが自動的に行われます。
	public $this_page_version = '1.0.1';
	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request){

		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		// 検索データのバリデーション
		$validated = $request->validate([
			'id' => 'nullable|numeric',
			'per_page' => 'nullable|numeric',
		]);
		
		$sesSearches = session('rich_menu_area_searches_key');// セッションからセッション検索データを受け取る

		// 新バージョンチェック  0:バージョン変更なし（通常）, 1:新しいバージョン
		$new_version = $this->judgeNewVersion($sesSearches, $this->this_page_version);

		$searches = []; // 検索データ
		
		// リクエストのパラメータが空でない、または新バージョンフラグがONである場合、リクエストから検索データを受け取る
		if(!empty($request->all()) || $new_version == 1){
			$searches = [
					'main_search' => $request->main_search, // メイン検索
				
					'id' => $request->id, // id
					
					// CBBXS-6000
					'rich_menu_area_val1' => $request->rich_menu_area_val1, // ネコ数値・範囲1
					'rich_menu_area_val2' => $request->rich_menu_area_val2, // ネコ数値・範囲2
					'rich_menu_area_name' => $request->rich_menu_area_name, // ネコメイ
					'rich_menu_area_date_ym' => $request->rich_menu_area_date_ym, // ネコ日付・年月
					'rich_menu_area_date1' => $request->rich_menu_area_date1, // ネコ日付・範囲1
					'rich_menu_area_date2' => $request->rich_menu_area_date2, // ネコ日付・範囲2
					'rich_menu_area_type' => $request->rich_menu_area_type, // 猫種別
					'rich_menu_area_dt' => $request->rich_menu_area_dt, // rich_menu_area_dt
					'rich_menu_area_flg' => $request->rich_menu_area_flg, // ネコフラグ
					'img_fn' => $request->img_fn, // 画像ファイル名
					'note' => $request->note, // 備考
					// CBBXE
					
					'sort_no' => $request->sort_no, // 順番
					'delete_flg' => $request->delete_flg, // 無効フラグ
					'update_user_id' => $request->update_user_id, // 更新者
					'ip_addr' => $request->ip_addr, // IPアドレス
					'created_at' => $request->created_at, // 生成日時
					'updated_at' => $request->updated_at, // 更新日
	
					'update_user' => $request->update_user, // 更新者
					'page' => $request->sort, // ページ番号
					'sort' => $request->sort, // 並びフィールド
					'desc' => $request->desc, // 並び向き
					'per_page' => $request->per_page, // 行制限数
			];
			
		}else{
			// リクエストのパラメータが空かつ新バージョンフラグがOFFである場合、セッション検索データを検索データにセットする
			$searches = $sesSearches;
		}

		$searches['this_page_version'] = $this->this_page_version; // 画面バージョン
		$searches['new_version'] = $new_version; // 新バージョンフラグ
		session(['rich_menu_area_searches_key' => $searches]); // セッションに検索データを書き込む

		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		$def_per_page = 20; // デフォルト制限行数
		
		$model = new RichMenuArea();
		$fieldData = $model->getFieldData();
		$data = $model->getData($searches, ['def_per_page' => $def_per_page]);
		$data_count = $data->total(); //　LIMIT制限を受けていないデータ件数
		
		// CBBXS-6001
		$rich_menu_areaTypeList = $model->getRichMenuAreaTypeList(); // ネコ種別リスト
        // CBBXE
        
		$crudBaseData = [
				'list_data'=>$data,
				'data_count'=>$data_count,
				'searches'=>$searches,
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'fieldData'=>$fieldData,
				'model_name_c'=>'RichMenuArea', // モデル名（キャメル記法）
				'model_name_s'=>'rich_menu_area', // モデル名（スネーク記法）
				'def_per_page'=>$def_per_page, // デフォルト制限行数
				'this_page_version'=>$this->this_page_version,
				'new_version' => $new_version,
				
				// CBBXS-6002
				'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
				// CBBXE
		];
        
		return view('rich_menu_area.index', [
			    'data'=>$data,
			    'searches'=>$searches,
				'userInfo'=>$userInfo,
				'fieldData'=>$fieldData,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData'=>$crudBaseData,
			    
			    // CBBXS-6003
			    'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
			    // CBBXE
		    
				
				
	   ]);
		
	}
	
	/**
	 * SPA型・入力フォームの登録アクション | 新規入力アクション、編集更新アクション、複製入力アクションに対応しています。
	 * @return string
	 */
	public function regAction(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$json=$_POST['key1'];
		
		$res = json_decode($json,true);
		
		$ent = $res['ent'];

		// IDフィールドです。 IDが空である場合、 新規入力アクションという扱いになります。なお、複製入力アクションは新規入力アクションに含まれます。
		$id = !empty($ent['id']) ? $ent['id'] : null;
		
		// DBテーブルからDBフィールド情報を取得します。
		$dbFieldData = $this->getDbFieldData('rich_menu_areas');
		
		// 値が空であればデフォルトをセットします。
		$ent = $this->setDefalutToEmpty($ent, $dbFieldData);
		
		// モデルを生成します。 新規入力アクションは真っ新なモデルを生成しますが、編集更新アクションの場合は、行データが格納されたモデルを生成します。
		$model = empty($id) ? new RichMenuArea() : RichMenuArea::find($id);
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		
		

		// CBBXS-XXXX
		$model->rich_menu_area_val = $ent['rich_menu_area_val']; // rich_menu_area_val
		$model->rich_menu_area_name = $ent['rich_menu_area_name']; // rich_menu_area_name
		$model->rich_menu_area_date = $ent['rich_menu_area_date']; // rich_menu_area_date
		$model->rich_menu_area_type = $ent['rich_menu_area_type']; // 猫種別
		$model->rich_menu_area_dt = $ent['rich_menu_area_dt']; // rich_menu_area_dt
		$model->rich_menu_area_flg = $ent['rich_menu_area_flg']; // ネコフラグ
		//$model->img_fn = $ent['img_fn']; // 画像ファイル名
		$model->note = $ent['note']; // 備考
		
		// CBBXE
		
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];
		$model->updated_at = date('Y-m-d H:i:s');
		
		
		if(empty($id)){
			$model->sort_no =$this->getNextSortNo('rich_menu_areas', 'asc');
			$model->save(); // DBへ新規追加: 同時に$modelに新規追加した行のidがセットされる。
			$ent['id'] = $model->id;
		}else{
			$model->update(); // DB更新
		}
		
		// CBBXS-6005
		// ▼ ファイルアップロード関連
		$fileUploadK = CrudBase::factoryFileUploadK();
		$front_img_fn = $ent['img_fn'];
		$exist_img_fn = $model->img_fn;
		$fRes = $fileUploadK->uploadForSpa('rich_menu_area', $_FILES, $ent, 'img_fn', $front_img_fn, $exist_img_fn);
		if($fRes['db_reg_flg']){
			$model->img_fn = $fRes['reg_fp'];
			$model->update(); // DB更新
		}
		// CBBXE
		
		$ent = $model->toArray();
		
		if(!empty($fRes['errs'])) $ent['errs'] = $fRes['errs'];
		
		$json = json_encode($ent, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		
		return $json;
	}
	
	
	/**
	 * 新規入力画面の表示アクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function create(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$model = new RichMenuArea();
		
		$copy_id = $request->id; // 複製元のid。空なら普通の新規入力になる
		
		$ent = $model->find($copy_id); // 複製元のエンティティを取得
		
		// 複製元のエンティティが空であれば、通常の新規入力になる。新規入力のデフォルト値をセットする。
		if($ent==null){
			$ent = $model->get();
			// CBBXS-4002
			$ent->rich_menu_area_val= '';
			$ent->rich_menu_area_name= '';
			$ent->rich_menu_area_date= '';
			$ent->rich_menu_area_type= 0;
			$ent->rich_menu_area_dt= '';
			$ent->rich_menu_area_flg= '';
			$ent->img_fn= '';
			$ent->note= '';
			$ent->sort_no= '';
			$ent->delete_flg= '';
			// CBBXE
		}
		
		if($ent->rich_menu_area_dt == '0000-00-00 00:00:00') $ent->rich_menu_area_dt = '';
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		// CBBXS-3037
		$rich_menu_areaTypeList = $model->getRichMenuAreaTypeList(); // ネコ種別リスト
		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent->toArray(),
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
		];
		
		return view('rich_menu_area.create', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData' => $crudBaseData,
				
		    	// CBBXS-3037B
		    	'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
		    	// CBBXE
			
		]);
		
	}
	
	
	/**
	 * 新規入力画面の登録ボタンアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function store(Request $request){
		
		if(\Auth::id() == null) die();
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する

		$request->validate([
				// CBBXS-3030
				'id' => 'nullable|numeric',
				'rich_menu_area_val' => 'nullable|numeric',
				'rich_menu_area_name' => 'nullable|max:255',
				'rich_menu_area_date' => 'nullable|date',
				'img_fn' => 'nullable|max:500000', // 最大アップロードは500MBまで
				'sort_no' => 'nullable|numeric',
				'update_user_id' => 'nullable|numeric',
				'ip_addr' => 'nullable|max:40',

			// CBBXE
		]);
		
		
		$model = new RichMenuArea();
		// CBBXS-3032
		$model->rich_menu_area_val = $request->rich_menu_area_val; // rich_menu_area_val
		$model->rich_menu_area_name = $request->rich_menu_area_name; // rich_menu_area_name
		$model->rich_menu_area_date = $request->rich_menu_area_date; // rich_menu_area_date
		$model->rich_menu_area_type = $request->rich_menu_area_type; // 猫種別
		$model->rich_menu_area_dt = $request->rich_menu_area_dt; // rich_menu_area_dt
		$model->rich_menu_area_flg = $request->rich_menu_area_flg; // ネコフラグ
		$model->img_fn = $request->img_fn; // 画像ファイル名
		$model->note = $request->note; // 備考

		// CBBXE
		
		$model->sort_no = $model->nextSortNo();
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];
		
		$model->save(); // DBへ新規追加と同時に$modelに新規追加した行のidがセットされる。

		// ▼ ファイルアップロード関連
		$fileUploadK = CrudBase::factoryFileUploadK();
		$ent = $model->toArray();
		$ent['img_fn_exist'] = $request->img_fn_exist; // 既存・画像ファイル名 img_fnの付属パラメータ
		$model->img_fn = $fileUploadK->uploadForLaravelMpa('rich_menu_area', $_FILES,  $ent, 'img_fn', 'img_fn_exist');

		$model->update(); // ファイル名をモデルにセットしたのでモデルをDB更新する。
		
		return redirect('/rich_menu_area');
		
	}
	
	
	/**
	 * 詳細画面の表示アクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function show(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$model = new RichMenuArea();
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		$id = $request->id;
		if(!is_numeric($id)){
			echo 'invalid access';
			die;
		}
		
		$ent = RichMenuArea::find($id);
		
		// CBBXS-3037
		$rich_menu_areaTypeList = $model->getRichMenuAreaTypeList(); // ネコ種別リスト
		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
		];
		

		return view('rich_menu_area.show', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
				'crudBaseData' => $crudBaseData,
		]);
		
	}
	
	
	/**
	 * 編集画面の表示アクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function edit(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');

		$model = new RichMenuArea();
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		$id = $request->id;
		if(!is_numeric($id)){
			echo 'invalid access';
			die;
		}
	
		$ent = RichMenuArea::find($id);
		
		// CBBXS-3038
		$rich_menu_areaTypeList = $model->getRichMenuAreaTypeList(); // ネコ種別リスト
		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent->toArray(),
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
		];
		
		return view('rich_menu_area.edit', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData'=>$crudBaseData,
				
			    // CBBXS-3038B
			    'rich_menu_areaTypeList'=>$rich_menu_areaTypeList,
				// CBBXE
			
		]);
		
	}
	
	
	/**
	 * 新規入力画面の登録ボタンアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function update(Request $request){
		
		if(\Auth::id() == null) die();

		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する

		$request->validate([
		   // CBBXS-3031
			'id' => 'nullable|numeric',
			'rich_menu_area_val' => 'nullable|numeric',
			'rich_menu_area_name' => 'nullable|max:255',
			'rich_menu_area_date' => 'nullable|date',
			'img_fn' => 'nullable|max:500000', // 最大アップロードは500MBまで
			'sort_no' => 'nullable|numeric',
			'update_user_id' => 'nullable|numeric',
			'ip_addr' => 'nullable|max:40',

			// CBBXE
		]);
		
		$model = RichMenuArea::find($request->id);

		$model->id = $request->id;
		
		// CBBXS-3033
		$model->rich_menu_area_val = $request->rich_menu_area_val; // rich_menu_area_val
		$model->rich_menu_area_name = $request->rich_menu_area_name; // rich_menu_area_name
		$model->rich_menu_area_date = $request->rich_menu_area_date; // rich_menu_area_date
		$model->rich_menu_area_type = $request->rich_menu_area_type; // 猫種別
		$model->rich_menu_area_dt = $request->rich_menu_area_dt; // rich_menu_area_dt
		$model->rich_menu_area_flg = $request->rich_menu_area_flg; // ネコフラグ
		$model->img_fn = $request->img_fn; // 画像ファイル名
		$model->note = $request->note; // 備考

		// CBBXE
		
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];

		// ▼ ファイルアップロード関連
		$fileUploadK = CrudBase::factoryFileUploadK();
		$ent = $model->toArray();
		$ent['img_fn_exist'] = $request->img_fn_exist; // 既存・画像ファイル名 img_fnの付属パラメータ
		$model->img_fn = $fileUploadK->uploadForLaravelMpa('rich_menu_area', $_FILES,  $ent, 'img_fn', 'img_fn_exist');

 		$model->update(); // DB更新
		
		return redirect('/rich_menu_area');
		
	}
	
	
	/**
	 * 削除/削除取消アクション(無効/有効アクション）
	 */
	public function disabled(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		
		$json=$_POST['key1'];
		
		$param = json_decode($json,true);//JSON文字を配列に戻す
		$id = $param['id'];
		$action_flg =  $param['action_flg'];

		$model = RichMenuArea::find($id);
		
		if(empty($action_flg)){
			$model->delete_flg = 0; // 削除フラグをOFFにする
		}else{
			$model->delete_flg = 1; // 削除フラグをONにする
		}
		
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];
		
		$model->update();
		
		$res = ['success'];
		$json_str = json_encode($res);//JSONに変換
		
		return $json_str;
	}
	
	
	/**
	 * 抹消アクション(無効/有効アクション）
	 */
	public function destroy(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		
		$json=$_POST['key1'];
		
		$param = json_decode($json,true);//JSON文字を配列に戻す
		$id = $param['id'];
		
		$model = new RichMenuArea();
		$model->destroy($id);// idを指定して抹消（データベースかDELETE）
		
		$res = ['success'];
		$json_str = json_encode($res);//JSONに変換
		
		return $json_str;
	}
	
	
	/**
	 * Ajax | ソート後の自動保存
	 *
	 * @note
	 * バリデーション機能は備えていない
	 *
	 */
	public function auto_save(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) die;

		$json=$_POST['key1'];
		
		$data = json_decode($json,true);//JSON文字を配列に戻す
		
		$model = new RichMenuArea();
		$model->saveAll($data);

		$res = ['success'];
		$json_str = json_encode($res);//JSONに変換
		
		return $json_str;
	}
	
	
	/**
	 * CSVダウンロード
	 *
	 * 一覧画面のCSVダウンロードボタンを押したとき、一覧データをCSVファイルとしてダウンロードします。
	 */
	public function csv_download(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');

		$searches = session('rich_menu_area_searches_key');// セッションからセッション検索データを受け取る

		$model = new RichMenuArea();
		$data = $model->getData($searches, ['use_type'=>'csv'] );
		
		// データ件数が0件ならCSVダウンロードを中断し、一覧画面にリダイレクトする。
		$count = count($data);
		if($count == 0){
			return redirect('/rich_menu_area');
		}
		
		// ダブルクォートで値を囲む
		foreach($data as &$ent){
			foreach($ent as $field => $value){
				if(mb_strpos($value,'"')!==false){
					$value = str_replace('"', '""', $value);
				}
				$value = '"' . $value . '"';
				$ent[$field] = $value;
			}
		}
		unset($ent);
		
		//列名配列を取得
		$clms=array_keys($data[0]);
		
		//データの先頭行に列名配列を挿入
		array_unshift($data,$clms);
		
		//CSVファイル名を作成
		$date = new \DateTime();
		$strDate=$date->format("Y-m-d");
		$fn='rich_menu_area'.$strDate.'.csv';
		
		//CSVダウンロード
		$this->csvOutput($fn, $data);

	}

	
	/**
	 * AJAX | 一覧のチェックボックス複数選択による一括処理
	 * @return string
	 */
	public function ajax_pwms(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$json_param=$_POST['key1'];
		
		$param=json_decode($json_param,true);//JSON文字を配列に戻す
		
		// IDリストを取得する
		$ids = $param['ids'];

		// アクション種別を取得する
		$kind_no = $param['kind_no'];

		// ユーザー情報を取得する
		$userInfo = $this->getUserInfo();

		$model = new RichMenuArea();
		
		// アクション種別ごとに処理を分岐
		switch ($kind_no){
			case 10:
				$model->switchDeleteFlg($ids, 0, $userInfo); // 有効化
				break;
			case 11:
				$model->switchDeleteFlg($ids, 1 ,$userInfo); // 削除化(無効化）
				break;
			default:
				return "'kind_no' is unknown value";
		}
		
		return 'success';
	}


}