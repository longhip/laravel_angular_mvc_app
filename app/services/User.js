MonsterApp.service('User', function ($http, $rootScope) {
    var urlBase = API_PATH + 'users';

    this.login = function(data) {
        return $http.post(urlBase + '/login', data);
    };

    this.logout = function() {
        return $http.get(urlBase + '/logout' + $rootScope.accessToken);
    };

    this.all = function(data) {
        return $http.get(urlBase + '/list' + $rootScope.accessToken , {params: data});
    };
    this.allUser = function(){
        return $http.get(urlBase + '/all' + $rootScope.accessToken);
    }

    this.index = function(data) {
        return $http.get(urlBase + $rootScope.accessToken , {params: data});
    };

    this.update = function(id,data) {
        if(data.join_date != undefined) {
            data.join_date = Date.parse(data.join_date)/1000;
        }
        if(id != "") {
            return $http.put(urlBase + '/' + id + $rootScope.accessToken, data);
        } else {
            return $http.post(urlBase + $rootScope.accessToken, data);
        }
    };
    this.create = function(data){
        return $http.post(urlBase + '/create' + $rootScope.accessToken, data);
    }

    this.detail = function(id) {
        return $http.get(urlBase + '/detail/' + id + $rootScope.accessToken);
    };
    
    this.remove = function(id) {
        return $http.get(urlBase + '/delete/' + id + $rootScope.accessToken);
    };

    this.checkPermission = function(permission) {
        return $http.get(urlBase + '/checkpermission/' + permission + $rootScope.accessToken);
    }

    this.listUser = function(data) {
        return $http.get(urlBase + '/list-user-assignment' + $rootScope.accessToken , {params: data});
    };
    this.listCollaborator = function(){
        return $http.get(urlBase + '/list-user-collaborator' + $rootScope.accessToken);
    }
    this.SaveChange = function(userID,type,data){
        return $http.post(urlBase +'/update/'+ userID + $rootScope.accessToken + '&type='+type, data);
    }
    this.saveChangePassword = function(userID,data){
        return $http.post(urlBase +'/update-password/'+ userID + $rootScope.accessToken, data);
    }
    this.saveChangeAvatar = function(userID,data){
        return $http.post(urlBase +'/update-avatar/'+ userID + $rootScope.accessToken, data);
    }
    this.saveChangeSignature = function(userID,data){
        return $http.post(urlBase +'/update-signature/'+ userID + $rootScope.accessToken, data);
    }

    

});
