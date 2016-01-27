MonsterApp.controller('UpdateMemberModalCtrl', function($rootScope, $scope, $translate, $state, $modal, toaster, $modalInstance, Assign, User, AssignData) {

    $scope.AssignData = AssignData;
    $scope.listUser = [];
    User.all()
            .success(function(response) {
                if(response.status) {
                    $scope.listUser = response.data;
                }
            });
    $scope.loadUsers = function($query) {
        var users = $scope.listUser;
        return users.filter(function(user) {
            return user.name.toLowerCase().indexOf($query.toLowerCase()) != -1;
        });
    };
    $scope.addUser = function($user, type) {
        if(type == 'main_person') {
            $scope.AssignData.main_person.push($user.id);
        } else if(type == 'backer') {
            $scope.AssignData.backer.push($user.id);
        } else {
            $scope.AssignData.coordinator.push($user.id);
        }
    };
    $scope.removeUser = function($user, type) {
        var userArr = [];
        if(type == 'main_person') {
            userArr = $scope.AssignData.main_person;
        } else if(type == 'backer') {
            userArr = $scope.AssignData.backer;
        } else {
            userArr = $scope.AssignData.coordinator;
        }
        var newUserArr = [];
        userArr.forEach(function(oneUser){
          if(oneUser != $user.id){
            newUserArr.push(oneUser);
          }
        });
        if(type == 'main_person') {
            $scope.AssignData.main_person   = newUserArr;
        } else if(type == 'backer') {
            $scope.AssignData.backer        = newUserArr;
        } else {
            $scope.AssignData.coordinator   = newUserArr;
        }
    };

    $scope.save = function() {
        Assign.updateMember($scope.AssignData.id,$scope.AssignData)
            .success(function(response) {
                if(response.status) {
                    $modalInstance.close($scope.AssignData);
                } else {
                    toaster.pop('error',$translate.instant('LABEL.WARNING'),$translate.instant(response.message), $rootScope.toasterDelay, 'trustedHtml');
                }
            })
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.openModal = function(type) {
        var modalInstance = $modal.open({
            templateUrl: 'tpl/popup/user.html',
            controller: 'UserModalCtrl',
            size: 'md',
            resolve: {
                listUser: function() {
                    if(type == 'main_person') {
                        return $scope.AssignData.main_person_user;
                    } else if(type == 'coordinator') {
                        return $scope.AssignData.coordinator_user;
                    } else {
                        return $scope.AssignData.backer_user;
                    }
                },
                type: function() {
                    return type;
                },
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MonsterApp',
                        files: [
                            'app/controllers/UserModalCtrl.js',
                            'app/services/Branch.js'
                        ]
                    });
                }]
            }
        });


        modalInstance.result.then(function (result) {
            var listUser = result.users;
            var type = result.type;
            if(type == 'main_person') {
                $scope.AssignData.main_person_user = [];
                $scope.AssignData.main_person = [];
            } else if(type == 'coordinator') {
                $scope.AssignData.coordinator_user = [];
                $scope.AssignData.coordinator = [];
            } else {
                $scope.AssignData.backer_user = [];
                $scope.AssignData.backer = [];
            }
            listUser.forEach(function(userID) {
                if(($scope.AssignData.main_person.indexOf(userID) == -1 && type == 'main_person') 
                    || ($scope.AssignData.coordinator.indexOf(userID) == -1 && type == 'coordinator')
                    || ($scope.AssignData.backer.indexOf(userID) == -1 && type == 'backer')) {
                    $scope.listUser.forEach(function(user) {
                    if(user.id == userID) {
                        $scope.addUser(user,type);

                        if(type == 'main_person') {
                            if($scope.AssignData.main_person_user == undefined) {
                                $scope.AssignData.main_person_user = [];
                            }
                            $scope.AssignData.main_person_user.push(user);
                        } else if(type =='coordinator') {
                            if($scope.AssignData.coordinator_user == undefined) {
                                $scope.AssignData.coordinator_user = [];
                            }
                            $scope.AssignData.coordinator_user.push(user);
                        } else {
                            if($scope.AssignData.backer_user == undefined) {
                                $scope.AssignData.backer_user = [];
                            }
                            $scope.AssignData.backer_user.push(user);
                        }
                    }
                    });
                }
            });
        }, function () {
        });
    };
});
