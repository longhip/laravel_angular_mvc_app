MonsterApp.service('Company', function ($rootScope, $http) {
    var urlBase = API_PATH + 'company';
    
    this.view = function() {
        return $http.get(urlBase + $rootScope.accessToken);
    }

    this.update = function(data) {
        return $http.put(urlBase + $rootScope.accessToken, data);
    };


});
