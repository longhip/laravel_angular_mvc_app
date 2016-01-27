<?php namespace App\Http\Controllers\Bill;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Bill\Bill;
use App\Models\Bill\BillCustomer;
use App\Models\Bill\BillStatus;
use App\Models\Bill\BillWorker;
use App\Models\Department\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

use App\Http\Requests;

class BillController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$time = Input::get('time');
		$s_time  = strtotime(date('Y/m/d 00:00:00',$time));
		$e_time  = strtotime(date('Y/m/d 23:59:59',$time));
		$data = [];
		$search = Input::get('search');
		$qr = Bill::on(Auth::getCS())->where('time_end','>=',$s_time)->where('time_end','<=',$e_time)->orderby('time_end','ASC');
		if($search!=""){
			$qr->where(function($q)use($search){
				$q->orWhere('to_name','LIKE','%'.$search.'%');
				$q->orWhere('to_address','LIKE','%'.$search.'%');
				$q->orWhere('to_phone','LIKE','%'.$search.'%');
				$q->orWhere('request','LIKE','%'.$search.'%');
			});
		}
		$list = $qr->get();
		if(!$list->isEmpty()){
			$q = BillStatus::on(Auth::getCS())->where('visible',1);
			$count = $q->count();
			$statuses = $q->get();
			foreach($list as $re){
				if($re->status<$count && $re->time_end < time()){
					$re['progress_type']='danger';
				}
				else if($re->status>=$count){
					$re['progress_type']='success';
				}
				else {
					$re['progress_type']='primary';
				}
				$re->price=number_format($re->price);
				foreach($statuses as $status){
					$re['progress']= intval(($re->status/$count)*100);
					if($re->status == $status->id){
						$re['status_name'] = $status->name;
					}
				}
			}
			$data = [
				'message'=>'Có '.count($list).' kết quả được tìm thấy',
				'status'=>true,
				'data'=>$list
			];
		}else{
			$data = [
				'message'=>'Không có kết quả nào được tìm thấy',
				'status'=>false,
				'data'=>[]
			];
		}
		return Response::json($data);
	}
	public function showList(){
		$status = Input::get('status');
		$search = Input::get('search');
		$branch = Auth::getBranchID();
		$company = Auth::getCompanyId();
		$department = Input::get('department');
		$qr = Bill::on(Auth::getCS())->where('status',$status)->where('branch_id',$branch);
		if($search!=""){
			$qr->where(function($q)use($search){
				$q->orWhere('to_name','LIKE','%'.$search.'%');
				$q->orWhere('to_address','LIKE','%'.$search.'%');
				$q->orWhere('to_phone','LIKE','%'.$search.'%');
				$q->orWhere('request','LIKE','%'.$search.'%');
			});
		}
		$userWork = [];
		if($status == 2){
			$ids = BillWorker::on(Auth::getCS())->where('status',1)->where('worker_id',Auth::getId())->lists('bill_id');
			$qr->whereIn('id',$ids);
			$re = BillWorker::on(Auth::getCS())->where('status',1)->whereIn('bill_id',$ids)->get();
			$userWork = [];
			foreach($re as $r){
				if(!empty($userWork)){
					foreach($userWork as $key=>$row){
						//var_dump($key.' '.$row['bill_id'].' == '.$r->bill_id);
						if($row['bill_id']==$r->bill_id){
							array_push($row['users'],$r->worker_id);
							$userWork[$key] = $row;
						}else if($row['bill_id']!=$r->bill_id && $key == count($userWork)-1){
							array_push($userWork,['bill_id'=>$r->bill_id, 'users'=>[$r->worker_id]]);
						}
					}
				}else{
					$userWork[0] = ['bill_id'=>$r->bill_id, 'users'=>[$r->worker_id]];
				}
			}
		}
		$total =$qr->count();
		$list = $qr->orderBy('time_end','ASC')->get();
		if(!$list->isEmpty()){
			$users = User::on(Auth::getCS())->where('department_id',$department)->where('branch_id',$branch)->get();
			foreach($users as $user){
				$user->avatar = url($user->avatar);
			}
			$data = [
				'message'=>'Có '.$total.' đơn hàng',
				'status'=>true,
				'total'=>$total,
				'users'=>$users,
				'data'=>$list
			];
			if($status == 2){
				$data['userWork'] = $userWork;
			}
		}else{
			$data = [
				'message'=>'Không có đơn hàng nào được tìm thấy',
				'status'=>false,
				'total'=>$total,
				'data'=>[]
			];
		}
		return Response::json($data);
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$to_phone = str_replace(['_','.'],'',Input::get('to_phone'));
		$from_phone = str_replace(['_','.'],'',Input::get('from_phone'));
		$event_time = dateToTime(Input::get('event_time'));
		$to_id = Input::get('to_id');
		$from_id = Input::get('from_id');
		if($to_id==0 && $to_phone!=''){
			$customer = new BillCustomer();
			$customer->setConnection(Auth::getCS());
			$customer->name = Input::get('to_name');
			$customer->gender = 1;
			$customer->phone = $to_phone;
			$customer->address = Input::get('to_address');
			$customer->bỉthday = $event_time;
			$customer->time_create =$customer->time_update = time();
			$customer->save();
			$to_id = $customer->id;
		}
		if($from_id==0 && $from_phone!=''){
			$customer = new BillCustomer();
			$customer->setConnection(Auth::getCS());
			$customer->name = Input::get('from_name');
			$customer->gender = 1;
			$customer->phone = $from_phone;
			$customer->address = Input::get('from_address');
			$customer->email = Input::get('from_email');
			$customer->time_create =$customer->time_update = time();
			$customer->save();
			$from_id = $customer->id;
		}
		$count = Bill::on(Auth::getCS())->where('time_create','>=',strtotime(date("Y").'/01/01 00:00:00'))->count();
		$obj = new Bill();
		$obj->setConnection(Auth::getCS());
		$obj->name = 'DH-'.date('Y').'-'.($count+1);
		$obj->request = Input::get('request');
		$obj->to_name = Input::get('to_name');
		$obj->to_phone = $to_phone;
		$obj->to_id = $to_id;
		$obj->to_address = Input::get('to_address');
		$obj->from_name = Input::get('from_name');
		$obj->from_id = $from_id;
		$obj->relationship = Input::get('relationship');
		$obj->message = Input::get('message');
		$obj->from_email = Input::get('from_email');
		$obj->time_end = dateTimeToTime(Input::get('time_end'));

		$obj->event_name = Input::get('event_name');
		$obj->event_time = $event_time;
		$obj->branch_id = Input::get('branch_id');
		$branch = Branch::on(Auth::getCS())->where('id',$obj->branch_id)->first();
		$obj->branch_name = $branch->name;
		$obj->price = str_replace(['_','.'],'',Input::get('price'));
		$pay = Input::get('pay');
		$obj->pay_id = $pay['id'];
		$obj->pay_name = $pay['name'];
		$obj->person_pay_type = Input::get('person_pay_type');
		$obj->status = Input::get('status');
		$obj->time_create =$obj->time_update = time();
		$obj->save();
		$data = [
			'status'=>true,
		];
		return Response::json($data);
	}
	public function updateWorker(){
		$workers = Input::get('workers');
		$bill_id = Input::get('bill_id');
		$status = Input::get('status');
		BillWorker::on(Auth::getCS())->where('bill_id',$bill_id)->where('status',1)->update(['status'=>0]);
		foreach($workers as $worker){
			$obj = new BillWorker();
			$obj->setConnection(Auth::getCS());
			$obj->bill_id = $bill_id;
			$obj->worker_id = $worker['id'];
			$obj->worker_name = $worker['fullname'];
			$obj->status=1;
			$obj->user_id = Auth::getId();
			$obj->time_create = time();
			$obj->save();
		}
		$data = [
			'message'=>'Cập nhật thành công',
			'status'=>true,
		];
		$bill = Bill::on(Auth::getCS())->find($bill_id);
		$bill->status = $status;
		$bill->save();
		return Response::json($data);
	}

	public function detail($id){
		$data = [];
		$bill = Bill::on(Auth::getCS())->where('id',$id)->first();
		if(!empty($bill)){
			$data = [
				'message'=>'',
				'data'=>$bill,
				'status'=>true
			];
		}else{
			$data = [
				'message'=>'Không có dữ liệu',
				'data'=>[],
				'status'=>false
			];
		}
		return Response::json($data);
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
