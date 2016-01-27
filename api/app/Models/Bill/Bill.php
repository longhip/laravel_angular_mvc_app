<?php namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

	//
	protected $table = 'bills';
    protected $primaryKey = 'id';
//    protected $fillable = [];
    public $timestamps = false;

}
