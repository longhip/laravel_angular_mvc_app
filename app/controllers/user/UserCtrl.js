MonsterApp.controller('UserCtrl', function($rootScope,$timeout, $scope, $state, $stateParams, $translate, $location,$modal, toaster, localStorageService, User, Group, Department, Branch, FileUploader, Position,Notification) {

    //define variable
    $scope.UserData = {
        avatar_preview:''
    };
    $scope.listData = [];
    $scope.listGroup = [];
    $scope.listBranch = [];
    $scope.listPosition = [];
    $scope.listDepartment = [];
    $scope.ShowEditName = '';
    $scope.ShowEditDepartment = '';
    $scope.ShowEditPosition = '';
    $scope.ShowEditEmail = '';
    $scope.ShowEditPhone = '';
    $scope.ShowEditBirthDay = '';
    $scope.ShowEditGender = '';
    $scope.ShowEditAddress = '';
    $scope.newAvatar = false;
    $scope.newSignature = false;
    $scope.user_id = $stateParams.userID;
    $scope.listCollaborator = [];
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
                    localStorageService.remove('userInfo');
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('access.login');
                } else {
                    //localStorageService.remove('userInfo');
                    
                }
            });
    }

    $scope.index = function(page) {
        if (page != undefined) {
            $scope.searchData.currentPage = page;
        }
        $scope.listData = [];
        User.index($scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                }
            });
    };

    $scope.SaveChange = function(type){
        User.SaveChange($stateParams.userID,type,$scope.UserData).success(function(response){
            if(response.status){
                $scope.getDetail();
            }
        });
    }
    $scope.saveChangePassword = function(){
        User.saveChangePassword($stateParams.userID,$scope.UserData).success(function(response){
            if(response.status){
                $scope.UserData.old_password = '';
                $scope.UserData.new_password = '';
                $scope.UserData.confirm_password = '';
                toaster.pop('success', $translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
            else{
                toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
        });
    }

    $scope.saveChangeAvatar = function(){
        User.saveChangeAvatar($stateParams.userID,$scope.UserData).success(function(response){
            if(response.status){
                $rootScope.userInfo.avatar = 'api/public/' + response.data;
                localStorageService.set('userInfo',$rootScope.userInfo);
                $scope.UserData.avatar = response.data;
                toaster.pop('success', $translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
            else{
                toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
        });
    }

    $scope.cancelChangeAvatar = function(){
        $scope.newSignature = false;
        $scope.UserData.signature_preview = '';
    }
    $scope.saveChangeSignature = function(){
        User.saveChangeSignature($stateParams.userID,$scope.UserData).success(function(response){
            if(response.status){
                $rootScope.userInfo.signature = 'api/public/' + response.data;
                localStorageService.set('userInfo',$rootScope.userInfo);
                $scope.UserData.signature = response.data;
                toaster.pop('success', $translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
            else{
                toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
        });
    }

    $scope.cancelChangeSignature = function(){
        $scope.newSignature = false;
        $scope.UserData.signature_preview = '';
    }

    $scope.getDetail = function(){
        if($rootScope.setChangePassword){
            angular.element('#setTabPassword').click();
        }
        User.detail($stateParams.userID).success(function(response){
            if(response.status){
                $scope.UserData = response.data;
            }
        });
    }

    $scope.getAllDepartment = function(){
        Department.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listDepartment = response.data;
                }
            });
    }
    $scope.getAllPosition = function(){
        Position.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listPosition = response.data;
                };
            })
    }

    $scope.removeUser = function(department_index,user_index,user_id) {
        User.remove(user_id)
            .success(function(response) {
                if (response.status) {
                    $scope.listData[department_index].users.splice(user_index,1);
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
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



    $scope.goStateDetail = function(id) {
        $state.go('app.user.detail',{userID : id});
    };

    $scope.getCreateUser = function (department_index,department_id) {
        $scope.UserData.department_index = department_index;
        $scope.UserData.department_id = department_id;

        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'tpl/users/user_modal.html',
            controller: 'UserManagerModalCtrl',
            size: 'lg',
            resolve: {
                UserData: function () {
                    return $scope.UserData;
                }
            }
        });

        modalInstance.result.then(function (UserData) {
            $scope.UserData = UserData;
            $scope.listData[department_index].users.push(UserData);
        });
    };




    /* Uploader */

    var avatarUploader = $scope.avatarUploader = new FileUploader({
        url: API_PATH + 'upload' + $rootScope.accessToken
    });

    avatarUploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            return this.queue.length < 10;
        }
    });

    avatarUploader.onAfterAddingFile = function(fileItem) {
        $scope.newAvatar  = true;
        avatarUploader.uploadAll();
    };
    avatarUploader.onSuccessItem = function(fileItem, response) {
        avatarUploader.clearQueue();
        if (response.status) {
            $scope.UserData.avatar_preview = response.data.file_url;
            $scope.UserData.avatar_url = response.data.file_full_url;
        }
    };

    var signatureUploader = $scope.signatureUploader = new FileUploader({
        url: API_PATH + 'upload' + $rootScope.accessToken
    });

    signatureUploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            return this.queue.length < 10;
        }
    });

    signatureUploader.onAfterAddingFile = function(fileItem) {
        $scope.newSignature  = true;
        signatureUploader.uploadAll();
    };
    signatureUploader.onSuccessItem = function(fileItem, response) {
        signatureUploader.clearQueue();
        if (response.status) {
            $scope.UserData.signature_preview = response.data.file_url;
            $scope.UserData.signature_url = response.data.file_full_url;
        }
    };
});