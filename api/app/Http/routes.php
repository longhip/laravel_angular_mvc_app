<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::group(['prefix' => 'api/v1'], function () {
	Route::post('/users/login', ['uses' => 'Users\UserController@postLogin']);

	//Language
	Route::get('/language', ['uses' => 'LanguageController@getIndex']);
	Route::get('/download', ['uses' => 'DownloadController@getDownload']);
	Route::get('/send_sms', ['uses' => 'Sms\SmsController@getSendsms']);

});

//route need permission
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth',
	'where' => ['id' => '[0-9]+']], function () {

	Route::post('/upload', ['uses' => 'UploaderController@postIndex']);

	//user
	Route::get('/users', ['uses' => 'Users\UserController@getIndex']);
	Route::post('/users/create', ['uses' => 'Users\UserController@postCreate']);
	Route::post('/users/update/{userID}', ['uses' => 'Users\UserController@postUpdate']);
	Route::post('/users/update-password/{userID}', ['uses' => 'Users\UserController@postUpdatePassword']);
	Route::post('/users/update-avatar/{userID}', ['uses' => 'Users\UserController@postUpdateAvatar']);
	Route::post('/users/update-signature/{userID}', ['uses' => 'Users\UserController@postUpdateSignature']);
	Route::put('/users/{id}', ['uses' => 'Users\UserController@putUpdate']);
	Route::get('/users/detail/{userID}', ['uses' => 'Users\UserController@getDetail']);
	Route::get('/users/logout', ['uses' => 'Users\UserController@getLogout']);
	Route::get('/users/delete/{id}', ['uses' => 'Users\UserController@deleteItem']);
	Route::get('/users/checktoken/', ['uses' => 'Users\UserController@getCheckToken']);
	Route::get('/users/list', ['uses' => 'Users\UserController@getList']);
	Route::get('/users/all', ['uses' => 'Users\UserController@index']);

	Route::get('/users/checkpermission/{permission}', ['uses' => 'Users\UserController@checkPermission']);


	//company
	Route::get('/company', ['uses' => 'Users\CompanyController@getView']);
	Route::put('/company', ['uses' => 'Users\CompanyController@putUpdate']);

	//group
	Route::get('/group', ['uses' => 'Users\GroupController@getIndex']);
	Route::get('/group/all', ['uses' => 'Users\GroupController@getAll']);
	Route::post('/group', ['uses' => 'Users\GroupController@postCreate']);
	Route::put('/group/{id}', ['uses' => 'Users\GroupController@putUpdate']);
	Route::get('/group/{id}', ['uses' => 'Users\GroupController@getView']);
	Route::get('/group/delete/{id}', ['uses' => 'Users\GroupController@deleteItem']);

	//city
	Route::get('/city', ['uses' => 'Address\CityController@getIndex']);
	Route::get('/city/all', ['uses' => 'Address\CityController@getAll']);
	Route::get('/city/address', ['uses' => 'Address\CityController@getAddress']);
	Route::post('/city', ['uses' => 'Address\CityController@postCreate']);
	Route::put('/city/{id}', ['uses' => 'Address\CityController@putUpdate']);
	Route::get('/city/{id}', ['uses' => 'Address\CityController@getView']);
	Route::get('/city/delete/{id}', ['uses' => 'Address\CityController@deleteItem']);

	//district
	Route::get('/district', ['uses' => 'Address\DistrictController@getIndex']);
	Route::post('/district', ['uses' => 'Address\DistrictController@postCreate']);
	Route::put('/district/{id}', ['uses' => 'Address\DistrictController@putUpdate']);
	Route::get('/district/{id}', ['uses' => 'Address\DistrictController@getView']);
	Route::get('/district/delete/{id}', ['uses' => 'Address\DistrictController@deleteItem']);


	//Job priority
	Route::get('/job-priority', ['uses' => 'Job\JobPriorityController@getIndex']);
	Route::get('/job-priority/all', ['uses' => 'Job\JobPriorityController@getAll']);
	Route::post('/job-priority', ['uses' => 'Job\JobPriorityController@postCreate']);
	Route::put('/job-priority/{id}', ['uses' => 'Job\JobPriorityController@putUpdate']);
	Route::get('/job-priority/{id}', ['uses' => 'Job\JobPriorityController@getView']);
	Route::get('/job-priority/delete/{id}', ['uses' => 'Job\JobPriorityController@deleteItem']);

	//Job Process
	Route::get('/job-progress/{id}', ['uses' => 'Job\JobProgressController@getLastProgress']);
	Route::post('/job-progress/{id}', ['uses' => 'Job\JobProgressController@postProgress']);

	//Branch
	Route::get('/branch', ['uses' => 'Department\BranchController@getIndex']);
	Route::get('/branch/list', ['uses' => 'Department\BranchController@getList']);
	Route::get('/branch/all', ['uses' => 'Department\BranchController@getAll']);
	Route::post('/branch', ['uses' => 'Department\BranchController@postCreate']);
	Route::put('/branch/{id}', ['uses' => 'Department\BranchController@putUpdate']);
	Route::get('/branch/{id}', ['uses' => 'Department\BranchController@getView']);
	Route::get('/branch/delete/{id}', ['uses' => 'Department\BranchController@deleteItem']);

	//Department
	Route::get('/department', ['uses' => 'Department\DepartmentController@getIndex']);
	Route::get('/department/all', ['uses' => 'Department\DepartmentController@getAll']);
	Route::post('/department', ['uses' => 'Department\DepartmentController@postCreate']);
	Route::put('/department/{id}', ['uses' => 'Department\DepartmentController@putUpdate']);
	Route::get('/department/{id}', ['uses' => 'Department\DepartmentController@getView']);
	Route::get('/department/delete/{id}', ['uses' => 'Department\DepartmentController@deleteItem']);

	//Position
	Route::get('/position', ['uses' => 'Position\PositionController@getIndex']);
	Route::get('/position/all', ['uses' => 'Position\PositionController@getAll']);
	Route::post('/position', ['uses' => 'Position\PositionController@postCreate']);
	Route::put('/position/{id}', ['uses' => 'Position\PositionController@putUpdate']);
	Route::get('/position/{id}', ['uses' => 'Position\PositionController@getView']);
	Route::get('/position/delete/{id}', ['uses' => 'Position\PositionController@deleteItem']);

	//Feedback
	Route::get('/feedback', ['uses' => 'Feedback\FeedbackController@getIndex']);
	Route::get('/feedback/all', ['uses' => 'Feedback\FeedbackController@getAll']);
	Route::post('/feedback', ['uses' => 'Feedback\FeedbackController@postCreate']);
	Route::put('/feedback/{id}', ['uses' => 'Feedback\FeedbackController@putUpdate']);
	Route::get('/feedback/{id}', ['uses' => 'Feedback\FeedbackController@getView']);
	Route::get('/feedback/delete/{id}', ['uses' => 'Feedback\FeedbackController@deleteItem']);
	Route::get('/feedback/listcomment/{feedback_id}', ['uses' => 'Feedback\FeedbackController@getComment']);
	Route::post('/feedback/comment/{feedback_id}', ['uses' => 'Feedback\FeedbackController@postCreateComment']);
	Route::get('/feedback/listparentcomment', ['uses' => 'Feedback\FeedbackController@getParentComment']);


	//Type_inspection
	Route::get('/type_inspection', ['uses' => 'Assignment\Type_inspectionController@getIndex']);
	Route::get('/type_inspection/all', ['uses' => 'Assignment\Type_inspectionController@getAll']);
	Route::post('/type_inspection', ['uses' => 'Assignment\Type_inspectionController@postCreate']);
	Route::put('/type_inspection/{id}', ['uses' => 'Assignment\Type_inspectionController@putUpdate']);
	Route::get('/type_inspection/{id}', ['uses' => 'Assignment\Type_inspectionController@getView']);
	Route::get('/type_inspection/delete/{id}', ['uses' => 'Assignment\Type_inspectionController@deleteItem']);


	//permission
	Route::get('/permission', ['uses' => 'Permission\PermissionController@getIndex']);
	Route::post('/permission', ['uses' => 'Permission\PermissionController@postCreate']);
	Route::put('/permission/{id}', ['uses' => 'Permission\PermissionController@putUpdate']);
	Route::get('/permission/{id}', ['uses' => 'Permission\PermissionController@getView']);
	Route::get('/position/delete/{id}', ['uses' => 'Position\PositionController@deleteItem']);


	/* Notification longnd@steed.vn */
	Route::post('/notification/create', 'NotificationController@postCreate');
	Route::get('/notification/count', 'NotificationController@getCountOfNotification');
	Route::get('/notification/list-all', 'NotificationController@getAll');
	Route::get('/notification/search/{value}', 'NotificationController@getSearch');
	Route::get('/notification/sort/{value}', 'NotificationController@getSort');
	Route::get('/notification/update/{notification_id}', 'NotificationController@getUpdate');

	//Bill phongnt 30/12/2015
	Route::post('/bill/all', 'bill\BillController@index');
	Route::get('/bill/detail/{id}', 'bill\BillController@detail');
	Route::post('/bill/show-list', 'bill\BillController@showList');
	Route::post('/bill/update-worker', 'bill\BillController@updateWorker');
	Route::post('/bill/create', 'bill\BillController@create');
	Route::post('/bill/update/{id}', 'bill\BillController@update');
	Route::get('/bill/customer/customer_by_phone/{phone}', 'bill\BillCUstomerController@getCustomerByPhone');
	Route::get('/bill/customer/detail/{phone}', 'bill\BillCUstomerController@getDetailCustomer');
});

//route master
Route::group(['prefix' => 'api/master/v1'], function () {

	Route::post('/users/login', ['uses' => 'Master\UserController@postLogin']);
	//Language
	Route::get('/language', ['uses' => 'LanguageController@getIndex']);
});

//route master need permission
Route::group(['prefix' => 'api/master/v1', 'middleware' => 'auth.master',
	'where' => ['id' => '[0-9]+']], function () {

	//db
	Route::get('/db-management', ['uses' => 'Master\DBManagementController@getIndex']);
	Route::post('/db-management', ['uses' => 'Master\DBManagementController@postCreate']);
	Route::put('/db-management/{id}', ['uses' => 'Master\DBManagementController@putUpdate']);
	Route::get('/db-management/{id}', ['uses' => 'Master\DBManagementController@getView']);

	//user
	Route::get('/users', ['uses' => 'Master\UserController@getIndex']);
	Route::post('/users', ['uses' => 'Master\UserController@postCreate']);
	Route::put('/users/{id}', ['uses' => 'Master\UserController@putUpdate']);
	Route::get('/users/{id}', ['uses' => 'Master\UserController@getView']);
	Route::get('/users/logout', ['uses' => 'Master\UserController@getLogout']);

	Route::post('/company/create', ['uses' => 'Master\CompanyController@postCreate']);

	//permission
	Route::get('/permission', ['uses' => 'Master\PermissionController@getIndex']);
	Route::post('/permission', ['uses' => 'Master\PermissionController@postCreate']);
	Route::put('/permission/{id}', ['uses' => 'Master\PermissionController@putUpdate']);

	//group
	Route::get('/group', ['uses' => 'Master\GroupController@getIndex']);
	Route::get('/group/all', ['uses' => 'Master\GroupController@getAll']);
	Route::post('/group', ['uses' => 'Master\GroupController@postCreate']);
	Route::put('/group/{id}', ['uses' => 'Master\GroupController@putUpdate']);
	Route::get('/group/{id}', ['uses' => 'Master\GroupController@getView']);

	//city
	Route::get('/city', ['uses' => 'Master\CityController@getIndex']);
	Route::get('/city/all', ['uses' => 'Master\CityController@getAll']);
	Route::get('/city/address', ['uses' => 'Master\CityController@getAddress']);
	Route::post('/city', ['uses' => 'Master\CityController@postCreate']);
	Route::put('/city/{id}', ['uses' => 'Master\CityController@putUpdate']);
	Route::get('/city/{id}', ['uses' => 'Master\CityController@getView']);
	//district
	Route::get('/district', ['uses' => 'Master\DistrictController@getIndex']);
	Route::post('/district', ['uses' => 'Master\DistrictController@postCreate']);
	Route::put('/district/{id}', ['uses' => 'Master\DistrictController@putUpdate']);
	Route::get('/district/{id}', ['uses' => 'Master\DistrictController@getView']);

});

/*
 * DEV
 * Author: longnd@steed.vn
 */

Route::group(['prefix' => 'dev'], function () {
	Route::get('/pdf/assignment/{AssignmentID}', 'Dev\DevController@exportPdf');
	Route::get('/word', 'Dev\DevController@exportWord');
	Route::get('/excel', 'Dev\DevController@exportExcel');
	Route::get('/convert-datetime', 'Dev\DevController@convertDateTime');
	Route::get('/insert-data', 'Dev\DevController@Insert');
	Route::get('/insert-noti-ass', 'Dev\DevController@InsertNotiAss');
	Route::get('/get-user-ass', 'Dev\DevController@getAssignmentUser');
	Route::get('/get-file-type', 'Dev\DevController@getFileType');
	Route::get('/get-assignment-id', 'Dev\DevController@getAssignmentID');
	Route::get('/remove-file', 'Dev\DevController@getRemoveFile');
});
Route::group(['prefix' => 'export'], function () {
	Route::get('/pdf/assignment-process/{AssignmentID}', 'Assignment\ExportController@exportPdf');
	Route::get('/pdf/assignment/{AssignmentID}', 'Assignment\ExportController@exportAssignment');
});
