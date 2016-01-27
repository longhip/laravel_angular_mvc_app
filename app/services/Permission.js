MonsterApp.service('Permission', function ($http, $rootScope) {
    var urlBase = API_PATH + 'permission';
    
    this.list = function(data) {
        return $http.get(urlBase + $rootScope.accessToken , {params: data});
    };

    this.view = function(id) {
        return $http.get(urlBase + '/' + id + $rootScope.accessToken);
    };

    this.update = function(id,data) {
        if(id != "") {
            return $http.put(urlBase + '/' + id + $rootScope.accessToken, data);
        } else {
            return $http.post(urlBase + $rootScope.accessToken, data);
        }
    };

    this.remove = function(id) {
        return $http.get(urlBase + '/delete/' + id + $rootScope.accessToken);
    };


});
