angular.module('SteedOfficeApp').controller('AuthCtrl', function($rootScope, $scope, $state,$cookieStore,$location,AuthService,ToastFactory,socket) {

	$scope.Credential = {};
    $scope.isLoading = false;
    $scope.userData = {token:''};
    /**
     * Login
     * @response {object} [put data to cookies and path to dashboard]
     */
	$scope.login = function(){
		AuthService.postLogin($scope.Credential).success(function(response){
			if(response.status){
				$scope.userData = response.data;
				$scope.userData.token = response.data.token;
				$cookieStore.put('CurrentUser',$scope.userData);
				ToastFactory.popSuccess(response.message);
				$state.go('app.dashboard');
				$scope.checkLoginData = {
					user:$scope.userData,
					domain:window.location.hostname
				}
				socket.emit('send notification',$scope.checkLoginData);
			}
			else{
				ToastFactory.popErrors(response.message);
			}
		})
	}

	/**
     * Logout
     * @response {object} [put data to cookies and path to dashboard]
     */
	$scope.logout = function(){
		AuthService.getLogout().success(function(response){
			if(response.status){
				$cookieStore.remove('CurrentUser');
				ToastFactory.popSuccess(response.message);
				$state.go('access.login');
			}
			else{
				ToastFactory.popErrors(response.message);
			}
		})
	}


});