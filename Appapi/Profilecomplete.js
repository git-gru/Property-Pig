pig
.controller('ProfilecompleteCtrl', function($scope,$state,$cordovaToast,focus,ajaxService,$ionicLoading,$rootScope,$cordovaOauth,$http,$twitterApi) {
        $scope.$on("$ionicView.loaded", function(event, data){
            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
            alert(JSON.stringify(localStorage.getItem('userdetails')));
            alert(JSON.stringify($rootScope.UserDetails));

            $scope.register={
                name:$rootScope.UserDetails.Name,
                phoneno:parseInt($rootScope.UserDetails.phoneNumber),
                email:$rootScope.UserDetails.email,
                username:$rootScope.UserDetails.userName,
                address:$rootScope.UserDetails.Address,
                zipcode:$rootScope.UserDetails.zipCode

            };
        });
        $scope.$on("$ionicView.enter", function(event, data){
            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
            alert(JSON.stringify(localStorage.getItem('userdetails')));
            alert(JSON.stringify($rootScope.UserDetails));

            $scope.register={
                name:$rootScope.UserDetails.Name,
                phoneno:parseInt($rootScope.UserDetails.phoneNumber),
                email:$rootScope.UserDetails.email,
                username:$rootScope.UserDetails.userName,
                address:$rootScope.UserDetails.Address,
                zipcode:$rootScope.UserDetails.zipCode

            };

        });

        $scope.signup=function(item)
        {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(item.name=='')
            {
                $cordovaToast.show('Name Field Empty','short', 'center').then(function(success) {
                    focus('name');
                    return;
                }, function (error) {
                    focus('name');
                    return;
                });
            }
           else if(item.phoneno=='')
            {
                $cordovaToast.show('Phone Number Field Empty','short', 'center').then(function(success) {
                    focus('phoneno');
                    return;
                }, function (error) {
                    focus('phoneno');
                    return;
                });
            }
            else if(item.email=='')
            {
                $cordovaToast.show('Email Id Field Empty','short', 'center').then(function(success) {
                    focus('email');
                    return;
                }, function (error) {
                    focus('email');
                    return;
                });
            }
            else if(!filter.test(item.email))
            {
                $cordovaToast.show('Invalid Email','short', 'center').then(function(success) {
                    focus('email');
                    return;
                }, function (error) {
                    focus('email');
                    return;
                });
            }
            else if(item.username=='')
            {
                $cordovaToast.show('Username Field Empty','short', 'center').then(function(success) {
                    focus('username');
                    return;
                }, function (error) {
                    focus('username');
                    return;
                });
            }

            else if(item.address=='')
            {
                $cordovaToast.show('Address Field Empty','short', 'center').then(function(success) {
                    focus('address');
                    return;
                }, function (error) {
                    focus('address');
                    return;
                });
            }
            else if(item.zipcode=='')
            {
                $cordovaToast.show('Postcode Field Empty','short', 'center').then(function(success) {
                    focus('zip');
                    return;
                }, function (error) {
                    focus('zip');
                    return;
                });
            }
            else
            {
                $ionicLoading.show();
                ajaxService.ajax_post({
                    request:'profilecomplete',
                    userid:$rootScope.UserDetails.user_id,
                    name:item.name,
                    phoneno:item.phoneno,
                    email:item.email,
                    username:item.username,
                    address:item.address,
                    zipcode:item.zipcode
                }).then(function (response) {
                    console.log(response)
                    if(response.data.status=="true")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Successfully completed','long', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                        localStorage.setItem('loginstatus',response.data.status);
                        localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                        localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                        $state.go('app.Home');
//                        $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));

                    }
                    else if(response.data.status=="alreadyexit")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Email already exit ! please login','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                    else if(response.data.status=="false")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('please try again','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                });

            }
        };

    });
