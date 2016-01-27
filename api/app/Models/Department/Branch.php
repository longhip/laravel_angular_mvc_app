<?php namespace App\Models\Department;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Branch extends Model {

	protected $table = 'branch';
	protected $primaryKey = 'id';

	protected $fillable = ['name','code'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:250',
	];

	public static function getName($branchID = false) {		
		if ($branchID) {
			return Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $branchID)->pluck('name');
		} else {
			return '';
		}
	}
}