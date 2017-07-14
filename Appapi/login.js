pig
.controller('LoginCtrl', function($scope,$state,$cordovaToast,focus,$cordovaOauth,$http,$rootScope,$twitterApi,ajaxService,$ionicLoading) {

        $scope.$on("$ionicView.loaded", function(event, data){
            // handle event
            console.log("State Params: ", data);
            $scope.log={
                email:'',
                pass:''
            };
        });


        $scope.login=function(item)
        {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(item.email=='')
            {
                $cordovaToast.show('Email Field Empty','short', 'center').then(function(success) {
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
            else if(item.pass=='')
            {
                $cordovaToast.show('password Field Empty','short', 'center').then(function(success) {
                    focus('pass');
                    return;

                }, function (error) {
                    focus('pass');
                    return;
                });
            }
            else
            {
                $ionicLoading.show();
                ajaxService.ajax_post({
                    request:'login',
                    email:item.email,
                    pass:item.pass,
                    deviceid:$rootScope.deviceToken,
                    devicename:$rootScope.devicePlatform
                }).then(function (response) {
                    console.log(response);
//                    $state.go('app.Home');
                    if(response.data.status=="true")
                    {
                        $ionicLoading.hide();
                        localStorage.setItem('loginstatus',response.data.status);
						 localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                         localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                         $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
//                        $cordovaToast.show('Successfully Login','shot', 'center').then(function(success) {
//                            return;
//                        }, function (error) {
//                            return;
//                        });
                        $state.go('app.Home');
                    }
                    else if(response.data.status=="verifyemail")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Please check your email to activate your account','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                    else if(response.data.status=="checkurdeatils")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Invalid Email Or Password','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                });
            }

        };
        $scope.facebook=function()
        {
            alert("haihhnbnjj");
           $cordovaOauth.facebook("177179936123947", ["email"]).then(function(result) {
                //alert(result.access_token);
                $scope.access_token = result.access_token;
                $http.get("https://graph.facebook.com/v2.8/me", {
                    params:
                    {
                        access_token: $scope.access_token,
                        fields: "id,name,email,gender,location,website,picture,relationship_status,ids_for_business,third_party_id",
                        format: "json"
                    }
                }).then(function(result1) {
                    alert(JSON.stringify(result1))
                    $ionicLoading.show();
                    ajaxService.ajax_post({
                        request:'Sociallogin',
                        loginthrought:'facebook',
                        id:result1.data.id,
                        name:result1.data.name,
                        email:result1.data.email,
                        deviceid:$rootScope.deviceToken,
                        devicename:$rootScope.devicePlatform
//                        picture:result1.data.picture.data.url
                   }).then(function (response) {
                        if(response.data.status=="true")
                        {
                            $ionicLoading.hide();
                            localStorage.setItem('loginstatus',response.data.status);
                            localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                            localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
//                        $cordovaToast.show('Successfully Login','shot', 'center').then(function(success) {
//                            return;
//                        }, function (error) {
//                            return;
//                        });
                            $state.go('app.Home');
                        }
                        else if(response.data.status=="verifyemail")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Please check your email to activate your account','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                        else if(response.data.status=="checkurdeatils")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Invalid Email Or Password','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                    });

                }),function error(response) {

                }
            });
        }
        $scope.linkedin = function() {

            var LINKEDIN_OAUTH_ID       = "81z32f51efmq0z";
            var LINKEDIN_OAUTH_SECRET   = "bouaJAZ9RqNQo5aq";
            var LINKEDIN_STATE_ID       = "DCEeFWf45A53sdfKef424";      // any uniqueid - user generated custom id
            $cordovaOauth.linkedin(LINKEDIN_OAUTH_ID, LINKEDIN_OAUTH_SECRET, ["r_emailaddress"], LINKEDIN_STATE_ID).then(function(result) {
                //alert(JSON.stringify(result));
                $http({
                    method: 'GET',
                    url: 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address,public-profile-url,picture-url)?oauth2_access_token='+result.access_token+'&format=json'
                }).then(function successCallback(result1) {
                    alert(JSON.stringify(result1))
                    $ionicLoading.show();
                    ajaxService.ajax_post({
                        request:'Sociallogin',
                        loginthrought:'linkedin',
                        id:result1.data.id,
                        name:result1.data.first-name,
                        email:result1.data.emailAddress,
                        deviceid:$rootScope.deviceToken,
                        devicename:$rootScope.devicePlatform
//                        picture:result1.data.pictureUrl,
                    }).then(function (response) {
                        if(response.data.status=="true")
                        {
                            $ionicLoading.hide();
                            localStorage.setItem('loginstatus',response.data.status);
                            localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                            localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
//                        $cordovaToast.show('Successfully Login','shot', 'center').then(function(success) {
//                            return;
//                        }, function (error) {
//                            return;
//                        });
                            $state.go('app.Home');
                        }
                        else if(response.data.status=="verifyemail")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Please check your email to activate your account','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                        else if(response.data.status=="checkurdeatils")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Invalid Email Or Password','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                    });
                }, function errorCallback(response) {

                });
            }, function(error) {

            });
        }
        $scope.twitter=function()
        {

            var twitterKey = 'STORAGE.TWITTER.KEY';
            var clientId = 'Gm3mwbIvfb8fxggyEUR1q8hBM';
            var clientSecret = 'Dug0zdELV9oJazlfPgtwWok4aJ7u0SMTjzQiQPPBCVVHPLZ9qO';
            var myToken = '';
            $cordovaOauth.twitter(clientId, clientSecret).then(function (succ) {
                $twitterApi.configure(clientId, clientSecret, succ);
                $scope.showHomeTimeline(succ.user_id);
            }, function(error) {

            });
        }
        $scope.showHomeTimeline = function(user_id) {
//          alert(user_id)
            $twitterApi.getUserDetails(user_id).then(function(data) {
                alert(JSON.stringify(data))
                $ionicLoading.show();
                ajaxService.ajax_post({
                    request:'Sociallogin',
                    loginthrought:'twitter',
                    id:data.id,
                    name:data.name,
                    email:'',
                    deviceid:$rootScope.deviceToken,
                    devicename:$rootScope.devicePlatform
//                        picture:result1.data.pictureUrl,
                }).then(function (response) {
                    console.log(response);
                    if(response.data.status=="true")
                    {

                        $ionicLoading.hide();
                        localStorage.setItem('loginstatus',response.data.status);
                        localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                        localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                        $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
//                        $cordovaToast.show('Successfully Login','shot', 'center').then(function(success) {
//                            return;
//                        }, function (error) {
//                            return;
//                        });
                        $state.go('app.Home');
                    }
                    else if(response.data.status=="verifyemail")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Please check your email to activate your account','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                    else if(response.data.status=="checkurdeatils")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Invalid Email Or Password','short', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });
                    }
                });

                console.log(data);
            }, function(error) {
                console.log('err: ' + error);
            });
        };

    });
