<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Company extends Model {

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	protected $table = 'company';
	protected $primaryKey = 'id';

	protected $fillable = ['name', 'email', 'mobile', 'fax', 'address', 'city_id', 'district_id', 'active'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:250',
	];

	public function users() {
		return $this->hasMany('App\Models\User', 'company_id');
	}

}