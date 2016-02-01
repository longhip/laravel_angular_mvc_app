SteedOfficeApp.config(function($stateProvider, $urlRouterProvider) {
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/access/login");

    $stateProvider
        .state('access', {
            url: "/access",
            template: '<div ui-view=""></div>'
        })

    .state('access.login', {
            url: "/login",
            templateUrl: "views/auth/login.html",
            resolve: {
                deps: ['$ocLazyLoad',
                    function($ocLazyLoad) {
                        return $ocLazyLoad.load({
                            name: 'SteedOfficeApp',
                            insertBefore: '#ng_load_plugins_before',
                            files: [
                                'assets/admin/pages/css/login2.css',
                                'app/controllers/auth/AuthCtrl.js',
                                'app/services/auth/AuthService.js',
                                'app/factories/ToastFactory.js'
                            ]
                        });
                    }
                ]
            }
        })
        .state('app', {
            url: "/app",
            templateUrl: "views/app.html",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'SteedOfficeApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
                        files: [
                            'app/controllers/auth/AuthCtrl.js',
                            'app/services/auth/AuthService.js',
                            'app/factories/ToastFactory.js'
                        ]
                    });
                }]
            }
        })
        // Dashboard
        .state('app.dashboard', {
            url: "/dashboard",
            templateUrl: "views/dashboard.html",
            data: {
                pageTitle: 'Admin Dashboard Template'
            },
            controller: "DashboardController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'SteedOfficeApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
                        files: [
                            'assets/global/plugins/morris/morris.css',
                            'assets/global/plugins/morris/morris.min.js',
                            'assets/global/plugins/morris/raphael-min.js',
                            'assets/global/plugins/jquery.sparkline.min.js',
                            'assets/pages/scripts/dashboard.js',
                            'app/controllers/DashboardController.js',
                        ]
                    });
                }]
            }
        })
        .state('app.user', {
            url: "/user",
            template: "<div ui-view class='fade-in-up'></div>",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'SteedOfficeApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
                        files: [
                            'app/controllers/user/UserCtrl.js',
                            'app/services/user/UserService.js'
                        ]
                    });
                }]
            }
        })
        .state('app.user.all', {
            url: "/all",
            controller:'UserCtrl',
            templateUrl: "views/user/all.html",
        })
        .state('app.user.add', {
            url: "/add",
            controller:'UserCtrl',
            templateUrl: "views/user/add.html",
        })
    // AngularJS plugins
    .state('app.upload_demo', {
        url: "/upload-demo",
        templateUrl: "views/upload-demo/demo.html",
        data: {
            pageTitle: 'AngularJS File Upload'
        },
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'angularFileUpload',
                    files: [
                        'assets/global/plugins/angularjs/plugins/angular-file-upload/angular-file-upload.min.js',
                    ]
                }, {
                    name: 'SteedOfficeApp',
                    files: [
                        'app/controllers/upload-demo/UploadDemoCtrl.js'
                    ]
                }]);
            }]
        }
    })

    // UI Select
    .state('app.uiselect', {
        url: "/ui_select",
        templateUrl: "views/ui_select",
        data: {
            pageTitle: 'AngularJS Ui Select'
        },
        controller: "UISelectController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'ui.select',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/angularjs/plugins/ui-select/select.min.css',
                        'assets/global/plugins/angularjs/plugins/ui-select/select.min.js'
                    ]
                }, {
                    name: 'SteedOfficeApp',
                    files: [
                        'app/controllers/UISelectController.js'
                    ]
                }]);
            }]
        }
    })

    // UI Bootstrap
    .state('app.uibootstrap', {
        url: "/ui_bootstrap",
        templateUrl: "views/ui_bootstrap.html",
        data: {
            pageTitle: 'AngularJS UI Bootstrap'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'SteedOfficeApp',
                    files: [
                        'app/controllers/GeneralPageController.js'
                    ]
                }]);
            }]
        }
    })

    // Tree View
    .state('app.tree', {
        url: "/tree",
        templateUrl: "views/tree.html",
        data: {
            pageTitle: 'jQuery Tree View'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/jstree/dist/themes/default/style.min.css',

                        'assets/global/plugins/jstree/dist/jstree.min.js',
                        'assets/pages/scripts/ui-tree.min.js',
                        'app/controllers/GeneralPageController.js'
                    ]
                }]);
            }]
        }
    })

    // Form Tools
    .state('app.formtools', {
        url: "/form-tools",
        templateUrl: "views/form_tools.html",
        data: {
            pageTitle: 'Form Tools'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
                        'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
                        'assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css',
                        'assets/global/plugins/typeahead/typeahead.css',

                        'assets/global/plugins/fuelux/js/spinner.min.js',
                        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
                        'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
                        'assets/global/plugins/jquery.input-ip-address-control-1.0.min.js',
                        'assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
                        'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
                        'assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
                        'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
                        'assets/global/plugins/typeahead/handlebars.min.js',
                        'assets/global/plugins/typeahead/typeahead.bundle.min.js',
                        'assets/pages/scripts/components-form-tools-2.min.js',

                        'app/controllers/GeneralPageController.js'
                    ]
                }]);
            }]
        }
    })

    // Date & Time Pickers
    .state('app.pickers', {
        url: "/pickers",
        templateUrl: "views/pickers.html",
        data: {
            pageTitle: 'Date & Time Pickers'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/angular/plugins/datetime_picker/datetime-picker.js',
                        'assets/global/plugins/jquery-datetimepicker/jquery.datetimepicker.css',
                        'assets/global/plugins/jquery-datetimepicker/jquery.datetimepicker.js',
                        'app/controllers/GeneralPageController.js'
                    ]
                }]);
            }]
        }
    })

    // Custom Dropdowns
    .state('app.dropdowns', {
        url: "/dropdowns",
        templateUrl: "views/dropdowns.html",
        data: {
            pageTitle: 'Custom Dropdowns'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load([{
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css',
                        'assets/global/plugins/select2/css/select2.min.css',
                        'assets/global/plugins/select2/css/select2-bootstrap.min.css',

                        'assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js',
                        'assets/global/plugins/select2/js/select2.full.min.js',

                        'assets/pages/scripts/components-bootstrap-select.min.js',
                        'assets/pages/scripts/components-select2.min.js',

                        'app/controllers/GeneralPageController.js'
                    ]
                }]);
            }]
        }
    })

    // Advanced Datatables
    .state('app.datatablesAdvanced', {
        url: "/datatables/managed",
        templateUrl: "views/datatables/managed.html",
        data: {
            pageTitle: 'Advanced Datatables'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/datatables/datatables.min.css',
                        'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',

                        'assets/global/plugins/datatables/datatables.all.min.js',

                        'assets/pages/scripts/table-datatables-managed.min.js',

                        'app/controllers/GeneralPageController.js'
                    ]
                });
            }]
        }
    })

    // Ajax Datetables
    .state('datatablesAjax', {
        url: "/datatables/ajax.html",
        templateUrl: "views/datatables/ajax.html",
        data: {
            pageTitle: 'Ajax Datatables'
        },
        controller: "GeneralPageController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/datatables/datatables.min.css',
                        'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',
                        'assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',

                        'assets/global/plugins/datatables/datatables.all.min.js',
                        'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                        'assets/global/scripts/datatable.js',

                        'app/scripts/table-ajax.js',
                        'app/controllers/GeneralPageController.js'
                    ]
                });
            }]
        }
    })

    // User Profile
    .state("app.profile", {
        url: "/profile",
        templateUrl: "views/profile/main.html",
        data: {
            pageTitle: 'User Profile'
        },
        controller: "UserProfileController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
                        'assets/pages/css/profile.css',

                        'assets/global/plugins/jquery.sparkline.min.js',
                        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',

                        'assets/global/plugins/jquery-validate/css/validationEngine.jquery.css',
                        'assets/global/plugins/jquery-validate/js/jquery.validationEngine.js',
                        'assets/global/plugins/jquery-validate/js/jquery.validationEngine-en.js',

                        'assets/pages/scripts/profile.min.js',

                        'app/controllers/UserProfileController.js'
                    ]
                });
            }]
        }
    })

    // User Profile Dashboard
    .state("app.profile.dashboard", {
        url: "/dashboard",
        templateUrl: "views/profile/dashboard.html",
        data: {
            pageTitle: 'User Profile'
        }
    })

    // User Profile Account
    .state("app.profile.account", {
        url: "/account",
        templateUrl: "views/profile/account.html",
        data: {
            pageTitle: 'User Account'
        }
    })

    // User Profile Help
    .state("app.profile.help", {
        url: "/help",
        templateUrl: "views/profile/help.html",
        data: {
            pageTitle: 'User Help'
        }
    })

    // Todo
    .state('app.todo', {
        url: "/todo",
        templateUrl: "views/todo.html",
        data: {
            pageTitle: 'Todo'
        },
        controller: "TodoController",
        resolve: {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'SteedOfficeApp',
                    insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                    files: [
                        'assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                        'assets/apps/css/todo-2.css',
                        'assets/global/plugins/select2/css/select2.min.css',
                        'assets/global/plugins/select2/css/select2-bootstrap.min.css',

                        'assets/global/plugins/select2/js/select2.full.min.js',

                        'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',

                        'assets/apps/scripts/todo-2.min.js',

                        'app/controllers/TodoController.js'
                    ]
                });
            }]
        }
    })

});
