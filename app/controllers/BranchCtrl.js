MonsterApp.controller('BranchCtrl', function($rootScope, $scope, $state, $stateParams,$modal, $translate, $location, toaster, Branch) {

    //define variable
    $scope.BranchData = {};
    $scope.listData = [];
    $scope.searchData = { currentPage: 1, itemsPerPage: 10 ,totalItems: 0, isLoading : false };

    $scope.listBranch = function() {
        $scope.searchData.isLoading = true;
        $scope.listData = [];

        Branch.list($scope.searchData)
            .success(function(response) {
                if(response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
                $scope.searchData.isLoading = false;
            });
    };

    $scope.save = function() {
        Branch.update($stateParams.branchID,$scope.BranchData)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.branch.list');
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    }

    $scope.viewBranch = function() {
        if($stateParams.branchID!="") {
            Branch.view($stateParams.branchID)
                .success(function(response) {
                    if(response.status) {
                        $scope.BranchData = response.data;
                    } else {
                        toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    }
                });
        }
    };

    $scope.remove = function(id) {
        Branch.remove(id)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listBranch(1);
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

    $scope.goStateDetail = function(id) {
        $state.go('app.branch.update',{branchID : id});
    };


    //askremove
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

});
