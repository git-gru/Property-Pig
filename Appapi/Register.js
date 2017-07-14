pig
.controller('RegisterCtrl', function($scope,$state,$cordovaToast,focus,ajaxService,$ionicLoading,$cordovaOauth,$http,$twitterApi) {

        $scope.$on("$ionicView.loaded", function(event, data){
            $scope.register={
                name:'',
                phoneno:'',
                email:'',
                username:'',
                pass:'',
                confirmpass:'',
                address:'',
                zipcode:''

            };
        });
        $scope.propertyplace='';
        $scope.permanentquestion=JSON.parse(localStorage.getItem('Questions'));
        angular.forEach($scope.permanentquestion.parameters,function(value,key){
            if(key==32)
                $scope.propertyno=value;
            if(key==33)
                $scope.propertyname=value;
        })
        ajaxService.ajax_place({
            no:$scope.propertyno,
            code:$scope.propertyname
        }).then(function (response) {
            console.log();
            angular.forEach(response.Addresses,function(value,key){
                var words = value.split(" ");
                var third=words[2].split(",");
                if(words[0]==$scope.propertyno)
                {
                    $scope.propertyplace=words[0]+' '+words[1]+' '+third[0];
                }
            })
            console.log($scope.propertyplace)
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
            else if(item.pass=='')
            {
                $cordovaToast.show('Password Field Empty','short', 'center').then(function(success) {
                    focus('pass');
                    return;
                }, function (error) {
                    focus('pass');
                    return;
                });
            }
            else if(item.confirmpass=='')
            {
                $cordovaToast.show('Confirm Password Field Empty','short', 'center').then(function(success) {
                    focus('conpass');
                    return;
                }, function (error) {
                    focus('conpass');
                    return;
                });
            }
            else if(item.pass!=item.confirmpass)
            {
                $cordovaToast.show('Mismach Password','short', 'center').then(function(success) {
                    focus('conpass');
                    return;
                }, function (error) {
                    focus('conpass');
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
                $cordovaToast.show('Zipcode Field Empty','short', 'center').then(function(success) {
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
                    request:'register',
                    name:item.name,
                    phoneno:item.phoneno,
                    email:item.email,
                    username:item.username,
                    pass:item.pass,
                    address:item.address,
                    zipcode:item.zipcode,
                    propertyplace:$scope.propertyplace,
                    permanentquestion:localStorage.getItem('Questions'),
                    tempquestion:localStorage.getItem('tempQuestions')
                }).then(function (response) {
                    console.log(response);
                    if(response.data.status=="true")
                    {
                        $ionicLoading.hide();
                        localStorage.setItem('Questions','');
                        localStorage.setItem('tempQuestions','');
                    $cordovaToast.show('Thankyou for registering! Please check your email to activate your account.','long', 'center').then(function(success) {
                        return;
                    }, function (error) {
                        return;
                    });

                    $state.go('PermanentQuestions');
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
                    else if(response.data.status=="Invalid details")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('Invalid Property Details ! please login','short', 'center').then(function(success) {
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

        $scope.facebook=function()
        {
            $cordovaOauth.facebook("177179936123947", ["email"]).then(function(result) {
                //alert(result.access_token);
                $scope.access_token = result.access_token;
                $http.get("https://graph.facebook.com/v2.2/me", {
                    params:
                    {
                        access_token: $scope.access_token,
                        fields: "id,name,email,gender,location,website,picture,relationship_status",
                        format: "json"
                    }
                }).then(function(result1) {
                    $ionicLoading.show();
                    ajaxService.ajax_post({
                        request:'SocialRegister',
                        loginthrought:'facebook',
                        id:result1.data.id,
                        name:result1.data.name,
                        email:result1.data.email,
                        phoneno:'',
                        username:result1.data.name,
                        pass:'',
                        address:'',
                        zipcode:'',
                        propertyplace:$scope.propertyplace,
                        permanentquestion:localStorage.getItem('Questions'),
                        tempquestion:localStorage.getItem('tempQuestions')
//                        picture:result1.data.picture.data.url
                    }).then(function (response) {
                        alert(JSON.stringify(response));
                        alert(result1.data.id );
                        alert(result1.data.firstName);
                        alert(result1.data.emailAddress);
                        alert(result1.data.firstName)
                        alert($scope.propertyplace)
                        alert(localStorage.getItem('Questions'));
                        alert(localStorage.getItem('tempQuestions'));
                        if(response.data.status=="true")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('please complete your profile to continue.','long', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                            localStorage.setItem('Questions','');
                            localStorage.setItem('tempQuestions','');
                            localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                            $state.go('Profilecomplete');
                            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));
                            alert(JSON.stringify($rootScope.UserDetails));

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
                    alert(JSON.stringify(result1));
                    $ionicLoading.show();
                    ajaxService.ajax_post({
                        request:'SocialRegister',
                        loginthrought:'linkedin',
                        id:result1.data.id,
                        name:result1.data.firstName,
                        email:result1.data.emailAddress,
                        phoneno:'',
                        username:result1.data.firstName,
                        pass:'',
                        address:'',
                        zipcode:'',
                        propertyplace:$scope.propertyplace,
                        permanentquestion:localStorage.getItem('Questions'),
                        tempquestion:localStorage.getItem('tempQuestions')
//                        picture:result1.data.pictureUrl,
                    }).then(function (response) {
                        alert(JSON.stringify(response));
                        alert(result1.data.id );
                        alert(result1.data.firstName);
                        alert(result1.data.emailAddress);
                        alert(result1.data.firstName)
                        alert($scope.propertyplace)
                        alert(localStorage.getItem('Questions'));
                        alert(localStorage.getItem('tempQuestions'));
                        if(response.data.status=="true")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('please complete your profile to continue.','long', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });

                            localStorage.setItem('Questions','');
                            localStorage.setItem('tempQuestions','');
                            localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                            $state.go('Profilecomplete');
                            $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));

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
                }, function errorCallback(response) {
//              alert("hai");
                });
            }, function(error) {
//                alert("haiu");
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

            $twitterApi.getUserDetails(user_id).then(function(data) {
                alert(JSON.stringify(data));

                $ionicLoading.show();
                ajaxService.ajax_post({
                    request:'SocialRegister',
                    loginthrought:'twitter',
                    id:data.id,
                    name:data.name,
                    email:'',
                    phoneno:'',
                    username:data.screen_name,
                    pass:'',
                    address:'',
                    zipcode:'',
                    propertyplace:$scope.propertyplace,
                    permanentquestion:localStorage.getItem('Questions'),
                    tempquestion:localStorage.getItem('tempQuestions')
//                        picture:result1.data.pictureUrl,
                }).then(function (response) {
                    alert(JSON.stringify(response));
                    alert(data.id );
                    alert(data.name);
                    alert(data.screen_name);
                    alert($scope.propertyplace)
                    alert(localStorage.getItem('Questions'));
                    alert(localStorage.getItem('tempQuestions'));
                    if(response.data.status=="true")
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('please complete your profile to continue.','long', 'center').then(function(success) {
                            return;
                        }, function (error) {
                            return;
                        });

                        localStorage.setItem('Questions','');
                        localStorage.setItem('tempQuestions','');
                        localStorage.setItem('userdetails',JSON.stringify(response.data.userdetails));
                        $state.go('Profilecomplete');
                        $rootScope.UserDetails=JSON.parse(localStorage.getItem('userdetails'));

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
                    else
                    {
                        $ionicLoading.hide();
                        $cordovaToast.show('please try again','short', 'center').then(function(success) {
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
