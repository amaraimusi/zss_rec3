<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    	// 各シーダークラスを実行する順序で呼び出す
    	$this->call([
    			RichmenusTableSeeder::class,
    			RichmenuAreasTableSeeder::class,
    	]);
    }
}
