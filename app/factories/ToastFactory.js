angular.module('SteedOfficeApp').factory('ToastFactory',function(toaster,$translate){
	return {
		popSuccess : function(message){
			toaster.pop({type: 'success',title: $translate.instant('LABEL.SUCCESS'),body: $translate.instant(message)});
		},
		popErrors : function(message){
			toaster.pop('error', $translate.instant('LABEL.FAILED'), $translate.instant(message));
		},
	}
});