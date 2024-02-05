<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RichmenuAreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	
    	// 既存のデータをクリアする
    	DB::table('richmenu_areas')->truncate();
    	
    	$actionTypes = [
    			'postback', // ポストバックアクション
    			'message', // メッセージアクション
    			'uri', // URIアクション
    			'datetimepicker', // 日時選択アクション
    			'camera', // カメラアクション
    			'cameraRoll', // カメラロールアクション
    			'location', // 位置情報アクション
    			'richmenuswitch', // リッチメニュー切替アクション
    			
    	]; 
    	$actionLabels = [
    			'Labelネズミ',
    			'Labelウシ',
    			'Labelトラ',
    			'Labelウサギ',
    			'Labelサル',
    			'Labelイノシシ',
    			'Labelヘビ',
    			
    	]; 

    	$res = DB::select('SELECT id FROM richmenus');

    	$richmenuIds = [];
    	foreach($res as $ent){
    		$richmenuIds[] = $ent->id;
    	}

    	for ($i = 0; $i < 30; $i++) {
    		DB::table('richmenu_areas')->insert([
    				'id' => 'RA' . $i,
    				'richmenu_id' => $richmenuIds[array_rand($richmenuIds, 1)], // ランダムなrichmenu_idを取得
    				'bounds_x' => rand(0, 800),
    				'bounds_y' => rand(0, 1000),
    				'bounds_width' => rand(800, 2500),
    				'bounds_height' => rand(250, 1000),
    				'action_type' => $actionTypes[array_rand($actionTypes)],
    				'action_label' => $actionLabels[array_rand($actionLabels)],
    				'action_json' => json_encode(["type" => "text", "text" => "Sample Message"]), // 適宜変更してください。
    				'delete_flg' => 0,
    				'created_at' => now(),
    				'updated_at' => now(),
    		]);
    	}
    }
}
