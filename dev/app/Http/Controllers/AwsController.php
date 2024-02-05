<?php

namespace App\Http\Controllers;

use App\Consts\crud_base_function;
use Illuminate\Http\Request;
use App\Models\RichMenuArea;
use CrudBase\CrudBase;
use App\Consts\ConstCrudBase;
use Illuminate\Http\File;
use Storage;

/**
 * Awsテスト
 * @since 2023-9-28
 * @version 1.0.0
 * @author amaraimusi
 *
 */
class AwsController extends CrudBaseController{
	
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
		
		$disk = Storage::disk('s3');
		
		// ファイルの存在チェック
		if ($request->hasFile('datafile'))
		{
			
			// S3にファイルを保存し、保存したファイル名を取得する
			$fileName = $disk->put('uploads', $request->file('datafile'));
			
			// $fileNameには
			// https://saitobucket3.s3.amazonaws.com/uhgKiZeJXMFhL9Vr7yT7XvlJqonPNx30xbJYoEo0.jpeg
			// のような画像へのフルパスが格納されている
			// このフルパスをDBに格納しておくと、画像を表示させるのは簡単になる
			dd($disk->url($fileName));
		}
		
		
		// S3のディスクを指定
		$disk = Storage::disk('s3');
		$localFilePath = "rsc/img/aws/img1.jpg"; // ローカルのファイルパス
		
		// ファイルが存在するかチェック
		if (file_exists($localFilePath)) {
			dump("ファイルが存在します。");
			
			// ファイルの内容を読み込む
			$fileContents = file_get_contents($localFilePath);
			
			$res = $disk->put('uploads/rich_menu/img1.jpg', $fileContents, 'public');
			if(!$res){
				$res = $disk->put('uploads/rich_menu/img1.jpg', $fileContents);
			}
			
			if($res){
				dump('putでファイル配置に成功しました');
			} else {
				dump('失敗');
			}
		} else {
			dump("ファイルが存在しません。");
		}
		
		
// 		$files = $disk->files('uploads/rich_menu/');
// 		dump('// S3バケットからファイルのリストを取得');//■■■□□□■■■□□□
// 		dump($files);//■■■□□□■■■□□□)
		
// 		// 指定されたディレクトリ内のサブディレクトリ一覧を取得
// 		$directories = $disk->directories('uploads');
// 		dump('ディレクトリ一覧');//■■■□□□■■■□□□
// 		dump($directories);//■■■□□□■■■□□□)
		
		
		
        
		return view('aws.index', []);
		
	}
	
	public function test2(Request $request){
		
		
		// メモリの使用量を確認
		$mem = memory_get_usage();
		$memoryLimit = ini_get('memory_limit');//■■■□□□■■■□□□
		
		dump("メモリ：{$mem} / {$memoryLimit}");//■■■□□□■■■□□□)
		
		
		$disk = Storage::disk('s3');
		$imagePath = 'uploads/img2.jpg';
		
		$url = $disk->url($imagePath);
		dump($url);//■■■□□□■■■□□□)
		
		$contents = $disk->get($imagePath);
		file_put_contents($imagePath, $contents);
// 		Storage::disk('local')->put($imagePath, $image);
// 		dump('xxx');//■■■□□□■■■□□□)

		$mem = memory_get_usage();
		dump("メモリ：{$mem} / {$memoryLimit}");//■■■□□□■■■□□□)
		
		
		//$disk = Storage::disk('s3');
// 		$url = $disk->url($fp);
// 		dump($url);//■■■□□□■■■□□□)
		
		
// 		$disk = Storage::disk('s3');
// 		$fp = 'rich_menu/01HKQE8BV5N7MPWNAZ3Z71G0W5.jpg';
// 		$url = $disk->url($fp);
// 		dump($url);//■■■□□□■■■□□□)
		
		
		
		
		
// 		$del_fp = 'rich_menu/01HKQE8BV5N7MPWNAZ3Z71G0W5_thum.jpg';
// 		$disk->delete($del_fp);
// 		dump('ファイルを削除しました。');//■■■□□□■■■□□□)
		
		
		
// 		// S3上のファイルパス
// 		$s3FilePath = 'uploads/rich_menu/img1.jpg';
		
// 		// ローカルに保存するパス(strage側に出力される）
// 		$localFilePath = 'rsc/img/aws/tmp/img_b2.jpg';
		
// 		// S3からファイルが存在するか確認
// 		if ($disk->exists($s3FilePath)) {
// 			// S3からファイルの内容を取得
// 			$fileContents = $disk->get($s3FilePath);
			
// 			// ローカルファイルシステムにファイルを保存
// 			Storage::put($localFilePath, $fileContents);
			
// 			dump( "ファイルをダウンロードしてローカルに保存しました。");
// 		} else {
// 			dump( "指定されたファイルはS3に存在しません。");
// 		}
		
		
		return view('aws.test2', []);
	}
	
	


}