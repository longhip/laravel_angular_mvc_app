<?php namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Model;

class BillStatus extends Model {

	//
    protected $table = 'bill_statuses';
    protected $primaryKey = 'id';
//    protected $fillable = [];
    public $timestamps = false;

}
