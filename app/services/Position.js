MonsterApp.service('Position', function ($http, $rootScope) {
    var urlBase = API_PATH + 'position';
    
    this.all = function(data) {
        return $http.get(urlBase + '/all' + $rootScope.accessToken, data);
    };

    this.list = function(data) {
        return $http.get(urlBase + $rootScope.accessToken , {params: data});
    };

    this.update = function(id,data) {
        if(id != "") {
            return $http.put(urlBase + '/' + id + $rootScope.accessToken, data);
        } else {
            return $http.post(urlBase + $rootScope.accessToken, data);
        }
    };

    this.view = function(id) {
        return $http.get(urlBase + '/' + id + $rootScope.accessToken);
    };
    
    this.remove = function(id) {
        return $http.get(urlBase + '/delete/' + id + $rootScope.accessToken);
    };

});