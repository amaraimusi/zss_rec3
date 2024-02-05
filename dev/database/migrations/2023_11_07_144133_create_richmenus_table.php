<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('richmenus', function (Blueprint $table) {
        	$table->ulid('id')->primary();
        	$table->string('line_account_id', 26)->comment('LINEアカウントID');
        	$table->integer('size_w')->comment('リッチメニューの横幅')->nullable();
        	$table->integer('size_h')->comment('リッチメニューの縦幅')->nullable();
        	$table->tinyInteger('default_selected')->default(0)->comment('デフォルト表示')->nullable();
        	$table->string('name', 300)->comment('リッチメニュー名');
        	$table->string('chat_bar_text')->comment('verchar(14)')->nullable();
        	$table->string('line_rich_menu_id', 26)->comment('LINE側のリッチメニューID(richMenuId)')->nullable();
        	$table->string('rich_menu_img', 256)->comment('リッチメニュー画像')->nullable();
        	$table->string('review_svg', 256)->comment('SVG画像')->nullable();
        	$table->string('start_time')->comment('期間・開始')->nullable();
        	$table->string('end_time')->comment('期間・終了')->nullable();
        	$table->string('segment', 256)->comment('セグメント')->nullable();
        	$table->string('rich_menu_category', 256)->comment('リッチメニュー・カテゴリ分類')->nullable();
        	$table->tinyInteger('delete_flg')->default(0)->comment('削除フラグ')->nullable();
        	
        	$table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('richmenus');
    }
};
