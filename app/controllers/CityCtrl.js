MonsterApp.controller('CityCtrl', function($rootScope, $scope, $state, $stateParams, $translate, $location, toaster, City) {

    //define variable
    $scope.CityData = {};
    $scope.listData = [];
    $scope.searchData = { currentPage: 1, itemsPerPage: 10 ,totalItems: 0, isLoading : false };

    $scope.listCity = function() {
        $scope.searchData.isLoading = true;
        $scope.listData = [];

        City.list($scope.searchData)
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
        City.update($stateParams.cityID,$scope.CityData)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $state.go('app.city.list');
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    }

    $scope.viewCity = function() {
        if($stateParams.cityID!="") {
            City.view($stateParams.cityID)
                .success(function(response) {
                    if(response.status) {
                        $scope.CityData = response.data;
                    } else {
                        toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    }
                });
        }
    };


    $scope.remove = function(id) {
        City.remove(id)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('LABEL.SUCCESS'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                    $scope.listCity(1);
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

});
