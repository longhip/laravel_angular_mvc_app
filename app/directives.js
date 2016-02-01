SteedOfficeApp.directive('ngSpinnerBar', ['$rootScope',
    function($rootScope) {
        return {
            link: function(scope, element, attrs) {
                // by defult hide the spinner bar
                element.addClass('hide'); // hide spinner bar by default

                // display the spinner bar whenever the route changes(the content part started loading)
                $rootScope.$on('$stateChangeStart', function() {
                    element.removeClass('hide'); // show spinner bar
                });

                // hide the spinner bar on rounte change success(after the content loaded)
                $rootScope.$on('$stateChangeSuccess', function() {
                    element.addClass('hide'); // hide spinner bar
                    $('body').removeClass('page-on-load'); // remove page loading indicator
                    Layout.setSidebarMenuActiveLink('match'); // activate selected link in the sidebar menu
                   
                    // auto scorll to page top
                    setTimeout(function () {
                        App.scrollTop(); // scroll to the top on content load
                    }, $rootScope.settings.layout.pageAutoScrollOnLoad);     
                });

                // handle errors
                $rootScope.$on('$stateNotFound', function() {
                    element.addClass('hide'); // hide spinner bar
                });

                // handle errors
                $rootScope.$on('$stateChangeError', function() {
                    element.addClass('hide'); // hide spinner bar
                });
            }
        };
    }
])
SteedOfficeApp.directive('steedofficeValidate', function($window, $parse) {
        return {
            restrict: 'A',
            required: 'form',
            link: function(scope, element, attrs) {
                var fn = $parse(attrs.steedofficeValidate);

                element.bind('submit', function(event) {
                    if (!element.validationEngine('validate')) {
                        return false;
                    }
                    scope.$apply(function() {
                        fn(scope, {
                            $event: event
                        });
                    });
                });
                angular.element($window).bind('resize', function() {
                    element.validationEngine('updatePromptsPosition');
                });
            }
        }
    })
// Handle global LINK click
SteedOfficeApp.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                elem.on('click', function(e) {
                    e.preventDefault(); // prevent link click for above criteria
                });
            }
        }
    };
});

// SteedOfficeApp.directive('datePicker', function () {
//     return {
//         restrict: 'A',
//         require: 'ngModel',
//         scope: {
//             ngModel: '=',
//         },
//         link: function (scope, element, attrs, ngModelCtrl) {
//             if(scope.ngModel !='' && scope.ngModel != undefined){
//                 var parts = scope.ngModel.split("-");
//                 element.datepicker({
//                     onSelect: function (date) {
//                         ngModelCtrl.$setViewValue(date);
//                         scope.$apply();
//                     }
//                 },'setDate', new Date(
//                     parseInt(parts[2], 10),
//                     parseInt(parts[1], 10) - 1,
//                     parseInt(parts[0], 10)
//                 ));
//             }
//             else{
//                 element.datepicker({
//                     onSelect: function (date) {
//                         ngModelCtrl.$setViewValue(date);
//                         scope.$apply();
//                     }
//                 });
//             }
//         }
//     };
// });

SteedOfficeApp.directive('scrollToItem', function() {
    return {
        restrict: 'A',
        scope: {
            scrollTo: "@"
        },
        link: function(scope, $elm, attr) {

            $elm.on('click', function() {
                $('html,body').animate({
                    scrollTop: $(scope.scrollTo).offset().top
                }, "slow");
            });
        }
    }
})
SteedOfficeApp.directive("datetimePickerJquery", function() {
    return {
        restrict: "A",
        require: "ngModel",
        link: function(scope, el, atts, ngModel) {

            var date_format = atts.dateFormat;

            el.datetimepicker({
                lang: 'vi',
                inline: false,
                onChangeDateTime: function(dp, $input) {
                    scope.$apply(function() {
                        ngModel.$setViewValue(dp);
                    });
                },
                format: date_format,
            });
        }
    }
});
SteedOfficeApp.directive("datePickerJquery", function() {
    return {
        restrict: "A",
        require: "ngModel",
        link: function(scope, el, atts, ngModel) {

            var date_format = atts.dateFormat;

            el.datetimepicker({
                lang: 'vi',
                inline: false,
                timepicker: false,
                onChangeDateTime: function(dp, $input) {
                    scope.$apply(function() {
                        ngModel.$setViewValue(dp);
                    });
                },
                format: date_format,
            });
        }
    }
});
SteedOfficeApp.directive("timePickerJquery", function() {
    return {
        restrict: "A",
        require: "ngModel",
        link: function(scope, el, atts, ngModel) {

            var date_format = atts.dateFormat;

            el.datetimepicker({
                lang: 'vi',
                inline: false,
                datepicker: false,
                onChangeDateTime: function(dp, $input) {
                    scope.$apply(function() {
                        ngModel.$setViewValue(dp);
                    });
                },
                format: date_format,
            });
        }
    }
});
SteedOfficeApp.directive("showDatetimepickerTrigger", function() {
    return {
        restrict: 'A',
        link: function(scope, el, attrs) {
            $(el).click(function(event) {
                var id = attrs.idShow;
                $('#' + id).datetimepicker('show');
            })
        }
    }
});

// Handle Dropdown Hover Plugin Integration
SteedOfficeApp.directive('dropdownMenuHover', function () {
  return {
    link: function (scope, elem) {
      elem.dropdownHover();
    }
  };  
});
