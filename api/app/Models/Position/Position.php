<?php namespace App\Models\Position;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Position extends Model {

	protected $table = 'position';
	protected $primaryKey = 'id';

	protected $fillable = ['name'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:250',
	];

	public static function getName($positionID = false) {
		if ($positionID) {
			return Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $positionID)->pluck('name');
		} else {
			return '';
		}
	}
}