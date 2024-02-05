<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CrudBase;


class LineDemo extends CrudBase
{
	protected $table = 'nekos'; // 紐づけるテーブル名
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	

	
	
	

}

