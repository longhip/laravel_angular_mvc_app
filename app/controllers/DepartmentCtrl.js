MonsterApp.controller('DepartmentCtrl', function($rootScope, $scope, $state, $stateParams, $modal, $translate, $location, toaster, Branch, Department, User) {

    //define variable
    $scope.DepartmentData = {};
    $scope.listData = [];
    $scope.listBranch = [];
    $scope.listUserAuto = [];
    $scope.searchData = {
        currentPage: 1,
        itemsPerPage: 2,
        totalItems: 0,
        isLoading: false
    };

    $scope.listDepartment = function() {
        $scope.searchData.isLoading = true;
        $scope.listData = [];

        Department.list($scope.searchData)
            .success(function(response) {
                if (response.status) {
                    $scope.listData = response.data;
                    $scope.total_record = response.total;
                    $scope.itemNo = ($scope.searchData.currentPage - 1) * $scope.searchData.itemsPerPage;
                    $scope.searchData.totalItems = response.total;
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
                $scope.searchData.isLoading = false;
            });
    };

    $scope.save = function() {
        Department.update($stateParams.departmentID, $scope.DepartmentData)
            .success(function(response) {
                if (response.status) {
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.department.list');
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    }

    $scope.viewDepartment = function() {
        if ($stateParams.departmentID != "") {
            Department.view($stateParams.departmentID)
                .success(function(response) {
                    if (response.status) {
                        $scope.DepartmentData = response.data;
                    } else {
                        toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    }
                });

        }
        Branch.all()
            .success(function(response) {
                if (response.status) {
                    $scope.listBranch = response.data;
                } else {
                    toaster.pop('warning', $translate.instant('LABEL.WARNING'), $translate.instant('MESSAGE.YOU_NEED_CREATE_BRANCH_BEFORE'), $rootScope.toasterDelay, 'trustedHtml')
                }
            });
        User.all()
            .success(function(response) {
                if (response.status) {
                    angular.forEach(response.data, function(value, key) {
                        var data = {
                            id: value.id,
                            name: value.fullname
                        };
                        $scope.listUserAuto.push(data);
                    });
                }
            });
    };
    $scope.updateListUser = function(typed) {
        var str_seach = '';
        if (typeof typed.id === 'undefined') {
            str_seach = typed;

            $scope.newData = User.all(str_seach).success(function(response) {
                $scope.listUserAuto = [];
                if (response.status) {
                    angular.forEach(response.data, function(value, key) {
                        var data = {
                            id: value.id,
                            name: value.fullname
                        };
                        $scope.listUserAuto.push(data);
                    });
                }
            });
        } else {
            $scope.DepartmentData.manager_name = typed.name;
            $scope.DepartmentData.manager_id = typed.id;
            str_seach = $scope.DepartmentData.manager_name;
        }
    }

    $scope.remove = function(id) {
        Department.remove(id)
            .success(function(response) {
                if (response.status) {
                    toaster.pop('success', $translate.instant('LABEL.SUCCESS'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listDepartment(1);
                } else {
                    toaster.pop('error', $translate.instant('LABEL.WARNING'), $translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

    $scope.goStateDetail = function(id) {
        $state.go('app.department.update', {
            departmentID: id
        });
    };

    //ask remove
    $scope.askRemove = function(id) {
        var message = $translate.instant("MESSAGE.ARE_YOU_SURE") + "<br />" + $translate.instant("MESSAGE.WANT_TO_DELETE_OLD_FILE");


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