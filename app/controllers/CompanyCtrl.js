MonsterApp.controller('CompanyCtrl', function($rootScope, $scope, $state, $translate, $location, toaster, Company, City) {

    //define variable
    $scope.CompanyData = {};
    $scope.listData = [];
    $scope.listCity = [];
    $scope.listDistrict = [];

    $scope.viewCompany = function() {
        City.address()
            .success(function(response) {
                if(response.status) {
                    $scope.listDistrict = [];
                    $scope.listCity = response.data;
                    angular.forEach(response.data,function(oneCity,i) {
                        angular.forEach(oneCity.district, function(oneDistrict) {
                            if($scope.listDistrict[oneCity.id] == undefined) {
                                $scope.listDistrict[oneCity.id] = [];
                            }
                            $scope.listDistrict[oneCity.id].push(oneDistrict);
                        })
                    });
                }
            });
        Company.view()
            .success(function(response) {
                if(response.status) {
                    $scope.CompanyData = response.data;
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    };

    $scope.save = function() {
        Company.update($scope.CompanyData)
            .success(function(response) {
                if(response.status) {
                    toaster.pop('success',$translate.instant('SUCCES'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            });
    }

});
