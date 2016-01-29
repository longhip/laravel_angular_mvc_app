angular.module('SteedOfficeApp').service('UserService',function($http){
	var baseUrl = API_PATH + 'user';

	this.getIndex = function(){
		return $http.get(baseUrl);
	}

	this.postStore = function(data){
		return $http.post(baseUrl,data);
	}
});