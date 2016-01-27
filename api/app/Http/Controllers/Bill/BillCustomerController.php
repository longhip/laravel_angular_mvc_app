<?php namespace App\Http\Controllers\Bill;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Bill\BillCustomer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

use App\Http\Requests;

class BillCustomerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}
	public function getCustomerByPhone($phone)
	{
		//
		$list = BillCustomer::on(Auth::getCS())->where('phone', 'LIKE', '%'.$phone.'%')->limit(20)->offset(0)->get();
		return Response::json($list);
		//function for autocomplete
	}
	public function getDetailCustomer($phone)
	{
		//
		$data = [];
		$detail = BillCustomer::on(Auth::getCS())->where('phone', '=',$phone)->first();
		if(!empty($detail)){
			$data = [
				'status'=>true,
				'data'=>$detail
			];
		}else{
			$data = [
				'status'=>false,
				'data'=>[]
			];
		}
		return Response::json($data);
		//function for autocomplete
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
