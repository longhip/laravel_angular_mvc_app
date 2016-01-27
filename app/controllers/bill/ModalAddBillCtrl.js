MonsterApp.controller('ModalAddBillCtrl', function($rootScope,$state, $scope,$modalInstance,FileUploader) {
    $scope.Bill = {};
    $scope.Bill.status=1;
    $scope.Bill.branch_id=1;
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
        $state.reload();
    };
    var uploader = $scope.uploader = new FileUploader({
        url: API_PATH + 'upload' + $rootScope.accessToken + '&folder=assign'
    });

    uploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/, options) {
            return this.queue.length < 10;
        }
    });

    uploader.onAfterAddingFile = function(fileItem) {
        $scope.needCheckUpload = true;
    };
    uploader.onSuccessItem = function(fileItem, response) {
        if(response.status) {
            $scope.AssignData.attachment.push(response.data);
        }
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
        $scope.attachmentError.push(fileItem.file);
    };

});
