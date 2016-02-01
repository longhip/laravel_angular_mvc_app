var API_PATH = 'api/public/api/v1/';
/* Monster App */

var SteedOfficeApp = angular.module('SteedOfficeApp', [
    "ui.router", 
    "ui.bootstrap", 
    "oc.lazyLoad",  
    "ngSanitize",
    "pascalprecht.translate",
    "LocalStorageModule",
    "toaster",
    "ngCookies",
]); 

/* Configure ocLazyLoader(refer: https://github.com/ocombe/ocLazyLoad) */
SteedOfficeApp.config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
    $ocLazyLoadProvider.config({
        // global configs go here
    });
}]);

//AngularJS v1.3.x workaround for old style controller declarition in HTML
SteedOfficeApp.config(['$controllerProvider', function($controllerProvider) {
    // this option might be handy for migrating old apps, but please don't use it
    // in new ones!
    $controllerProvider.allowGlobals();
}]);

/********************************************
 END: BREAKING CHANGE in AngularJS v1.3.x:
 *********************************************/

/* Setup connect Nodejs Sever */
SteedOfficeApp.factory('socket',function(){
    var socket = io.connect('http://103.7.40.9:6868');
    return socket;
});


/* Setup App Main Controller */
SteedOfficeApp.controller('AppController', ['$scope', '$rootScope', function($scope, $rootScope) {
    $scope.$on('$viewContentLoaded', function() {
        App.initComponents(); // init core components
        //Layout.init(); //  Init entire layout(header, footer, sidebar, etc) on page load if the partials included in server side instead of loading with ng-include directive 
    });
}]);

/* Setup Layout Part - Header */
SteedOfficeApp.controller('HeaderController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        Layout.initHeader(); // init header
    });
}]);

/* Setup Layout Part - Sidebar */
SteedOfficeApp.controller('SidebarController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        Layout.initSidebar(); // init sidebar
    });
}]);

/* Setup Layout Part - Quick Sidebar */
SteedOfficeApp.controller('QuickSidebarController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        setTimeout(function(){
            QuickSidebar.init(); // init quick sidebar
        }, 2000)
    });
}]);

/* Setup Layout Part - Theme Panel */
SteedOfficeApp.controller('ThemePanelController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        Demo.init(); // init theme panel
    });
}]);

/* Setup Layout Part - Footer */
SteedOfficeApp.controller('FooterController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        Layout.initFooter(); // init footer
    });
}]);

/* Init global settings and run the app */
SteedOfficeApp.run(["$rootScope", "settings", "$state", function($rootScope, settings, $state) {
    $rootScope.$state = $state; // state to be accessed from view
    $rootScope.$settings = settings; // state to be accessed from view    
}]);
SteedOfficeApp.run(function($rootScope, $state,$interval,config, $location, $http, $cookieStore,socket) {
    if(config.enableClientAuthExpire){
        $interval(function(){
           if($rootScope.rootAuth != null){
                if($cookieStore.get('time_out') != null){
                    $cookieStore.put('time_out',config.timeDelayCheckAuthExpire + $cookieStore.get('time_out'));
                }
                else{
                    $cookieStore.put('time_out',config.timeDelayCheckAuthExpire);
                }
           }
           if($cookieStore.get('time_out') != null && $cookieStore.get('time_out') >= config.timeExpireAuth){
                $http.get(API_PATH + 'auth/logout').success(function(response){
                    if(response.status){
                        $cookieStore.remove('CurrentUser');
                        $cookieStore.remove('time_out');
                        $location.path('access/login');
                    }
                })
            }
        },config.timeDelayCheckAuthExpire);
    }
});
/* Init global settings and run the app */
SteedOfficeApp.run(function($rootScope, $state,$interval, $location, $http, $cookieStore,socket) {
    $rootScope.rootAuth = {};

    $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
        $rootScope.rootAuth = $cookieStore.get('CurrentUser');
        if ($rootScope.rootAuth == null) {
           $location.path('access/login');
        }
        else{
          $state.current = toState;
          if ($state.current.name == 'access.login') {
            $location.path('app/dashboard');
          }
          
        }
    });
});
SteedOfficeApp.config(function($httpProvider) {
  $httpProvider.interceptors.push(['$q', '$location','$cookieStore', function($q, $location,$cookieStore) {
    return {
      'request': function(config) {
        config.headers = config.headers || {};
        if ($cookieStore.get('CurrentUser') != null) {
          config.headers.Authorization = 'Bearer ' + $cookieStore.get('CurrentUser').token;
          $cookieStore.remove('time_out');
        }
        return config;
      },
      'responseError': function(response) {
        if (response.status === 401) {
            $cookieStore.remove('CurrentUser');
            $location.path('access/login');
          }
          return $q.reject(response);
        }
      };
    }]);
  }
)

SteedOfficeApp.config(function ($translateProvider, localStorageServiceProvider, $translatePartialLoaderProvider) {
    $translateProvider.useLoader('$translatePartialLoader', {
        urlTemplate: 'languages/{part}/{lang}.json'
    });
    $translateProvider.preferredLanguage('vn');
    $translateProvider.useCookieStorage();
    $translatePartialLoaderProvider.addPart('default');
});