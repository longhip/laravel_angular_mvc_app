MonsterApp.controller('UserCtrl', function($rootScope, $scope, $state, $stateParams, $translate, $location,$modal, toaster, localStorageService, User, Group, Department, Branch, FileUploader, Position,Notification) {

    //define variable
    $scope.UserData = {};
    $scope.listData = [];
    $scope.listGroup = [];
    $scope.listBranch = [];
    $scope.listPosition = [];
    $scope.listDepartment = [];
    $scope.searchData = {
        currentPage: 1,
        itemsPerPage: 20,
        totalItems: 0,
        isLoading: false
    };

    $scope.login = function() {
        if (!localStorageService.cookie.isSupported) {
            toaster.pop('warning', $translate.instant('LABEL.WARNING'), $translate.instant('MESSAGE.YOU_NEED_ENABLED_COOKIE_TO_USER_THIS_WEBSITE'), $rootScope.toasterDelay, 'trustedHtml');
            return false;
        }
        User.login($scope.UserData)
            .success(function(response) {
                if (response.status) {
                    $rootScope.userInfo = response.data;
                    localStorageService.set('userInfo', response.data);
                    var token = response.data.token;
                    var cs_code = response.data.cs_code;
                    $rootScope.Notification.data = [];
                    Notification.count(token,cs_code).success(function(response){
                        if(response.status){
                            $rootScope.Notification.chapter = response.count;
                            angular.forEach(response.data,function(val){
                                val.time_create = new Date( val.time_create*1000);
                                $rootScope.Notification.data.push(val);
                            });
                        }
                    })
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.dashboard');
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            })
    };

    $scope.logout = function() {
        User.logout()
            .success(function(response) {
                if (response.status) {
                    $rootScope.Notification.data = [];
                    localStorageService.remove('userInfo');
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('access.login');
                } else {
                    //localStorageService.remove('userInfo');
                    
                }
            });
    }

    $scope.listUser = function(page) {
        if (page != undefined) {
            $scope.searchData.currentPage = page;
        }
        $scope.searchData.isLoading = true;
        $scope.listData = [];
        User.index($scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    
                }
                $scope.searchData.isLoading = false;
            });
        $scope.onLoad();
    };



    //uploader
    var uploader = $scope.uploader = new FileUploader({
        url: API_PATH + 'upload' + $rootScope.accessToken
    });

    uploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            return this.queue.length < 10;
        }
    });



    //uploader
    var avatarUploader = $scope.avatarUploader = new FileUploader({
        url: API_PATH + 'upload' + $rootScope.accessToken
    });

    avatarUploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            return this.queue.length < 10;
        }
    });

    uploader.onAfterAddingFile = function(fileItem) {
        uploader.uploadAll();
    };
    uploader.onSuccessItem = function(fileItem, response) {
        uploader.clearQueue();
        if (response.status) {
            $scope.UserData.contract = response.data.file_url;
        }
    };

    avatarUploader.onAfterAddingFile = function(fileItem) {
        avatarUploader.uploadAll();
    };
    avatarUploader.onSuccessItem = function(fileItem, response) {
        avatarUploader.clearQueue();
        if (response.status) {
            $scope.UserData.avatar = response.data.file_url;
            $scope.UserData.avatar_url = response.data.file_full_url;
        }
    };
    $scope.save = function() {
        User.update($stateParams.userID, $scope.UserData)
            .success(function(response) {
                if (response.status) {
                    if ($stateParams.userID == $rootScope.userInfo.id) {
                        $rootScope.userInfo.avatar = $scope.UserData.avatar_url;
                        localStorageService.set('userInfo', $rootScope.userInfo);
                    }
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.user.list');
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };


    $scope.viewUser = function() {
        if ($stateParams.userID != "") {
            User.view($stateParams.userID)
                .success(function(response) {
                    if (response.status) {
                        $scope.UserData = response.data;
                    } else {
                        toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    }
                });
        }
        $scope.onLoad();
    };

    $scope.remove = function(id) {
        User.remove(id)
            .success(function(response) {
                if (response.status) {
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listUser(1);
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };


    $rootScope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
    };

    $rootScope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.onLoad = function() {
        Group.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listGroup = response.data;
                }
            });

        Department.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listDepartment = response.data;
                }
            });
        Branch.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listBranch = response.data;
                };
            })
        Position.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listPosition = response.data;
                };
            })
        User.checkPermission('Permission.Admin.Manager')
            .success(function(response){
                if(response.status) {
                    $scope.isGroupUser = true;
                }else {
                    $scope.isGroupUser = false;
                }
            }); 
    }

    /**
     * Duong Hai
     * Lấy branch_id và department_id để show ra dữ liệu tương ứng
     * [SelectBranch description]
     * @param {[type]} id [description]
     */
    $scope.sort =function(branch_id,department_id){
        console.log(branch_id +':'+ department_id)
        if(branch_id == null && department_id != null ){
        $scope.searchData.isLoading = true;
        $scope.listData = [];
        User.sortByDepartment(department_id,$scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    
                }
                $scope.searchData.isLoading = false;
            });
        $scope.onLoad();
        }
        if(department_id == null && branch_id != null ){
        $scope.searchData.isLoading = true;
        $scope.listData = [];
        User.sortByBranch(branch_id,$scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    
                }
                $scope.searchData.isLoading = false;
            });
        $scope.onLoad();
        }
        if(branch_id !=null && department_id !=null){
        $scope.searchData.isLoading = true;
        $scope.listData = [];
        User.sortMultiple(branch_id,department_id,$scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    
                }
                $scope.searchData.isLoading = false;
            });
        $scope.onLoad();
        }
        if(branch_id == null && department_id == null){
        $scope.searchData.isLoading = true;
        $scope.listData = [];
        User.sortAll($scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    
                }
                $scope.searchData.isLoading = false;
            });
        $scope.onLoad();
        }
    };

    

    $scope.goStateDetail = function(id) {
        $state.go('app.user.update',{userID : id});
    };

    $scope.askRemove = function(id) {
        var message = $translate.instant("MESSAGE.ARE_YOU_SURE")  + "<br />" 
                        + $translate.instant("MESSAGE.WANT_TO_DELETE_OLD_FILE");


        var modalHtml = '<div class="modal-body">' + message + '</div>';
        modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="ok()">' + $translate.instant("LABEL.OK") + '</button><button class="btn btn-warning" ng-click="cancel()">' + $translate.instant("LABEL.CANCEL") + '</button></div>';

        var modalInstance = $modal.open({
            size: 'sm',
            template: modalHtml,
            controller: function($scope, $modalInstance) {
                  $scope.ok = function() {
                    $modalInstance.close();
                  };

                  $scope.cancel = function() {
                    $modalInstance.dismiss('cancel');
                  };
            }
        });

        modalInstance.result.then(function() {
            $scope.remove(id);
        });
    };

    $scope.checkPermission = function(permission) {        
        User.checkPermission(permission)
            .success(function(response){
                if (response.status) {
                    return true;
                }else {
                    return false;
                }    
            });
    }

});