MonsterApp.controller('ModalSelectTypeCtrl', function($rootScope,$state, $scope,$modalInstance,FileUploader) {
    $scope.img = 0;
    $scope.imgs = [];
    $scope.select = [];
    $scope.libraries = [
        //{id:0,name:'Bỏ trống',link:'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMTUiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjE1IiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwNy41IiB5PSIxMDAiIHN0eWxlPSJmaWxsOiNhYWE7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LXNpemU6MTNweDtmb250LWZhbWlseTpBcmlhbCxIZWx2ZXRpY2Esc2Fucy1zZXJpZjtkb21pbmFudC1iYXNlbGluZTpjZW50cmFsIj4yMTV4MjAwPC90ZXh0Pjwvc3ZnPg=='},
        {id:1,name:'M-01-2015',link:'http://hoaluaonline.com/wp-content/uploads/2014/10/gio-hoa-sinh-nhat-dep.jpg'},
        {id:2,name:'M-02-2015',link:'http://sendflower.vn/wp-content/uploads/hoa-gi%E1%BB%8F-650.jpg'},
        {id:3,name:'M-03-2015',link:'http://sendflower.vn/wp-content/uploads/hoa-20.10-dien-hoa-ngay-quoc-te-phu-nu-300x300.jpg'},
        {id:4,name:'M-04-2015',link:'https://bloghoamungkhaitruong.files.wordpress.com/2013/08/hoa-chuc-mung-thanh-cong-03.jpg?w=300'},
        {id:5,name:'M-05-2015',link:'http://anh.eva.vn/upload/3-2013/images/2013-08-19/1376896131-1.jpg'},
    ]
    $scope.change = function(val){
        $scope.img = val;
    }
    $scope.add = function(val){
        var id = $scope.libraries[val].id;
        if($scope.select.indexOf(id)<0){
            $scope.select.push(id);
        }else{
            $scope.select.remove(id);
        }
        console.log($scope.select);
    }
    $scope.close = function () {
        $modalInstance.dismiss('cancel');
        //$state.reload();
    };
    $scope.ok = function () {
        $scope.libraries.forEach(function(row){
            if($scope.select.indexOf(row.id)>=0){
                $scope.imgs.push(row);
            }
        })
        $modalInstance.close($scope.imgs);
        //$state.reload();
    };

});
