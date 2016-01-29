angular.module('SteedOfficeApp').service('AuthService',function($http){
	var baseUrl = API_PATH + 'auth';

	this.postLogin = function(Credential){
		return $http.post(baseUrl + '/login',Credential);
	}
	this.getLogout = function(Credential){
		return $http.get(baseUrl + '/logout/' + 1);
	}
});