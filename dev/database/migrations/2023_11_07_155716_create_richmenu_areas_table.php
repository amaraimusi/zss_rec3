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
        Schema::create('richmenu_areas', function (Blueprint $table) {
        	$table->ulid('id')->primary();
        	$table->string('richmenu_id', 26)->comment('リッチメニューID（LINE側のリッチメニューIDではない）')->nullable();
        	$table->integer('bounds_x')->comment('矩形・位置X')->nullable();
        	$table->integer('bounds_y')->comment('矩形・位置Y')->nullable();
        	$table->integer('bounds_width')->comment('矩形・横幅')->nullable();
        	$table->integer('bounds_height')->comment('矩形・縦幅')->nullable();
        	$table->string('action_type', 16)->comment('アクション・タイプ')->nullable();
        	$table->string('action_label', 16)->comment('アクション・ラベル')->nullable();
        	$table->text('action_json')->comment('アクション・json')->nullable();
        	$table->tinyInteger('delete_flg')->default(0)->comment('削除フラグ')->nullable();
        	
        	$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('richmenu_areas');
    }
};
