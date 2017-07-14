pig
.controller('HomeCtrl', function($scope,$cordovaToast,$rootScope,$state,ajaxService,$ionicScrollDelegate,$ionicModal,$ionicLoading) {
        $scope.$on("$ionicView.loaded", function(event, data){
//            $rootScope.notification=(localStorage.getItem('notification')==null || localStorage.getItem('notification')=='' ) ? [] : JSON.parse(localStorage.getItem('notification'))  ;
//            $rootScope.notificationcount=(localStorage.getItem('notificationcount')==null || localStorage.getItem('notificationcount')=='' ) ? 0 : JSON.parse(localStorage.getItem('notificationcount'))  ;
//            $ionicScrollDelegate.scrollTop();
            $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
            console.log($rootScope.notification);
            console.log($rootScope.notificationcount);
            $scope.permanentlength=$rootScope.permanentquestions.length;
            $scope.formData=(localStorage.getItem('Questions')==null || localStorage.getItem('Questions')=='' ) ? {parameters:{}} : JSON.parse(localStorage.getItem('Questions'))  ;
            $scope.temparayquestion="hide";
            $scope.questionshow=0;
            $scope.offerdetails=(localStorage.getItem('offerdetails')==null || localStorage.getItem('offerdetails')=='' ) ? [] : JSON.parse(localStorage.getItem('offerdetails'))  ;
//            $scope.activeanswer=false;
            $scope.maxquestion=$rootScope.tempararyquestions.length;
            $scope.textanswer='';
            $scope.tempquestions=''
            $scope.tempanswers=(localStorage.getItem('tempQuestions')==null || localStorage.getItem('tempQuestions')=='' ) ? [] : JSON.parse(localStorage.getItem('tempQuestions'));
            angular.forEach($scope.tempanswers,function(value,key){
                if(value.index==$scope.questionshow)
                {
                    $scope.style=value.style;
                    $scope.textanswer=value.answer
                }
            })
        });

        $scope.$on("$ionicView.enter", function(event, data){
            $state.go('Profilecomplete');
            $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
            $rootScope.notification=(localStorage.getItem('notification')==null || localStorage.getItem('notification')=='' ) ? [] : JSON.parse(localStorage.getItem('notification'))  ;
            $rootScope.notificationcount=(localStorage.getItem('notificationcount')==null || localStorage.getItem('notificationcount')=='' ) ? 0 : JSON.parse(localStorage.getItem('notificationcount'))  ;
            console.log($rootScope.notification);
            console.log($rootScope.notificationcount);
            console.log($rootScope.propertydetails);
            $scope.permanentlength=$rootScope.permanentquestions.length;
            $scope.formData=(localStorage.getItem('Questions')==null || localStorage.getItem('Questions')=='' ) ? {parameters:{}} : JSON.parse(localStorage.getItem('Questions'))  ;
            $scope.temparayquestion="hide";
            $scope.questionshow=0;
            console.log(JSON.parse(localStorage.getItem('offerdetails')));
            $scope.offerdetails=(localStorage.getItem('offerdetails')==null || localStorage.getItem('offerdetails')=='' ) ? [] : JSON.parse(localStorage.getItem('offerdetails'))  ;
            console.log($scope.offerdetails);
//  $scope.activeanswer=false;
            $scope.maxquestion=$rootScope.tempararyquestions.length;
            $scope.textanswer='';
            $scope.tempquestions=''
            $scope.tempanswers=(localStorage.getItem('tempQuestions')==null || localStorage.getItem('tempQuestions')=='' ) ? [] : JSON.parse(localStorage.getItem('tempQuestions'));
            angular.forEach($scope.tempanswers,function(value,key){
                if(value.index==$scope.questionshow)
                {
                    $scope.style=value.style;
                    $scope.textanswer=value.answer
                }
            })
            $ionicModal.fromTemplateUrl('property.html', {
                scope: $scope
//                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.modal = modal;
            });
            $scope.openModal = function() {
                $scope.modal.show();
            };
            $scope.closeModal = function() {
                $scope.modal.hide();
            };
            ajaxService.ajax_get({
                request:'viewproperty',
                userid:$rootScope.UserDetails.user_id
            }).then(function (response) {
                console.log(response);
                if(response.status=="true")
                {
                    angular.forEach($rootScope.propertydetails,function(value1,key)
                     {
                         angular.forEach(response.propertydetails,function(value,key)
                         {
                                   if(value1.property_id==value.property_id)
                                   {
                                       if(value1.adminstatus!= value.adminstatus)
                                       {
                                           $rootScope.notification.unshift(value);
                                           $rootScope.notificationcount=parseInt($rootScope.notificationcount)+1;
                                       }
                                   }
                         })
                         localStorage.setItem('notificationcount',$rootScope.notificationcount);
                         localStorage.setItem('notification',JSON.stringify($rootScope.notification));
                         console.log($rootScope.notification);
                         console.log($rootScope.notificationcount);
                    })

                    localStorage.setItem('propertydetails',JSON.stringify(response.propertydetails));
                    $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
                }

            });
            ajaxService.ajax_get({
                request:'propertyoffer',
                userid:$rootScope.UserDetails.user_id
            }).then(function (response) {
                console.log(response);
                if(response.status=="true")
                {

                    angular.forEach(response.offerdetails,function(value1,key)
                    {
                        $rootScope.findproperty=0;
                        angular.forEach($rootScope.notification,function(value,key)
                        {
                            if(value1.offerId==value.offerId)
                            {
                                $rootScope.findproperty=1;
//                                  $rootScope.notification.unshift(value1);
                            }
                        })
                        if($scope.findproperty==0)
                        {
                            $rootScope.notificationcount=parseInt($rootScope.notificationcount)+1;
                            localStorage.setItem('notificationcount',$rootScope.notificationcount);
                            $rootScope.notification.unshift(value1);
                            localStorage.setItem('notification',JSON.stringify($rootScope.notification));

                        }
                    })

                }
            });
//            ajaxService.ajax_get({
//                request:'propertyoffer',
//                userid:$rootScope.UserDetails.user_id
//            }).then(function (response) {
//                console.log(response);
//                if(response.status=="true")
//                {
//
//                    angular.forEach(response.offerdetails,function(value1,key)
//                    {
//                        $scope.findproperty=0;
//                        angular.forEach($scope.offerdetails,function(value,key)
//                        {
//                            if(value1.property_id==value.property_id)
//                            {
//                               $scope.findproperty=1;
//                               $rootScope.notification.unshift(value1);
//                            }
//                        })
//                        if($scope.findproperty==0)
//                        {
//                            $rootScope.notificationcount=parseInt($rootScope.notificationcount)+1;
//                            localStorage.setItem('notificationcount',$rootScope.notificationcount);
//                            $scope.offerdetails.push(value1);
//                            console.log($scope.offerdetails);
//                            localStorage.setItem('offerdetails',JSON.stringify($scope.offerdetails));
//                            $rootScope.notification.unshift(value1);
//                        }
//                    })
//
//                }
//            });

        });
//permanent
        $scope.gototempquestion=function()
        {
            $scope.perchecklength=0;
            $scope.emptyfield=0;
            console.log($scope.formData);
            angular.forEach($scope.formData.parameters,function(value,key)
            {
                if(value=='')
                {
                    $scope.emptyfield=1;
                    $cordovaToast.show('Please fill all the fields','short', 'center').then(function(success) {
                        return;
                    }, function (error) {
                        return;
                    });
                }
                $scope.perchecklength=$scope.perchecklength+1;
            })
            console.log($scope.perchecklength);
            console.log($scope.permanentlength);
            if($scope.permanentlength>$scope.perchecklength)
            {
                $scope.emptyfield=1;
                $cordovaToast.show('Please fill all the fields','short', 'center').then(function(success) {
                    return;

                }, function (error) {
                    return;
                });
            }
            else
            {
                if($scope.emptyfield==0)
                {
                    if($rootScope.tempararyquestions.length==0)
                    {
                        localStorage.setItem('Questions',JSON.stringify($scope.formData));
                        $ionicLoading.show();
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
                                if(words[0]==$scope.propertyno)
                                {
                                    $scope.propertyplace=words[0]+' '+words[1]+' '+words[2];
                                }
                            });
                            ajaxService.ajax_post({
                                request:'addnewproperty',
                                userid:$rootScope.UserDetails.user_id,
                                propertyplace:$scope.propertyplace,
                                permanentquestion:localStorage.getItem('Questions'),
                                tempquestion:localStorage.getItem('tempQuestions')
                            }).then(function (response) {
                                console.log(response);
                                if(response.data.status=="true")
                                {
                                    $ionicLoading.hide();
                                    localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                                    $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
                                    localStorage.setItem('Questions','');
                                    localStorage.setItem('tempQuestions','');
                                    $scope.formData=(localStorage.getItem('Questions')==null || localStorage.getItem('Questions')=='' ) ? {parameters:{}} : JSON.parse(localStorage.getItem('Questions'))  ;
                                    $scope.tempanswers=(localStorage.getItem('tempQuestions')==null || localStorage.getItem('tempQuestions')=='' ) ? [] : JSON.parse(localStorage.getItem('tempQuestions'));
                                    $scope.temparayquestion="hide";
                                    $scope.questionshow=0;
                                    $scope.textanswer='';
                                    $scope.tempquestions=''
                                    $scope.closeModal();
                                    $cordovaToast.show('Successfully added !','long', 'center').then(function(success) {
                                        return;
                                    }, function (error) {
                                        return;
                                    });

//                        $state.go('PermanentQuestions');
                                }
                                else if(response.data.status=="Invalid details")
                                {
                                    $ionicLoading.hide();
                                    $cordovaToast.show('Invalid Details ! error','short', 'center').then(function(success) {
                                        return;
                                    }, function (error) {
                                        return;
                                    });
                                }
                                else
                                {
                                    $ionicLoading.hide();
                                    $cordovaToast.show('Please try again ! error','short', 'center').then(function(success) {
                                        return;
                                    }, function (error) {
                                        return;
                                    });
                                }
                            });
                        });

                    }
                    else
                    {
                        localStorage.setItem('Questions',JSON.stringify($scope.formData));
                        $scope.temparayquestion="show";
                    }
                }
            }

        }
//permanemt end
//        temparay

        $scope.questioninsertion=function(item)
        {
            if($scope.tempanswers.length==0)
            {
                $scope.tempanswers.push($scope.tempquestions);
                $scope.tempquestions='';
            }
            else
            {
                $scope.textanswer='';
                var i=0;
                console.log(item);
                console.log($scope.tempanswers);
                angular.forEach($scope.tempanswers,function(value,key){
                    console.log(value.index);
                    if(value.index==item)
                    {
                        var index =  $scope.tempanswers.indexOf(value);
                        $scope.tempanswers.splice(index, 1);
                        $scope.tempanswers.push($scope.tempquestions);
                        i=1;
                    }

                })
                if(i==0)
                {
                    $scope.tempanswers.push($scope.tempquestions);
                }
                $scope.tempquestions='';
            }
        }
        $scope.next=function(item){
//            $scope.activeanswer=false;
            angular.forEach($scope.tempanswers,function(value,key){
                if(value.index==item)
                {
                    $scope.answernotempty=1;
                }
            });
            if($scope.maxquestion==item+1)
            {
                if( $scope.tempquestions!='' ||  $scope.answernotempty==1)
                {
                    $scope.questioninsertion(item);
                    localStorage.setItem('tempQuestions',JSON.stringify($scope.tempanswers));
                    $ionicLoading.show();
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
                            if(words[0]==$scope.propertyno)
                            {
                                $scope.propertyplace=words[0]+' '+words[1]+' '+words[2];
                            }
                        });
                    ajaxService.ajax_post({
                        request:'addnewproperty',
                        userid:$rootScope.UserDetails.user_id,
                        propertyplace:$scope.propertyplace,
                        permanentquestion:localStorage.getItem('Questions'),
                        tempquestion:localStorage.getItem('tempQuestions')
                    }).then(function (response) {
                        console.log(response);
                        if(response.data.status=="true")
                        {
                            $ionicLoading.hide();
                            localStorage.setItem('propertydetails',JSON.stringify(response.data.propertydetails));
                            $rootScope.propertydetails=JSON.parse(localStorage.getItem('propertydetails'));
                            localStorage.setItem('Questions','');
                            localStorage.setItem('tempQuestions','');
                            $scope.formData=(localStorage.getItem('Questions')==null || localStorage.getItem('Questions')=='' ) ? {parameters:{}} : JSON.parse(localStorage.getItem('Questions'))  ;
                            $scope.tempanswers=(localStorage.getItem('tempQuestions')==null || localStorage.getItem('tempQuestions')=='' ) ? [] : JSON.parse(localStorage.getItem('tempQuestions'));
                            $scope.temparayquestion="hide";
                            $scope.questionshow=0;
                            $scope.textanswer='';
                            $scope.tempquestions=''
                            $scope.closeModal();
                            $cordovaToast.show('Successfully added !','long', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });

//                        $state.go('PermanentQuestions');
                        }
                        else if(response.data.status=="Invalid details")
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Invalid Details ! error','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                        else
                        {
                            $ionicLoading.hide();
                            $cordovaToast.show('Please try again ! error','short', 'center').then(function(success) {
                                return;
                            }, function (error) {
                                return;
                            });
                        }
                    });
                    });
                }
                else
                {
                    $cordovaToast.show('please fill your answer','short', 'center').then(function(success) {
                        return;
                    }, function (error) {
                        return;
                    });
                }

            }
            else{
                if( $scope.tempquestions!='' ||  $scope.answernotempty==1)
                {
                    $scope.questioninsertion(item);
                    $scope.questionshow=item+1;
                    var i=0;
                    angular.forEach($scope.tempanswers,function(value,key){

                        if(value.index==$scope.questionshow)
                        {
                            $scope.style=value.style;
                            $scope.textanswer=value.answer;
                            i++;
                        }
                    });
                    if(i==0)
                    {
                        $scope.textanswer='';
                    }
                }
                else
                {
                    $cordovaToast.show('please fill your answer','short', 'center').then(function(success) {
                        return;
                    }, function (error) {
                        return;
                    });
                }

            }
        }
        $scope.skip=function(item){

            if($scope.maxquestion==item+1)
            {
                localStorage.setItem('tempQuestions',JSON.stringify($scope.tempanswers));
//                $state.go('Register')
            }
            else{
                $scope.questionshow=item+1;
                var i=0
                angular.forEach($scope.tempanswers,function(value,key){
                    $scope.textanswer='';
                    if(value.index==$scope.questionshow)
                    {
                        $scope.style=value.style;
                        $scope.textanswer=value.answer;
                        i++;
                    }
                });
                if(i==0)
                {
                    $scope.textanswer='';
                }
            }
        }
        $scope.backquestion=function(item)
        {
            if(item==0)
            {
                $scope.temparayquestion="hide";
            }
            else
            {
                $scope.questionshow=item-1;
                var i=0;
                angular.forEach($scope.tempanswers,function(value,key){
                    if(value.index==$scope.questionshow)
                    {
                        $scope.style=value.style;
                        $scope.textanswer=value.answer
                        i++;
                    }
                })
                if(i==0)
                {
                    $scope.textanswer='';
                }
            }
        }
        $scope.selectannswer=function(index,quesrtionid,answer,type)
        {
//            $scope.activeanswer=true;
            $scope.style=index.toString()+quesrtionid.toString();
            if(type==1 || type==4)
                $scope.tempquestions={style:$scope.style,id:quesrtionid,answer:answer,index:$scope.questionshow};
            if(type==2)
                $scope.tempquestions={style:'',id:quesrtionid,answer:answer,index:$scope.questionshow};
            if(type==3)
            {
                $scope.style=answer+quesrtionid.toString();;
                $scope.tempquestions={style:$scope.style,id:quesrtionid,answer:answer,index:$scope.questionshow};
            }
        }
//        temparay end


        $scope.gotopropertyinner=function(item)
        {
            ajaxService.ajax_post({
                request:'reason'
            }).then(function (response) {
                console.log(response);
                if(response.data.status=="true")
                {
                  $rootScope.reasons=response.data.reason;
                }
            })
            $state.go('app.propertyinner');
            $rootScope.innerdetails=item;
        }

});
