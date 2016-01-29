SteedOfficeApp.filter('fileSize', function() {
    return function(bytes) {

        var type  = 'bytes';
        var size = 0;
        if(bytes >= (1048576)) {
            type = 'MB';
            size = bytes/1048576;
        } else if(bytes >= 1024) {
            type = 'KB';
            size = bytes/1024;
        } else {
            type = 'bytes';
        }
        return Math.round(size * 100) / 100 + ' ' + type;
    };
});

SteedOfficeApp.filter("sanitize", ['$sce', function($sce) {
        return function(htmlCode){
            return $sce.trustAsHtml(htmlCode);
        }
}]);

SteedOfficeApp.filter("letterfilter", [ function() {
    return function(items, searchItem) {
        var filtered = []; 
        searchItem = searchItem.toLowerCase();       
        var regex = new RegExp(searchItem);
        angular.forEach(items, function(item) {  
            var fullname = item.fullname.toLowerCase();            
            var start = fullname.lastIndexOf(' ');
            if(start == -1) {
                start = 0;
            }else {
                start++;    
            }
            var end = fullname.length;
            var search = fullname.slice(start,end);
            search = search.latinise();
            if(searchItem.length == 1) {                
                if(searchItem == search[0]) {                    
                    filtered.push(item);    
                }    
            }else {
                if(regex.test(search)){
                    filtered.push(item);
                }    
            }
                 
            
        });
        return filtered;
    };
}]);