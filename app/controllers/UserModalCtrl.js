MonsterApp.controller('UserModalCtrl', function($scope, $state, $modalInstance, Branch, User, type, listUser) {


    $scope.listData = [];
    $scope.users = [];
    $scope.department = [];
    $scope.branch = [];
    $scope.company = [];
    //set button event
    $scope.type = type;
    $scope.select = function () {
        var response = {
            users: $scope.users,
            type: $scope.type
        };
        $modalInstance.close(response);
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    $scope.onLoad = function() {
        Branch.listAndDepartment({type:$scope.type})
            .success(function(response) {
                if(response.status) {                    
                    $scope.listData = response.data;
                    listUser.forEach(function(oneUser) {
                        $scope.toggleCheckUser(oneUser);
                    });
                }
            });
    };

    $scope.onLoad();

    $scope.toggleCheckCompany = function(companyID) {
        if($scope.company.indexOf(companyID) == -1) {
            $scope.company.push(companyID);
            $scope.listData.forEach(function(oneBranch) {
                $scope.branch.push(oneBranch.id);
                oneBranch.department.forEach(function(oneDepartment) {
                    $scope.department.push(oneDepartment.id);
                    oneDepartment.users.forEach(function(oneUser) {
                        $scope.users.push(oneUser.id);
                    });
                });
            });
        } else {
            $scope.company.remove(companyID);
            $scope.listData.forEach(function(oneBranch) {
                $scope.branch.remove(oneBranch.id);
                oneBranch.department.forEach(function(oneDepartment) {
                    $scope.department.remove(oneDepartment.id);
                    oneDepartment.users.forEach(function(oneUser) {
                        $scope.users.remove(oneUser.id);
                    });
                });
            });
        }
    };

    $scope.toggleCheckBranch = function(branch) {
        if($scope.branch.indexOf(branch.id) == -1) {
            $scope.branch.push(branch.id);
            branch.department.forEach(function(oneDepartment) {
                $scope.department.push(oneDepartment.id);
                oneDepartment.users.forEach(function(oneUser) {
                    $scope.users.push(oneUser.id);
                });
            });
        } else {
            $scope.branch.remove(branch.id);
            branch.department.forEach(function(oneDepartment) {
                $scope.department.remove(oneDepartment.id);
                oneDepartment.users.forEach(function(oneUser) {
                    $scope.users.remove(oneUser.id);
                });
            });
        }

        if($scope.branch.length == $scope.listData.length) {
            if($scope.company.indexOf(1) == -1) {
                $scope.company.push(1);
            }
        } else {
            $scope.company.remove(1);
        }
    };


    $scope.toggleCheckDepartment = function(department) {
        if($scope.department.indexOf(department.id) == -1) {
            $scope.department.push(department.id);
            department.users.forEach(function(oneUser) {
                $scope.users.push(oneUser.id);
            });
        } else {
            $scope.department.remove(department.id);
            department.users.forEach(function(oneUser) {
                $scope.users.remove(oneUser.id);
            });
        }

        //check to check || uncheck parent
        $scope.listData.forEach(function(oneBranch) {
            var thisTargetBranch = false;
            oneBranch.department.forEach(function(oneDepartment) {
                if($scope.department.indexOf(oneDepartment.id) == -1) {
                    thisTargetBranch = true;
                }
            });
            if(thisTargetBranch || oneBranch.department.length == 0) {
                $scope.branch.remove(oneBranch.id);
            } else {
                if($scope.branch.indexOf(oneBranch.id) == -1) {
                    $scope.branch.push(oneBranch.id);
                }
            }
            if($scope.branch.length == $scope.listData.length) {
                if($scope.company.indexOf(1) == -1) {
                    $scope.company.push(1);
                }
            } else {
                $scope.company.remove(1);
            }
        });
    };
    
    $scope.toggleCheckUser = function(user) {
        if($scope.users.indexOf(user.id) == -1) {
            $scope.users.push(user.id);
        } else {
            $scope.users.remove(user.id);
        }

        //check to check || uncheck parent
        $scope.listData.forEach(function(oneBranch) {
            var thisTargetBranch = false;
            oneBranch.department.forEach(function(oneDepartment) {
                var thisTargetDepartment = false;
                oneDepartment.users.forEach(function(oneUser) {
                    if($scope.users.indexOf(oneUser.id) == -1) {
                        thisTargetBranch = thisTargetDepartment = true;
                    }
                });
                if(thisTargetDepartment || oneDepartment.users.length == 0) {
                    $scope.department.remove(oneDepartment.id);
                } else {
                    if($scope.department.indexOf(oneDepartment.id) == -1) {
                        $scope.department.push(oneDepartment.id);
                    }
                }
            });
            
            if(thisTargetBranch || oneBranch.department.length == 0) {
                $scope.branch.remove(oneBranch.id);
            } else {
                if($scope.branch.indexOf(oneBranch.id) == -1) {
                    $scope.branch.push(oneBranch.id);
                }
            }
            if($scope.branch.length == $scope.listData.length) {
                if($scope.company.indexOf(1) == -1) {
                    $scope.company.push(1);
                }
            } else {
                $scope.company.remove(1);
            }
        });
    };
    
});

