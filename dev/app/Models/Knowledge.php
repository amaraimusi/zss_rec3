<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CrudBase;


class Knowledge extends CrudBase
{
	protected $table = 'knowledges'; // 紐づけるテーブル名
	
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
			// CBBXS-6009
			'id',
			'kl_text',
			'xid',
			'kl_category',
			'contents_url',
			'doc_name',
			'doc_text',
			'dtm',
			'next_dtm',
			'level',
			'sort_no',
			'delete_flg',
			'update_user_id',
			'update_user',
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
				// CBBXS-6014
				'id' => [], // ID
				'kl_text' => [], // 心得テキスト
				'xid' => [], // XID
				'kl_category' => [ // 猫種別
					'outer_table' => 'kl_categorys',
					'outer_field' => 'kl_category_name', 
					'outer_list'=>'klCategoryList',
				],
				'contents_url' => [], // 内容URL
				'doc_name' => [], // 文献名
				'doc_text' => [], // 文献テキスト
				'dtm' => [], // 学習日時
				'next_dtm' => [], // 次回日時
				'level' => [], // 学習レベル
				'sort_no' => [], // 順番
				'delete_flg' => [
						'value_type'=>'delete_flg',
				], // 削除フラグ
				'update_user_id' => [], // 更新者
				'update_user' => [], // 更新ユーザー
				'ip_addr' => [], // IPアドレス
				'created_at' => [], // 生成日時
				'updated_at' => [], // 更新日時

				// CBBXE
		];
		
		// フィールドデータへＤＢからのフィールド詳細情報を追加
		$fieldData = $this->addFieldDetailsFromDB($fieldData, 'knowledges');
		
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
		$query = DB::table('knowledges')->
			leftJoin('users', 'knowledges.update_user_id', '=', 'users.id');
		
		$query = $query->select(
				'knowledges.id as id',
				// CBBXS-6019
				'knowledges.id as id',
				'knowledges.kl_text as kl_text',
				'knowledges.xid as xid',
				'knowledges.kl_category as kl_category',
				'knowledges.contents_url as contents_url',
				'knowledges.doc_name as doc_name',
				'knowledges.doc_text as doc_text',
				'knowledges.dtm as dtm',
				'knowledges.next_dtm as next_dtm',
				'knowledges.level as level',

				// CBBXE
				'knowledges.sort_no as sort_no',
				'knowledges.delete_flg as delete_flg',
				'knowledges.update_user_id as update_user_id',
				'users.nickname as update_user',
				'knowledges.ip_addr as ip_addr',
				'knowledges.created_at as created_at',
				'knowledges.updated_at as updated_at',
	
				// CBBXE
			);
		
		// メイン検索
		if(!empty($searches['main_search'])){
			$concat = DB::raw("
					CONCAT( 
					/* CBBXS-6017 */
					IFNULL(knowledges.kl_text, '') , 
					IFNULL(knowledges.contents_url, '') , 
					IFNULL(knowledges.doc_name, '') , 
					IFNULL(knowledges.doc_text, '') , 
					IFNULL(knowledges.update_user, '') , 
					IFNULL(knowledges.ip_addr, '') , 

					/* CBBXE */
					''
					 ) ");
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

		// id
		if(!empty($searches['id'])){
			$query = $query->where('knowledges.id',$searches['id']);
		}
		
		// CBBXS-6024
		// ID
		if(!empty($searches['id'])){
			$query = $query->where('id.id',$searches['id']);
		}

		// 心得テキスト
		if(!empty($searches['kl_text'])){
			$query = $query->where('knowledges.kl_text', 'LIKE', "%{$searches['kl_text']}%");
		}

		// XID
		if(!empty($searches['xid'])){
			$query = $query->where('xid.xid',$searches['xid']);
		}

		// カテゴリ
		if(!empty($searches['kl_category'])){
			$query = $query->where('kl_category.kl_category',$searches['kl_category']);
		}

		// 内容URL
		if(!empty($searches['contents_url'])){
			$query = $query->where('knowledges.contents_url', 'LIKE', "%{$searches['contents_url']}%");
		}

		// 文献名
		if(!empty($searches['doc_name'])){
			$query = $query->where('knowledges.doc_name', 'LIKE', "%{$searches['doc_name']}%");
		}

		// 文献テキスト
		if(!empty($searches['doc_text'])){
			$query = $query->where('knowledges.doc_text', 'LIKE', "%{$searches['doc_text']}%");
		}

		// 学習日時
		if(!empty($searches['dtm'])){
			$query = $query->where('knowledges.dtm', '>=', $searches['dtm']);
		}

		// 次回日時
		if(!empty($searches['next_dtm'])){
			$query = $query->where('knowledges.next_dtm', '>=', $searches['next_dtm']);
		}

		// 学習レベル
		if(!empty($searches['level'])){
			$query = $query->where('level.level',$searches['level']);
		}

		// 順番
		if(!empty($searches['sort_no'])){
			$query = $query->where('sort_no.sort_no',$searches['sort_no']);
		}

		// 削除フラグ
		if(!empty($searches['delete_flg']) || $searches['delete_flg'] ==='0' || $searches['delete_flg'] ===0){
			if($searches['delete_flg'] != -1){
				$query = $query->where('knowledges.delete_flg',$searches['delete_flg']);
			}
		}

		// 更新者
		if(!empty($searches['update_user_id'])){
			$query = $query->where('update_user_id.update_user_id',$searches['update_user_id']);
		}

		// 更新ユーザー
		if(!empty($searches['update_user'])){
			$query = $query->where('knowledges.update_user', 'LIKE', "%{$searches['update_user']}%");
		}

		// IPアドレス
		if(!empty($searches['ip_addr'])){
			$query = $query->where('knowledges.ip_addr', 'LIKE', "%{$searches['ip_addr']}%");
		}

		// 生成日時
		if(!empty($searches['created_at'])){
			$query = $query->where('knowledges.created_at', '>=', $searches['created_at']);
		}

		// 更新日時
		if(!empty($searches['updated_at'])){
			$query = $query->where('knowledges.updated_at', '>=', $searches['updated_at']);
		}


		// CBBXE

		// 順番
		if(!empty($searches['sort_no'])){
			$query = $query->where('knowledges.sort_no',$searches['sort_no']);
		}

		// 無効フラグ
		if(!empty($searches['delete_flg'])){
			$query = $query->where('knowledges.delete_flg',$searches['delete_flg']);
		}else{
			$query = $query->where('knowledges.delete_flg', 0);
		}

		// 更新者
		if(!empty($searches['update_user'])){
			$query = $query->where('users.nickname',$searches['update_user']);
		}

		// IPアドレス
		if(!empty($searches['ip_addr'])){
			$query = $query->where('knowledges.ip_addr', 'LIKE', "%{$searches['ip_addr']}%");
		}

		// 生成日時
		if(!empty($searches['created_at'])){
			$query = $query->where('knowledges.created_at', '>=', $searches['created_at']);
		}

		// 更新日
		if(!empty($searches['updated_at'])){
			$query = $query->where('knowledges.updated_at', '>=', $searches['updated_at']);
		}
		
		return $query;
	}
	
	
	/**
	 * 次の順番を取得する
	 * @return int 順番
	 */
	public function nextSortNo(){
		$query = DB::table('knowledges')->selectRaw('MAX(sort_no) AS max_sort_no');
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
	
	
	// CBBXS-6029
	/**
	 *  カテゴリリストを取得する
	 *  @return [] カテゴリリスト
	 */
	public function getKlCategoryList(){
		
		$query = DB::table('kl_categorys')->
		   select(['id', 'kl_category_name'])->
		   where('delete_flg',0);
		
		$res = $query->get();
		$list = [];
		foreach($res as $ent){
			$list[$ent->id] = $ent->kl_category_name;
		}

		return $list;
	}

	// CBBXE
	
	

}

