<?php namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Model;

class BillCustomer extends Model {

	//
    protected $table = 'bill_customers';
    protected $primaryKey = 'id';
//    protected $fillable = [];
    public $timestamps = false;

}
