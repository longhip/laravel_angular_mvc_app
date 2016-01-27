<?php namespace App\Models\Department;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Department extends Model {

	protected $table = 'department';
	protected $primaryKey = 'id';

	protected $fillable = ['name', 'branch_id'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:250',
	];

	public static function getName($departmentId = false) {
		if($departmentId) {
			return Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $departmentId)->pluck('name');
		} else {
			return '';
		}
	}

}