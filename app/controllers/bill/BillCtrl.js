/**
 * Created by phongnt on 12/23/2015.
 */
MonsterApp.controller('BillCtrl',function($rootScope, $scope, $state,$translate, $stateParams,$modal, toaster, FileUploader, Bill, Branch, User,$timeout){
    $scope.pay_type_list = [
        {id:1,name:'Thanh toán khi nhận'},
        {id:2,name:'Thanh toán sau'},
        {id:3,name:'Đã thanh toán'},
        {id:4,name:'Quà tặng'}
    ];
    $scope.users = [];
    $scope.branchs = [];
    $scope.obj= {
        'request':'',
        'files':[],
        'to_name':'',
        'to_phone':'',
        'to_address':'',
        'to_id':0,
        'from_id':0,
        'time_end':'',
        'event_name':'',
        'event_time':'',
        'from_name':'',
        'from_phone':'',
        'from_address':'',
        'relationship':'',
        'branch_id':1,
        'branch_name':'',
        'price':'000',
        'pay_id':$scope.pay_type_list[0],
        'person_pay_type':1,
        'img_pattem':{},
        'user':{},
        'showroom':false,
    };
    $scope.selectedUsers = [];
    $scope.userWork = [];
    $scope.type_view = 'day';//day, month, year
    $scope.bills = [];
    $scope.search = '';
    $scope.to_phone_spinner = '';
    $scope.from_phone_spinner = '';
    $scope.page= {
        total:0,
        action:'',
    }
    $scope.viewRows = [];
    $scope.template =
        '<h4 class="media-heading">{{phone}}</h4>'+
        '<h6 class="media-heading">{{name}}</h6>';
    $scope.links_source = Bill.get_links_source();

    $scope.d = new Date();
    $scope.time = $scope.d.getTime();
    $scope.showAddForm = false;
    $scope.cancel = function(){
        $scope.obj= {
            'request':'',
            'files':[],
            'to_name':'',
            'to_phone':'',
            'to_address':'',
            'to_id':0,
            'from_id':0,
            'time_end':'',
            'event_name':'',
            'event_time':'',
            'from_name':'',
            'from_phone':'',
            'from_address':'',
            'relationship':'',
            'branch_id':1,
            'branch_name':'',
            'price':'000',
            'pay':$scope.pay_type_list[0],
            'person_pay_type':1,
            'img_pattem':{},
            'user':{},
            'showroom':false,
        };
        $scope.showAddForm = false;
    }

    var uploader = $scope.uploader = new FileUploader({
        url: 'assets/global/plugins/angularjs/plugins/angular-file-upload/upload.php'
    });
    $scope.load = function(){
        Branch.all().success(function(re){
            if(re.status){
                $scope.branchs = re.data;
            }
        })
        User.allUser().success(function(re){
            if(re.status){
                $scope.users = re.data;
                $scope.users.forEach(function(user){
                    if(user.id == $rootScope.userInfo.id){
                        $scope.obj.user = user;
                    }
                })
            }
        })
        var date_now = $scope.d.getDate()<10 ? "0"+$scope.d.getDate() : $scope.d.getDate();
        var month_now = $scope.d.getMonth()+1<10 ? "0"+($scope.d.getMonth()+1) : $scope.d.getMonth()+1;
        var year_now = $scope.d.getFullYear();
        $scope.go_date = date_now+"/"+month_now+"/"+year_now;
        var time = parseInt($scope.d.getTime()/1000);
        var data = {'time':time};
        Bill.all(data).success(function(re){
            if(re.status){
                $scope.bills = re.data;
            }
        })
    }
    $scope.up_time = function(){
        if($scope.type_view=='day'){
            $scope.d =  new Date($scope.d.getTime()+(24*60*60*1000));
        }
        $scope.reloadBills();
    }
    $scope.down_time = function(){
        if($scope.type_view=='day'){
            $scope.d =  new Date($scope.d.getTime()-(24*60*60*1000));
        }
        $scope.reloadBills();
    }
    $scope.set_time = function(){
        var d = $scope.go_date;
        if(d != $scope.d){
            console.log(d);
            $scope.d = d;
            $scope.reloadBills();
        }
    }
    $scope.search_change =function(e){
        if(e.keyCode ==13){
            $timeout.cancel($scope.callToServer);
            $scope.callToServer = $timeout(function(){
                $scope.reloadBills();
            }, 500);
        }else{
            $timeout.cancel($scope.callToServer);
            $scope.callToServer = $timeout(function(){
                $scope.reloadBills();
            }, 800);
        }

    }
    $scope.reloadBills = function(){
        var date_now = $scope.d.getDate()<10 ? "0"+$scope.d.getDate() : $scope.d.getDate();
        var month_now = $scope.d.getMonth()+1<10 ? "0"+($scope.d.getMonth()+1) : $scope.d.getMonth()+1;
        var year_now = $scope.d.getFullYear();
        $scope.go_date = date_now+"/"+month_now+"/"+year_now;
        var time = parseInt($scope.d.getTime()/1000);
        var data = {'time':time};
        if($scope.search!=''){
            data.search = $scope.search;
        }
        $timeout.cancel($scope.callToServer);
        $scope.callToServer = $timeout(function(){
            Bill.all(data).success(function(re){
                $scope.bills = re.data;
                if(re.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(re.message), $rootScope.toasterDelay, 'trustedHtml');
                } else {
                    toaster.pop('warning',$translate.instant('LABEL.WARNING'),$translate.instant(re.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            })
        }, 1000);

    }
    $scope.to_phones = [];
    $scope.phones = [];
    uploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            return this.queue.length < 10;
        }
    });

    $scope.showSelectType = function(){
        var modalInstance = $modal.open({
            templateUrl: 'tpl/Bill/modalSelectType.html',
            controller: 'ModalSelectTypeCtrl',
            size: 'lg',
            backdrop: true,
            resolve: {
                //votes: function (){
                //    return $scope.rateList;
                //},
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MonsterApp',
                        files: [
                            'app/controllers/Bill/ModalSelectTypeCtrl.js',
                        ]
                    });
                }]
            }
        });
        modalInstance.result.then(function (re) {
            $scope.img_pattems = re;
            console.log($scope.img_pattems);
        }, function (re) {

        });
    }
    $scope.update_is_showroom = function(){
        //$scope.obj.is_showroom == false ? $scope.obj.is_showroom=1 :$scope.obj.is_showroom=0;
        console.log($scope.obj.is_showroom);
    }
    $scope.search_customer = function(e,type){
        if(e.keyCode==13){
            if(type=='to_phone'){
                $scope.to_phone_spinner = 'spinner';
                $timeout.cancel($scope.callToServer);
                $scope.callToServer = $timeout(function(){
                    Bill.getDetailCustomer($scope.obj.to_phone).success(function(re){
                        $scope.to_phone_spinner = '';
                        if(re.status){
                            $scope.obj.to_address = re.data.address;
                            $scope.obj.to_id = re.data.id;
                            $scope.obj.to_name = re.data.name;
                        }
                    })
                }, 100);
            }
            if(type=='from_phone'){
                $scope.from_phone_spinner = 'spinner';
                $timeout.cancel($scope.callToServer);
                $scope.callToServer = $timeout(function(){
                    Bill.getDetailCustomer($scope.obj.from_phone).success(function(re){
                        $scope.from_phone_spinner = '';
                        if(re.status){
                            $scope.obj.from_address = re.data.address;
                            $scope.obj.from_id = re.data.id;
                            $scope.obj.from_name = re.data.name;
                        }
                    })
                }, 100);
            }
        }
    }
    $scope.save = function(type){
        $scope.obj.status = type;
        Bill.create($scope.obj).success(function(re){
        })
    }
    $scope.setup = function(action){
        $scope.page.action = action;
        $scope.loadListWorking();
    }
    $scope.loadListWorking = function(){
        $scope.users=[];
        var data= {};
        if($scope.page.action == 'working'){
            data = {
                'status':1,
                'department':2,
                'search':$scope.search
            };
        }else if($scope.page.action == 'processing'){
            data = {
                'status':2,
                'department':2,
                'search':$scope.search
            };
        }
        Bill.showList(data).success(function(re){
            $scope.bills = re.data;
            $scope.page.total = re.total;
            $scope.users = re.users;
            $scope.userWork = re.userWork;
        })
    }
    $scope.search_list_change =function(e){
        if(e.keyCode ==13){
            $timeout.cancel($scope.callToServer);
            $scope.callToServer = $timeout(function(){
                $scope.loadListWorking();
            }, 500);
        }else{
            $timeout.cancel($scope.callToServer);
            $scope.callToServer = $timeout(function(){
                $scope.loadListWorking();
            }, 800);
        }

    }
    $scope.select_user = function(id){
        console.log($scope.userWork);
        if($scope.selectedUsers.indexOf(id)>=0){
            $scope.selectedUsers.remove(id);
        }else{
            $scope.selectedUsers.push(id);
        }
        console.log($scope.userWork);
    }
    $scope.cancel_select_user = function(){
        $scope.viewRows = [];
        $scope.selectedUsers = [];
    }
    $scope.show_row = function(id){
        if($scope.viewRows.indexOf(id)>=0){
            $scope.viewRows = [];
            $scope.selectedUsers=[];
        }else{
            $scope.viewRows = [];
            $scope.selectedUsers = [];
            $scope.viewRows.push(id);
            //when click show row if action = processing
            if($scope.page.action == 'processing'){
                $scope.userWork.forEach(function(row){
                    if($scope.viewRows.lastIndexOf(row.bill_id)>=0){
                        row.users.forEach(function(uid){
                            $scope.selectedUsers.push(uid);
                        })
                    }
                })
            }
        }
    }
    $scope.update_worker = function(){
        var workers = [];
        $scope.users.forEach(function(row){
            if($scope.selectedUsers.indexOf(row.id)>=0){
                workers.push(row);
            }
        })
        var bill_id = $scope.viewRows[0];
        var data = {
            'workers':workers,
            'bill_id':bill_id,
            'status':2,
        }
        Bill.updateWorker(data).success(function(re){
            if(re.status) {
                $scope.viewRows = [];
                toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(re.message), $rootScope.toasterDelay, 'trustedHtml');
                $scope.loadListWorking();
            } else {
                toaster.pop('warning',$translate.instant('LABEL.WARNING'),$translate.instant(re.message), $rootScope.toasterDelay, 'trustedHtml');
            }
        })
    }
    //detail bill
    $scope.showDetail = function(){

    }
})