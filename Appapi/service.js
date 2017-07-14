pig
    .factory('focus', function($timeout, $window) {
        return function(id) {
            // timeout makes sure that is invoked after any other event has been triggered.
            // e.g. click events that need to run before the focus or
            // inputs elements that are in a disabled state but are enabled when those events
            // are triggered.
            $timeout(function() {
                var element = $window.document.getElementById(id);
                if(element)
                    element.focus();
            });
        };
    })
  .factory('ajaxService',function($http,$q,$ionicLoading,$rootScope){
    var deferredAbort = $q.defer();

    return{
        ajax_post:function(datas){
            $rootScope.$broadcast('apiRequest');
            console.log($rootScope.isAPICalling);
            return $http({
                method: 'POST',
                url: "https://www.propertypig.co.uk/Appapi/api.php",
                timeout: deferredAbort.promise,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: datas
            }).success(function (data,status) {
                $rootScope.$broadcast('apiResponse');
                return data;

            }).error(function(data,status){
                // $ionicLoading.hide();
                // if(status == -1 || status == 503){
                //     servicefunc.alert('No service available');
                // }
            })
        },
        ajax_get:function(data){
            var q = $q.defer();
            $http({
                method:'GET',
                url:"http://www.sicsglobal.com/HybridApp//property_pig/Appapi/api.php",
                params:data
            }).success(function(response,status){
                console.log(response);
//                $rootScope.$broadcast('apiResponse');
                q.resolve(response);
            }).error(function(data,status){
                q.reject(status);
            });

            return q.promise;
        },
        ajax_put:function(datas){
            var q = $q.defer();
            $http({
                method:'PUT',
                url:"http://www.sicsglobal.com/HybridApp//property_pig/Appapi/api.php",
                data: datas
            }).success(function(response,status){
                $rootScope.$broadcast('apiResponse');
                q.resolve(response);
            }).error(function(data,status){
                r.reject(status);
            });
            return q.promise;
        },
        ajax_cancel:function(){
            deferredAbort = $q.defer();
            $rootScope.$broadcast('apiResponse');
            deferredAbort.resolve('ajax_aborted');
            return deferredAbort.promise;
        }
    }
})
    .directive('loading', function($rootScope) {
        return {
            restrict: 'AE',
            template: '<div class="jb-spinner" ng-show="isAPICalling"><ion-spinner class="spinner-energized"></ion-spinner></div>',
            replace: true,
            link: function(scope, elem, attrs) {
                scope.isAPICalling = false;

                $rootScope.$on('apiRequest', function() {
                    console.log("apire");
                    scope.isAPICalling = true;
                });
                $rootScope.$on('apiResponse', function() {
                    console.log("apires");
                    scope.isAPICalling = false;
                });
            }
        };
    })
    .factory('uploadservice',function($q,$ionicLoading,$cordovaFileTransfer,$ionicActionSheet,$cordovaCamera,$ionicPopup,$rootScope){

        function capturephoto(callback){
            var options = {
                destinationType: Camera.DestinationType.FILE_URI,
                sourceType: Camera.PictureSourceType.CAMERA,
                targetWidth: 400,
                targetHeight: 400,
                encodingType: Camera.EncodingType.JPEG,
                popoverOptions: CameraPopoverOptions,
                correctOrientation: true
            };
            $cordovaCamera.getPicture(options).then(function (imageURI) {
                return callback(imageURI);
            }, function (err) {

            });
        }


        function captureFromGallery(callback){
            var options = {
                limit: 1,
                destinationType: Camera.DestinationType.FILE_URI,
                sourceType: Camera.PictureSourceType.PHOTOLIBRARY,
                mediaType:Camera.MediaType.ALLMEDIA,
                targetWidth: 400,
                targetHeight: 400,
                correctOrientation:true
            };
            $cordovaCamera.getPicture(options).then(function (imageURI) {
                return callback(imageURI);
            });


        }

        function actionsheet(){
            var q=$q.defer();
            $ionicActionSheet.show({
                titleText: "Property Pig",
                buttons: [
                    {text: "Take A Picture"},
                    {text: "Upload From Gallery"}
                ],
                cancel: function () {
                    //console.log('CANCELLED');
                },
                buttonClicked: function (index) {
                    if (index == 0) {
                        capturephoto(function(url){
                            q.resolve(url);
                        });
                    }
                    else if (index == 1) {
                        captureFromGallery(function(url){
                            q.resolve(url)
                        });
                    }
                    //console.log('BUTTON CLICKED', index);
                    return true;
                },
                destructiveButtonClicked: function () {
                    //console.log('DESTRUCT');
                    return true;
                }
            });
            return q.promise;
        }
        function uploadfile(uri,userid,propertyid){

            var q = $q.defer();
            $ionicLoading.show();
            var server = 'http://www.sicsglobal.com/HybridApp//property_pig/Appapi/uploads.php?userid='+userid+'&propertyid='+propertyid;
            var options = new FileUploadOptions();
            options.fileKey = 'File';
            options.fileName = uri.substr(uri.lastIndexOf('/') + 1);
            options.mimeType = 'image/jpeg', 'video/mp4';
            options.chunkedMode = false;
            $cordovaFileTransfer.upload(server, uri, options, true)
                .then(function (r) {
                    $ionicLoading.hide();
                    q.resolve(r.response);

                }, function (err) {
                    $ionicLoading.hide();
                    $ionicPopup.alert({
                        title: "Failed to upload!"
                    });
                }, function (progress) {
                    //alert(JSON.stringify(progress));
                });
            return q.promise;
        }
        return {
            uploadfile : uploadfile,
            showactionsheet : actionsheet

        }

    })

;
