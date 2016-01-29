angular.module('SteedOfficeApp').controller('UserCtrl', function($rootScope,$http,$state, $scope, $timeout,$translate,$translatePartialLoader,UserService,ToastFactory) {

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageContentWhite = true;
    $rootScope.settings.layout.pageBodySolid = false;
    $rootScope.settings.layout.pageSidebarClosed = false;
    $translatePartialLoader.addPart('user');
    $translate.refresh();

    $scope.listUserData = [];
    $scope.userData = {active:1};


    $scope.index = function(){
        UserService.getIndex().success(function(response){
            if(response.status){
                $scope.listUserData = response.data;
            }
            else{
                Toast.popErrors(response.message);
            }
        });
    }

    $scope.store = function(){
        UserService.postStore($scope.userData).success(function(response){
            if(response.status){
                $state.go('app.user.all')
            }
            else{
                Toast.popErrors(response.message);
            }
        });
    }

    
});