angular.module('SteedOfficeApp').controller('DashboardController', function($rootScope,$http, $scope, $http, $timeout,$translate) {

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageContentWhite = true;
    $rootScope.settings.layout.pageBodySolid = false;
    $rootScope.settings.layout.pageSidebarClosed = false;
});