/* Setup global settings */
SteedOfficeApp.factory('settings', ['$rootScope', function($rootScope) {
    // supported languages
    var settings = {
        layout: {
            pageSidebarClosed: false, // sidebar menu state
            pageContentWhite: true, // set page content layout
            pageBodySolid: false, // solid body color state
            pageAutoScrollOnLoad: 1000 // auto scroll to top on page load
        },
        assetsPath: 'assets',
        globalPath: 'assets/global',
        layoutPath: 'assets/layouts/layout',
    };

    $rootScope.settings = settings;

    return settings;
}]);

/* Setup config */ 
SteedOfficeApp.factory('config', ['$rootScope', function($rootScope) {

    var config = {
        enableClientAuthExpire: false, // if client not send request to server then auto invalidate token
        timeDelayCheckAuthExpire: 1*60000, // 5 minute (time delay check user request)
        timeExpireAuth: 2*60000, // 30 minute . after 30 minute user do not sent request to server then token is invalidate
    };

    $rootScope.config = config;

    return config;
}]);