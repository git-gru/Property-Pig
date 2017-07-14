pig
.controller('propertyinnerCtrl', function($scope,$cordovaToast,$ionicSideMenuDelegate,$ionicModal,ajaxService,$cordovaCamera,$ionicLoading,$cordovaInAppBrowser,$cordovaCapture,$rootScope,uploadservice) {
        $scope.$on("$ionicView.enter", function(event, data){
            $ionicSideMenuDelegate.canDragContent(false);
            $rootScope.postdoc=$rootScope.innerdetails.doc;
            if($rootScope.innerdetails.propertystatus==null || $rootScope.innerdetails.propertystatus=='' || $rootScope.innerdetails.propertystatus==undefined)
            {
                $scope.showupload=0;
            }
            else
            {
                $scope.showupload=1;
            }

//            $scope.postimages=[{img:'997IMG_20170319_202049493.jpg'},{img:'997IMG_20170319_202049493.jpg'}];
            $ionicModal.fromTemplateUrl('reject.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.modal = modal;
            });
            $scope.openModal = function() {
                $scope.modal.show();
            };
            $scope.closeModal = function() {
                $scope.modal.hide();
            };
            $ionicModal.fromTemplateUrl('accept.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.modal1 = modal;
            });
            $scope.openModal1 = function() {
                $scope.modal1.show();
            };
            $scope.closeModal1 = function() {
                $scope.modal1.hide();
            };

            $ionicModal.fromTemplateUrl('image.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.modal2 = modal;
            });
            $scope.openModalimg = function() {
                $scope.modal2.show();
            };
            $scope.closeModalimg = function() {
                $scope.modal2.hide();
            };



        });
        $scope.openModalreject=function()
        {
            if($rootScope.innerdetails.rate1==null)
            {
                $cordovaToast.show('You can perform this action once the price is recieved','short', 'center').then(function(success) {
                    return;

                }, function (error) {
                    return;
                });
            }
            else{
                $scope.openModal();
            }
        }
        $scope.openModal1accept=function()
        {
            if($rootScope.innerdetails.rate1==null)
            {
                $cordovaToast.show('You can perform this action once the price is recieved','short', 'center').then(function(success) {
                    return;

                }, function (error) {
                    return;
                });
            }
            else{
                $scope.openModal1();
            }
        }
        $scope.closeModalreject=function(item)
        {
            $ionicLoading.show();
            ajaxService.ajax_get({
                request:'acceptreject',
                userid:$rootScope.UserDetails.user_id,
                propertyid:$rootScope.innerdetails.property_id,
                propertyreason:item,
                status:0
            }).then(function (response) {
                console.log(response);
                if(response.status=="true")
                {
                    $ionicLoading.hide();
                    $rootScope.innerdetails.propertystatus=0;
                    localStorage.setItem('propertydetails',JSON.stringify(response.propertydetails));
                    $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
                    $scope.closeModal();
                }
            });

        }
        $scope.closeModal1accept=function(item)
        {
            $ionicLoading.show();
            ajaxService.ajax_get({
                request:'acceptreject',
                userid:$rootScope.UserDetails.user_id,
                propertyid:$rootScope.innerdetails.property_id,
                propertyreason:item,
                status:1
            }).then(function (response) {
                console.log(response);
                if(response.status=="true")
                {
                    $ionicLoading.hide();
                    $scope.showupload=1;
                    $rootScope.innerdetails.propertystatus=1
                    localStorage.setItem('propertydetails',JSON.stringify(response.propertydetails));
                    $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));

                }
            });

        }

        $scope.upload = function(){
//            var options = {
//                limit: 1,
//                destinationType: Camera.DestinationType.FILE_URI,
//                sourceType: Camera.PictureSourceType.PHOTOLIBRARY,
//                mediaType:Camera.MediaType.ALLMEDIA,
//                targetWidth: 400,
//                targetHeight: 400,
//                correctOrientation:true
//            };
//                $cordovaCamera.getPicture(options).then(function (imageURI) {
//                         uploadservice.uploadfile(imageURI,$rootScope.UserDetails.user_id,$rootScope.innerdetails.property_id).then(function(result){
//
//                            $scope.postimages.push({img:result});
//                        })
//
//                });
            uploadservice.showactionsheet().then(function(url){
                uploadservice.uploadfile(url,$rootScope.UserDetails.user_id,$rootScope.innerdetails.property_id).then(function(result){

                    $rootScope.postdoc.push({img:result});

                        })
            });
        }


        $scope.openimg=function(item)
        {
            $scope.imageframe='https://www.propertypig.co.uk/assets/docs/'+item;
            $scope.openModalimg();
        }
        $scope.opendoc=function(item)
        {
            alert("haihh");
            var options = {
                location: 'yes',
                clearcache: 'yes',
                toolbar: 'yes',
                closebuttoncaption:'Close',
                enableViewportScale:'yes'
            };
            $cordovaInAppBrowser.open(encodeURI('https://www.propertypig.co.uk/assets/docs/'+item), '_system', options)
                .then(function(event) {
                    // success
                })
                .catch(function(event) {
                    // error
                });

//            handleDocumentWithURL(
//                function() {console.log('success');},
//                function(error) {
//                    console.log('failure');
//                    if(error == 53) {
//                        console.log('No app that handles this file type.');
//                    }
//                },
//                'http://www.example.com/path/to/document.pdf'
//            );

        }
        $scope.deletedoc=function(item)
        {
            $ionicLoading.show();
            ajaxService.ajax_get({
                request:'deletedoc',
                docid:item.docid
            }).then(function (response) {
                $ionicLoading.hide();
                if(response.status=="true")
                {
                    var index =  $rootScope.innerdetails.doc.indexOf(item);
                    $rootScope.innerdetails.doc.splice(index, 1);
                }
            });
        }

});
