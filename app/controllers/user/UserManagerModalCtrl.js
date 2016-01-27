MonsterApp.controller('UserManagerModalCtrl', function($rootScope, $scope,$modalInstance,$timeout, $state, $stateParams, $translate, $location,$modal, toaster, User, Group, Department, Branch, FileUploader, Position,UserData) {

    $scope.UserData = UserData;
    $scope.listData = [];
    $scope.listGroup = [];
    $scope.listBranch = [];
    $scope.listPosition = [];
    $scope.listDepartment = [];
    $scope.isSubmiting = false;

    $scope.save = function() {
        $scope.isSubmiting = true;
        User.create($scope.UserData).success(function(response){
            if(response.status){
                $scope.UserData.id = response.id;
                $scope.UserData.position_name = response.position_name;
                $modalInstance.close($scope.UserData);
                $scope.isSubmiting = false;
                toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
            }
            else{

            }
        });
    };


    $scope.onLoad = function(){
        $timeout(function(){
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
        },1500)
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
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
        avatarUploader.uploadAll();
    };
    avatarUploader.onSuccessItem = function(fileItem, response) {
        avatarUploader.clearQueue();
        if (response.status) {
            $scope.UserData.avatar = response.data.file_url;
            $scope.UserData.avatar_url = response.data.file_full_url;
        }
    };
});