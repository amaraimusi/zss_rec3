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
    	Schema::create('animals', function (Blueprint $table) {
    		$table->ulid('id')->primary();
    		$table->integer('neko_val')->default(100)->comment('ネコ数値')->nullable();
    		$table->string('neko_name', 255)->comment('ネコ名')->nullable();
    		$table->date('neko_date')->comment('ネコ日付')->nullable();
    		$table->integer('neko_group')->default(0)->comment('ネコ種別');
    		$table->string('neko_dt')->comment('ネコ日時')->nullable();
    		$table->tinyInteger('neko_flg')->default(0)->comment('ネコフラグ');
    		$table->string('img_fn', 255)->comment('画像ファイル名')->nullable();
    		$table->text('note')->comment('備考')->nullable();
    		
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
