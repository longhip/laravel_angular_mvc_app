MonsterApp.controller('GroupCtrl', function($rootScope, $scope, $state, $stateParams, $translate, $location,$modal, toaster, Group) {

    //define variable
    $scope.GroupData = {};
    $scope.listData = [];
    $scope.searchData = { currentPage: 1, itemsPerPage: 10 ,totalItems: 0, isLoading : false };

    $scope.listGroup = function() {
        $scope.searchData.isLoading = true;
        $scope.listData = [];

        Group.list($scope.searchData)
            .success(function(response) {
                if(response.status) {
                    $scope.listData = response.data;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
                $scope.searchData.isLoading = false;
            });
    };

    $scope.save = function() {
        Group.update($stateParams.groupID,$scope.GroupData)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.group.list');
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    }

    $scope.viewGroup = function() {
        if($stateParams.groupID!="") {
            Group.view($stateParams.groupID)
                .success(function(response) {
                    if(response.status) {
                        $scope.GroupData = response.data;
                    } else {
                        toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    }
                })

        }
    };


    $scope.remove = function(id) {
        Group.remove(id)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listGroup(1);
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

    $scope.goStateDetail = function(id) {
        $state.go('app.group.update',{groupID : id});
    };

    $scope.remove = function(id) {
        Group.remove(id)
            .success(function(response) {
                if (response.status) {
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listGroup(1);
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

    //ask remove
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
