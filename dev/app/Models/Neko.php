<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CrudBase;


class Neko extends CrudBase
{
	protected $table = 'nekos'; // 紐づけるテーブル名
	
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
			'neko_val',
			'neko_name',
			'neko_date',
			'neko_type',
			'neko_dt',
			'neko_flg',
			'img_fn',
			'note',
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
				// CBBXS-6014
				'id' => [], // ID
				'neko_val' => [], // ネコ数値
				'neko_name' => [], // ネコ名
				'neko_date' => [], // ネコ日付
				'neko_type' => [ // 猫種別
					'outer_table' => 'neko_types',
					'outer_field' => 'neko_type_name', 
					'outer_list'=>'nekoTypeList',
				],
				'neko_dt' => [], // ネコ日時
				'neko_flg' => [
						'value_type'=>'flg',
				], // ネコフラグ
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
				// CBBXE
		];
		
		// フィールドデータへＤＢからのフィールド詳細情報を追加
		$fieldData = $this->addFieldDetailsFromDB($fieldData, 'nekos');
		
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
		$query = DB::table('nekos')->
			leftJoin('users', 'nekos.update_user_id', '=', 'users.id');
		
		$query = $query->select(
				'nekos.id as id',
				// CBBXS-6019
				'nekos.neko_val as neko_val',
				'nekos.neko_name as neko_name',
				'nekos.neko_date as neko_date',
				'nekos.neko_type as neko_type',
				'nekos.neko_dt as neko_dt',
				'nekos.neko_flg as neko_flg',
				'nekos.img_fn as img_fn',
				'nekos.note as note',
				// CBBXE
				'nekos.sort_no as sort_no',
				'nekos.delete_flg as delete_flg',
				'nekos.update_user_id as update_user_id',
				'users.nickname as update_user',
				'nekos.ip_addr as ip_addr',
				'nekos.created_at as created_at',
				'nekos.updated_at as updated_at',
	
				// CBBXE
			);
		
		// メイン検索
		if(!empty($searches['main_search'])){
			$concat = DB::raw("
					CONCAT( 
					/* CBBXS-6017 */
					IFNULL(nekos.neko_name, '') , 
					IFNULL(nekos.note, ''),
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
			$query = $query->where('nekos.id',$searches['id']);
		}
		
		// CBBXS-6024

		// ネコ数値・範囲1
		if(!empty($searches['neko_val1'])){
			$query = $query->where('nekos.neko_val', '>=', $searches['neko_val1']);
		}
		
		// ネコ数値・範囲2
		if(!empty($searches['neko_val2'])){
			$query = $query->where('nekos.neko_val', '<=', $searches['neko_val2']);
		}

		// ネコ名
		if(!empty($searches['neko_name'])){
			$query = $query->where('nekos.neko_name', 'LIKE', "%{$searches['neko_name']}%");
		}

		// ネコ日付・範囲1
		if(!empty($searches['neko_date1'])){
			$query = $query->where('nekos.neko_date', '>=', $searches['neko_date1']);
		}
		
		// ネコ日付・範囲2
		if(!empty($searches['neko_date2'])){
			$query = $query->where('nekos.neko_date', '<=', $searches['neko_date2']);
		}

		// 猫種別
		if(!empty($searches['neko_type'])){
			$query = $query->where('nekos.neko_type',$searches['neko_type']);
		}

		// ネコ日時
		if(!empty($searches['neko_dt'])){
			$query = $query->where('nekos.neko_dt', '>=', $searches['neko_dt']);
		}
		
		// ネコフラグ
		if(!empty($searches['neko_flg']) || $searches['neko_flg'] ==='0' || $searches['neko_flg'] ===0){
			if($searches['neko_flg'] != -1){
				$query = $query->where('nekos.neko_flg',$searches['neko_flg']);
			}
		}

		// 画像ファイル名
		if(!empty($searches['img_fn'])){
			$query = $query->where('nekos.img_fn', 'LIKE', "%{$searches['img_fn']}%");
		}

		// 備考
		if(!empty($searches['note'])){
			$query = $query->where('nekos.note', 'LIKE', "%{$searches['note']}%");
		}
		
		// CBBXE

		// 順番
		if(!empty($searches['sort_no'])){
			$query = $query->where('nekos.sort_no',$searches['sort_no']);
		}

		// 無効フラグ
		if(!empty($searches['delete_flg'])){
			$query = $query->where('nekos.delete_flg',$searches['delete_flg']);
		}else{
			$query = $query->where('nekos.delete_flg', 0);
		}

		// 更新者
		if(!empty($searches['update_user'])){
			$query = $query->where('users.nickname',$searches['update_user']);
		}

		// IPアドレス
		if(!empty($searches['ip_addr'])){
			$query = $query->where('nekos.ip_addr', 'LIKE', "%{$searches['ip_addr']}%");
		}

		// 生成日時
		if(!empty($searches['created_at'])){
			$query = $query->where('nekos.created_at', '>=', $searches['created_at']);
		}

		// 更新日
		if(!empty($searches['updated_at'])){
			$query = $query->where('nekos.updated_at', '>=', $searches['updated_at']);
		}
		
		return $query;
	}
	
	
	/**
	 * 次の順番を取得する
	 * @return int 順番
	 */
	public function nextSortNo(){
		$query = DB::table('nekos')->selectRaw('MAX(sort_no) AS max_sort_no');
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
	 *  ネコ種別リストを取得する
	 *  @return [] ネコ種別リスト
	 */
	public function getNekoTypeList(){
		
		$query = DB::table('neko_types')->
		   select(['id', 'neko_type_name'])->
		   where('delete_flg',0);
		
		$res = $query->get();
		$list = [];
		foreach($res as $ent){
			$list[$ent->id] = $ent->neko_type_name;
		}

		return $list;
	}
	// CBBXE
	
	

}

