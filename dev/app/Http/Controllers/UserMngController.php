<?php

namespace App\Http\Controllers;

use App\Consts\crud_base_function;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use CrudBase\CrudBase;
use App\Models\UserMng;
use App\Consts\ConstCrudBase;

/**
 * ユーザー管理管理画面
 * @since 2023-9-28
 * @version 1.0.0
 * @author amaraimusi
 *
 */
class UserMngController extends CrudBaseController{
	
	// 画面のバージョン → 開発者はこの画面を修正したらバージョンを変更すること。バージョンを変更するとキャッシュやセッションのクリアが自動的に行われます。
	public $this_page_version = '1.0.0';
	
	private $def_sort = 'sort_no'; // デフォルトソートフィールド
	private $def_desc = 0; // デフォールトソート向き 0:昇順, 1:降順
	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request){

		// ログアウトになっていたらログイン画面にリダイレクト
		 if(\Auth::id() == null) return redirect('login');
		 
		 $userInfo = \Auth::user();
		 $role = $userInfo->role; // ログインユーザーの権限

		// 検索データのバリデーション
		$validated = $request->validate([
			'id' => 'nullable|numeric',
			'per_page' => 'nullable|numeric',
		]);
		
		$sesSearches = session('user_mng_searches_key');// セッションからセッション検索データを受け取る

		// 新バージョンチェック  0:バージョン変更なし（通常）, 1:新しいバージョン
		$new_version = $this->judgeNewVersion($sesSearches, $this->this_page_version);

		$searches = []; // 検索データ
		
		// リクエストのパラメータが空でない、または新バージョンフラグがONである場合、リクエストから検索データを受け取る
		if(!empty($request->all()) || $new_version == 1){
			$searches = [
					'main_search' => $request->main_search, // メイン検索
				
					'id' => $request->id, // id
					
					// CBBXS-6000
					'name' => $request->name, // ユーザー/アカウント名
					'email' => $request->email, // メールアドレス
					'nickname' => $request->nickname, // 名前
					'password' => $request->password, // パスワード
					'role' => $request->role, // 権限

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
		
		// デフォルトソート情報をセットする
		if($searches['sort'] === null) $searches['sort'] = $this->def_sort;
		if($searches['desc'] === null) $searches['desc'] = $this->def_desc;

		$searches['this_page_version'] = $this->this_page_version; // 画面バージョン
		$searches['new_version'] = $new_version; // 新バージョンフラグ
		session(['user_mng_searches_key' => $searches]); // セッションに検索データを書き込む

		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		$def_per_page = 20; // デフォルト制限行数
		
		$model = new UserMng();
		$roleList = $model->getRoleList($role); // 権限リスト
		$fieldData = $model->getFieldData();
		$listData = $model->getData($searches, ['def_per_page' => $def_per_page, 'roleList' => $roleList]);
		$data_count = $listData->total(); //　LIMIT制限を受けていないデータ件数
		
		// パスワードは空にしておく。
		foreach($listData as &$rEnt){
			$rEnt->password = '';
		}
		
		$data = [];
		foreach($listData as $rEnt){
			$data[] = (array)$rEnt;
		}

		
		$crudBaseData = [
				'data' => $data,
				'data_count'=>$data_count,
				'searches'=>$searches,
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'fieldData'=>$fieldData,
				'model_name_c'=>'UserMng', // モデル名（キャメル記法）
				'model_name_s'=>'user_mng', // モデル名（スネーク記法）
				'def_per_page'=>$def_per_page, // デフォルト制限行数
				'this_page_version'=>$this->this_page_version,
				'new_version' => $new_version,
				
				// CBBXS-6002
				'roleList'=>$roleList, // 権限リスト

				// CBBXE
		];
        
		return view('user_mng.index', [
			    'listData'=>$listData,
			    'searches'=>$searches,
				'userInfo'=>$userInfo,
				'fieldData'=>$fieldData,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData'=>$crudBaseData,
			    
			    // CBBXS-6003
				'roleList'=>$roleList, // 権限リスト

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
		$dbFieldData = $this->getDbFieldData('users');
		
		// 値が空であればデフォルトをセットします。
		$ent = $this->setDefalutToEmpty($ent, $dbFieldData);
		
		// モデルを生成します。 新規入力アクションは真っ新なモデルを生成しますが、編集更新アクションの場合は、行データが格納されたモデルを生成します。
		$model = empty($id) ? new UserMng() : UserMng::find($id);
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		
		

		// CBBXS-6004
		$model->name = $ent['name']; // ユーザー/アカウント名
		$model->email = $ent['email']; // メールアドレス
		$model->nickname = $ent['nickname']; // 名前
		$model->role = $ent['role']; // 権限

		// CBBXE
		
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];
		$model->updated_at = date('Y-m-d H:i:s');
		
		if(!empty($request->password)){
			$user_mng->password = \Hash::make($request->password); // パスワードをハッシュ化する。
		}
		
		if(empty($id)){
			$model->sort_no =$this->getNextSortNo('users', 'asc');
			$model->password = \Hash::make($ent['password']); // パスワードをハッシュ化する。
			
			$model->save(); // DBへ新規追加: 同時に$modelに新規追加した行のidがセットされる。
			$ent['id'] = $model->id;
		}else{
			
			if(!empty($ent['password'])){
				$model->password = \Hash::make($ent['password']); // パスワードをハッシュ化する。
			}
			
			$model->update(); // DB更新
		}
		
		// CBBXS-6005

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
		
		$model = new UserMng();
		
		$copy_id = $request->id; // 複製元のid。空なら普通の新規入力になる
		
		$ent = $model->find($copy_id); // 複製元のエンティティを取得
		
		// 複製元のエンティティが空であれば、通常の新規入力になる。新規入力のデフォルト値をセットする。
		if($ent==null){
			$ent = $model->get();
			// CBBXS-6006
			$ent->name= '';
			$ent->email= '';
			$ent->nickname= '';
			$ent->password= '';
			$ent->role= '';
			$ent->sort_no= '';
			$ent->delete_flg= '0';
			$ent->update_user_id= '';
			$ent->ip_addr= '';
			$ent->created_at= '';
			$ent->updated_at= '';

			// CBBXE
		}
		
		if($ent->user_mng_dt == '0000-00-00 00:00:00') $ent->user_mng_dt = '';
		
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		// CBBXS-3037
		$roleList = $model->getRoleList(); // 権限

		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent->toArray(),
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'user_mngTypeList'=>$user_mngTypeList,
		];
		
		return view('user_mng.create', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData' => $crudBaseData,
				
		    	// CBBXS-6010
		    	'roleList'=>$roleList,

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
	        'name' => 'nullable|max:255',
	        'email' => 'nullable|max:255',
	        'nickname' => 'nullable|max:50',
			'password' =>['required', Password::min(8)],
			'sort_no' => 'nullable|numeric',
			'update_user_id' => 'nullable|numeric',
	        'ip_addr' => 'nullable|max:40',

			// CBBXE
		]);
		
		
		$model = new UserMng();
		// CBBXS-6032
		$model->name = $request->name; // ユーザー/アカウント名
		$model->email = $request->email; // メールアドレス
		$model->nickname = $request->nickname; // 名前
		$model->password = $request->password; // パスワード
		$model->role = $request->role; // 権限

		// CBBXE
		
		$model->sort_no = $model->nextSortNo();
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];
		
		$user_mng->password = \Hash::make($user_mng->password); // パスワードをハッシュ化する。
		
		$model->save(); // DBへ新規追加と同時に$modelに新規追加した行のidがセットされる。

		// ▼ ファイルアップロード関連
		$fileUploadK = CrudBase::factoryFileUploadK();
		$ent = $model->toArray();
		$ent['img_fn_exist'] = $request->img_fn_exist; // 既存・画像ファイル名 img_fnの付属パラメータ
		$model->img_fn = $fileUploadK->uploadForLaravelMpa('user_mng', $_FILES,  $ent, 'img_fn', 'img_fn_exist');

		$model->update(); // ファイル名をモデルにセットしたのでモデルをDB更新する。
		
		return redirect('/user_mng');
		
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
		
		$model = new UserMng();
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		$id = $request->id;
		if(!is_numeric($id)){
			echo 'invalid access';
			die;
		}
		
		$ent = UserMng::find($id);
		
		// CBBXS-6037
		$roleList = $model->getRoleTypeList(); // 権限リスト

		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'user_mngTypeList'=>$user_mngTypeList,
		];
		

		return view('user_mng.show', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'user_mngTypeList'=>$user_mngTypeList,
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

		$model = new UserMng();
		$userInfo = $this->getUserInfo(); // ログインユーザーのユーザー情報を取得する
		$paths = $this->getPaths(); // パス情報を取得する
		
		$id = $request->id;
		if(!is_numeric($id)){
			echo 'invalid access';
			die;
		}
	
		$ent = UserMng::find($id);
		
		// CBBXS-6068

		// CBBXE
		
		$crudBaseData = [
				'ent'=>$ent->toArray(),
				'userInfo'=>$userInfo,
				'paths'=>$paths,
				'this_page_version'=>$this->this_page_version,
				'user_mngTypeList'=>$user_mngTypeList,
		];
		
		return view('user_mng.edit', [
				'ent'=>$ent,
				'userInfo'=>$userInfo,
				'this_page_version'=>$this->this_page_version,
				'crudBaseData'=>$crudBaseData,
				
			    // CBBXS-6039
			    'roleList'=>$roleList,

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
	        'name' => 'nullable|max:255',
	        'email' => 'nullable|max:255',
	        'nickname' => 'nullable|max:50',
			'password' =>['required', Password::min(8)],
			'sort_no' => 'nullable|numeric',
			'update_user_id' => 'nullable|numeric',
	        'ip_addr' => 'nullable|max:40',

			// CBBXE
		]);
		
		$model = UserMng::find($request->id);

		$model->id = $request->id;
		
		// CBBXS-6033
		$model->name = $request->name; // ユーザー/アカウント名
		$model->email = $request->email; // メールアドレス
		$model->nickname = $request->nickname; // 名前
		$model->password = $request->password; // パスワード
		$model->role = $request->role; // 権限
		// CBBXE
		
		$model->delete_flg = 0;
		$model->update_user_id = $userInfo['id'];
		$model->ip_addr = $userInfo['ip_addr'];

		// ▼ ファイルアップロード関連
		$fileUploadK = CrudBase::factoryFileUploadK();
		$ent = $model->toArray();
		$ent['img_fn_exist'] = $request->img_fn_exist; // 既存・画像ファイル名 img_fnの付属パラメータ
		$model->img_fn = $fileUploadK->uploadForLaravelMpa('user_mng', $_FILES,  $ent, 'img_fn', 'img_fn_exist');

 		$model->update(); // DB更新
		
		return redirect('/user_mng');
		
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

		$model = UserMng::find($id);
		
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
		
		$model = new UserMng();
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
		
		foreach($data as &$ent){
			unset($ent['password']);
		}
		unset($ent);

		$model = new UserMng();
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

		$searches = session('user_mng_searches_key');// セッションからセッション検索データを受け取る

		$model = new UserMng();
		$data = $model->getData($searches, ['use_type'=>'csv'] );
		
		// データ件数が0件ならCSVダウンロードを中断し、一覧画面にリダイレクトする。
		$count = count($data);
		if($count == 0){
			return redirect('/user_mng');
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
		$fn='user_mng'.$strDate.'.csv';
		
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

		$model = new UserMng();
		
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