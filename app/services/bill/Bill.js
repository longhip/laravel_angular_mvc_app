MonsterApp.service('Bill', function ($http, $rootScope) {
    var urlBase = API_PATH + 'bill';

    this.all = function(data) {
        return $http.post(urlBase + '/all' + $rootScope.accessToken, data);
    };
    this.showList = function(data) {
        return $http.post(urlBase + '/show-list' + $rootScope.accessToken, data);
    };
    this.updateWorker = function(data) {
        return $http.post(urlBase + '/update-worker' + $rootScope.accessToken, data);
    };

    this.listAndDepartment = function(data) {
        return $http.get(urlBase + '/list' + $rootScope.accessToken, {params: data});
    };

    //this.getCstomerByPhone = function(phone) {
    //    return $http.get(urlBase+"/customer/customer_by_phone/"+phone + $rootScope.accessToken );
    //};
    this.getDetailCustomer = function(phone) {
        return $http.get(urlBase+"/customer/detail/"+phone + $rootScope.accessToken );
    };

    this.update = function(id,data) {
        return $http.put(urlBase + '/update/' + id + $rootScope.accessToken, data);
    };
    this.create = function(data) {
        return $http.post(urlBase +'/create'+ $rootScope.accessToken, data);
    };

    this.view = function(id) {
        return $http.get(urlBase + '/' + id + $rootScope.accessToken);
    };
    
    this.remove = function(id) {
        return $http.get(urlBase + '/delete/' + id + $rootScope.accessToken);
    };

    this.get_links_source = function() {
        return urlBase + '/customer/customer_by_phone/';
    }

});
