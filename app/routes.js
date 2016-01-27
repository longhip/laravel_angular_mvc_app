MonsterApp.config(function($stateProvider, $urlRouterProvider) {
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/access/login");

    $stateProvider
    .state('access', {
        url: "/access",
        template: '<div ui-view=""></div>'
    })

    .state('access.login', {
        url: "/login",
        templateUrl: "tpl/access/login.html",
        resolve: {
            deps: ['$ocLazyLoad',
                function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MonsterApp',
                        insertBefore: '#ng_load_plugins_before',
                        files: [
                            'assets/admin/pages/css/login2.css',
                            'app/controllers/UserCtrl.js',
                            'app/services/User.js'
                        ]
                    });
                }
            ]
        }
    })
    .state('app', {
        url: "/app",
        controller: function() {
            angular.element(
                $('body').removeClass('login')
            );
        },
        templateUrl: "tpl/app.html",
        resolve: {
            deps: ['$ocLazyLoad',
                function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MonsterApp',
                        files: [
                            'app/controllers/UserCtrl.js',
                            'app/services/User.js',
                            'app/services/Group.js',
                            'app/services/Department.js',
                            'app/services/Branch.js',
                            'app/services/Position.js',
                            'app/services/Notification.js',
                        ]
                    });
                }
            ]
        }
    })
});