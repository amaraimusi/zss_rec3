<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CrudBase;


class NekoType extends CrudBase
{
	protected $table = 'neko_type_types'; // 紐づけるテーブル名
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	
	/**
	 * The attributes that are mass assignable.
	 * DB保存時、ここで定義してあるDBフィールドのみ保存対象にします。
	 * ここの存在しないDBフィールドは保存対象外になりますのでご注意ください。
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
			// CBBXS-3009
		'id',
		'neko_type_name',
		'sort_no',
		'delete_flg',
		'update_user_id',
		'ip_addr',
		'created_at',
		'updated_at',

			// CBBXE
	];
	
	
	public function __construct(){
		parent::__construct();
		
	}
	
	
	/**
	 * フィールドデータを取得する
	 * @return [] $fieldData フィールドデータ
	 */
	public function getFieldData(){
		$fieldData = [
				'id' => [], // ID
				'neko_type_val' => [], // ネコ種別数値
				'neko_type_name' => [], // ネコ種別名
				'neko_type_date' => [], // ネコ種別日付
				'neko_type_type' => [ // 猫種別
					'outer_table' => 'neko_type_types',
					'outer_field' => 'neko_type_type_name', 
					'outer_list'=>'neko_typeTypeList',
				],
				'neko_type_dt' => [], // ネコ種別日時
				'neko_type_flg' => [
						'value_type'=>'flg',
				], // ネコ種別フラグ
				'img_fn' => [], // 画像ファイル名
				'note' => [], // 備考
				'sort_no' => [], // 順番
				'delete_flg' => [
						'value_type'=>'delete_flg',
				], // 無効フラグ
				'update_user_id' => [], // 更新ユーザーID
				'ip_addr' => [], // IPアドレス
				'created_at' => [], // 生成日時
				'updated_at' => [], // 更新日
				'update_user' => [], // 更新者
		];
		
		// フィールドデータへＤＢからのフィールド詳細情報を追加
		$fieldData = $this->addFieldDetailsFromDB($fieldData, 'neko_type_types');
		
		// フィールドデータに登録対象フラグを追加します。
		$fieldData = $this->addRegFlgToFieldData($fieldData, $this->fillable);

		return $fieldData;
	}
	
	
	/**
	 * DBから一覧データを取得する
	 * @param [] $searches 検索データ
	 * @param [] $param
	 *     - string use_type 用途タイプ 　index:一覧データ用（デフォルト）, csv:CSVダウンロード用
	 *     - int def_per_page  デフォルト制限行数
	 * @return [] 一覧データ
	 */
	public function getData($searches, $param=[]){
		
		$use_type = $param['use_type'] ?? 'index';
		$def_per_page = $param['def_per_page'] ?? 50;
		
		// 一覧データを取得するSQLの組立。
		$query = DB::table('neko_type_types')->
			leftJoin('users', 'neko_type_types.update_user_id', '=', 'users.id');
		
		$query = $query->select(
			// CBBXS-3034
			'neko_types.id as id',
			'neko_types.neko_type_name as neko_type_name',
			'neko_types.sort_no as sort_no',
			'neko_types.delete_flg as delete_flg',
		    'neko_types.update_user_id as update_user_id',
		    'users.nickname as update_user',
			'neko_types.ip_addr as ip_addr',
			'neko_types.created_at as created_at',
			'neko_types.updated_at as updated_at',

			// CBBXE
			);
		
		// メイン検索
		if(!empty($searches['main_search'])){
			$concat = DB::raw("CONCAT( IFNULL(neko_type_types.neko_type_name, '') , IFNULL(neko_type_types.note, '') ) ");
			$query = $query->where($concat, 'LIKE', "%{$searches['main_search']}%");
		}
		
		$query = $this->addWheres($query, $searches); // 詳細検索情報をクエリビルダにセットする
		
		$sort_field = $searches['sort'] ?? 'sort_no'; // 並びフィールド
		$dire = 'asc'; // 並び向き
		if(!empty($searches['desc'])){
			$dire = 'desc';
		}
		$query = $query->orderBy($sort_field, $dire);
		
		// 一覧用のデータ取得。ページネーションを考慮している。
		if($use_type == 'index'){
			
			$per_page = $searches['per_page'] ?? $def_per_page; // 行制限数(一覧の最大行数) デフォルトは50行まで。
			$data = $query->paginate($per_page);
			
			return $data;
			
		}
		
		// CSV用の出力。Limitなし
		elseif($use_type == 'csv'){
			$data = $query->get();
			$data2 = [];
			foreach($data as $ent){
				$data2[] = (array)$ent;
			}
			return $data2;
		}
		
		
	}
	
	/**
	 * 詳細検索情報をクエリビルダにセットする
	 * @param object $query クエリビルダ
	 * @param [] $searches　検索データ
	 * @return object $query クエリビルダ
	 */
	private function addWheres($query, $searches){
		
		// CBBXS-3003

	    // id
	    if(!empty($searches['id'])){
	        $query = $query->where('neko_types.id',$searches['id']);
	    }

	    // ネコ種別
	    if(!empty($searches['neko_type_name'])){
	        $query = $query->where('neko_types.neko_type_name', 'LIKE', "%{$searches['neko_type_name']}%");
	    }

	    // 順番
	    if(!empty($searches['sort_no'])){
	        $query = $query->where('neko_types.sort_no',$searches['sort_no']);
	    }

	    // 無効フラグ
	    if(!empty($searches['delete_flg'])){
	        $query = $query->where('neko_types.delete_flg',$searches['delete_flg']);
	    }else{
	        $query = $query->where('neko_types.delete_flg', 0);
	    }

	    // 更新者
	    if(!empty($searches['update_user'])){
	        $query = $query->where('users.nickname',$searches['update_user']);
	    }

	    // IPアドレス
	    if(!empty($searches['ip_addr'])){
	        $query = $query->where('neko_types.ip_addr', 'LIKE', "%{$searches['ip_addr']}%");
	    }

	    // 生成日時
	    if(!empty($searches['created_at'])){
	        $query = $query->where('neko_types.created_at',$searches['created_at']);
	    }

	    // 更新日
	    if(!empty($searches['updated_at'])){
	        $query = $query->where('neko_types.updated_at',$searches['updated_at']);
	    }

		// CBBXE
		
		return $query;
	}
	
	
	/**
	 * 次の順番を取得する
	 * @return int 順番
	 */
	public function nextSortNo(){
		$query = DB::table('neko_type_types')->selectRaw('MAX(sort_no) AS max_sort_no');
		$res = $query->first();
		$sort_no = $res->max_sort_no ?? 0;
		$sort_no++;
		
		return $sort_no;
	}
	
	
	/**
	 * エンティティのDB保存
	 * @note エンティティのidが空ならINSERT, 空でないならUPDATEになる。
	 * @param [] $ent エンティティ
	 * @return [] エンティティ(insertされた場合、新idがセットされている）
	 */
	public function saveEntity(&$ent){
		
		if(empty($ent['id'])){
			
			// ▽ idが空であればINSERTをする。
			$ent = array_intersect_key($ent, array_flip($this->fillable)); // ホワイトリストによるフィルタリング
			$id = $this->insertGetId($ent); // INSERT
			$ent['id'] = $id;
		}else{
			
			// ▽ idが空でなければUPDATEする。
			$ent = array_intersect_key($ent, array_flip($this->fillable)); // ホワイトリストによるフィルタリング
			$this->updateOrCreate(['id'=>$ent['id']], $ent); // UPDATE
		}
		
		return $ent;
	}
	
	
	/**
	 * データのDB保存
	 * @param [] $data データ（エンティティの配列）
	 * @return [] データ(insertされた場合、新idがセットされている）
	 */
	public function saveAll(&$data){
		
		$data2 = [];
		foreach($data as &$ent){
			$data2[] = $this->saveEntity($ent);
			
		}
		unset($ent);
		return $data2;
	}
	
	
	/**
	 * 削除フラグを切り替える
	 * @param array $ids IDリスト
	 * @param int $delete_flg 削除フラグ   0:有効  , 1:削除
	 * @param [] $userInfo ユーザー情報
	 */
	public function switchDeleteFlg($ids, $delete_flg, $userInfo){
		
		// IDリストと削除フラグからデータを作成する
		$data = [];
		foreach($ids as $id){
			$ent = [
					'id' => $id,
					'delete_flg' => $delete_flg,
			];
			$data[] = $ent;
			
		}
		
		// 更新ユーザーなど共通フィールドをデータにセットする。
		$data = $this->setCommonToData($data, $userInfo);

		// データを更新する
		$rs = $this->saveAll($data);
		
		return $rs;
		
	}
	
	
	// CBBXS-3021

	// CBBXE
	
	

}

