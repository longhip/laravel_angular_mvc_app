<?php namespace App\Models;
//Author: haind@steed.vn  -- file

use Illuminate\Database\Eloquent\Model;

class Files extends Model {
	public $timestamps = false;
	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	protected $table = 'file';
	protected $primaryKey = 'id';

}
