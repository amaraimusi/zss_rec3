<?php

namespace App\Http\Controllers;

use App\Consts\crud_base_function;
use Illuminate\Http\Request;
use App\Models\LineDemo;
use CrudBase\CrudBase;
use App\Consts\ConstCrudBase;

/**
 * LINEデモ
 * @since 2024-1-29
 * @version 1.0.0
 * @author amaraimusi
 *
 */
class LineDemoController extends CrudBaseController{

	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request){

		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
        
		return view('line_demo.index', []);
		
	}
	
	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function audience(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		
		return view('line_demo.audience', []);
		
	}
	
	
	public function audience_list(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$json=$_POST['key1'];
		$param = json_decode($json, true);
		
		$accessToken = $param['access_token']; // LINEのアクセストークン
		$url = 'https://api.line.me/v2/bot/audienceGroup/list';
		
		$headers = [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json'
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		$audienceData=json_decode($response, true);//JSONデコード
		$res = ['audienceData' => $audienceData];

		$json = json_encode($res, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		return $json;
		
	}
	

	public function audience_reg(){

		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');

		$json=$_POST['key1'];
		
		$param = json_decode($json, true);
		

		$description = $param['description']; // オーディエンス名
		$isIfaAudience = $param['isIfaAudience']; // IFAフラグ
		$uploadDescription = $param['uploadDescription']; // ジョブ説明
		$audiences = $param['audiences']; // ユーザー名リスト

		
		
		$accessToken = $param['access_token']; // LINEのアクセストークン
		$url = 'https://api.line.me/v2/bot/audienceGroup/upload';
		
		$headers = [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json'
		];
		
		$data = [
				'description' => $description,
				'isIfaAudience' => $isIfaAudience,
				'audiences' => [
// 						['id' => 'USER_ID_1'],
// 						['id' => 'USER_ID_2'],
// 						// 他のユーザーIDを追加...
				],
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		dump($response);//■■■□□□■■■□□□)

		$json = json_encode($response, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		
		return $json;
	}
	

}