<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class District extends Model {

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	protected $table = 'district';
	protected $primaryKey = 'id';

	protected $fillable = ['city_id', 'name', 'active'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:50',
		'city_id' => 'required|integer',
	];
}