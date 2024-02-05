<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RichmenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	
    	// 既存のデータをクリアする
    	DB::table('richmenus')->truncate();
    	
    	
    	$sampleData = [];
    	for ($i = 0; $i < 12; $i++) {
    		$sampleData[] = [
    				'id'                => 'RM' . $i,
    				'line_account_id'   => '01gz18jmtaz1tz2yewmg0qh62d',
    				'size_w'            => 2500,
    				'size_h'            => 1686,
    				'default_selected'  => rand(0, 1),
    				'name'              => 'Sample Rich Menu ' . $i,
    				'chat_bar_text'     => 'Chat with us!',
    				'line_rich_menu_id' => Str::random(26),
    				'rich_menu_img'     => 'https://example.com/img/' . $i . '.jpg',
    				'review_svg'        => 'https://example.com/svg/' . $i . '.svg',
    				'start_time'        => now()->toDateTimeString(),
    				'end_time'          => now()->addDays(rand(1, 30))->toDateTimeString(),
    				'segment'           => null, // 適宜必要な値に設定してください。
    				'rich_menu_category'=> null, // 適宜必要な値に設定してください。
    				'delete_flg'        => 0,
    				'created_at'        => now(),
    				'updated_at'        => now(),
    		];
    	}
    	
    	DB::table('richmenus')->insert($sampleData);
    }
    

    
}
