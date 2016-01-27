MonsterApp.service('Notification',function($rootScope,$http){
    var urlBase = API_PATH + 'notification';

    this.count = function(token,cs_code){
        if(token) {
            return $http.get(urlBase + '/count'+ '?token=' + token + '&cs_code=' + cs_code);
        }else {
            return $http.get(urlBase + '/count'+$rootScope.accessToken);
        }
    }
    this.update = function(id){
        return $http.get(urlBase + '/update/'+id +$rootScope.accessToken);
    }
    this.all = function(searchData){
        return $http.get(urlBase + '/list-all' +$rootScope.accessToken,{params: searchData});
    }
    this.search = function(searchData,value){
        return $http.get(urlBase + '/search/'+ value +$rootScope.accessToken,{params: searchData});
    }
    this.sort = function(searchData,value){
        return $http.get(urlBase + '/sort/'+ value +$rootScope.accessToken,{params: searchData});
    }
})