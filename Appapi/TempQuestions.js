pig
.controller('TempQuestionsCtrl', function($scope,$state,$rootScope,$cordovaToast,$ionicPopup) {
        $scope.$on("$ionicView.loaded", function(event, data){
            $scope.questionshow=0;
            $scope.maxquestion=$rootScope.tempararyquestions.length;
            $scope.textanswer='';
            $scope.tempquestions=''
//            $scope.activeanswer=false;
            console.log(localStorage.getItem('tempQuestions'));
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
            $scope.questionshow=0;
            $scope.maxquestion=$rootScope.tempararyquestions.length;
            $scope.textanswer='';
            $scope.tempquestions=''
//            $scope.activeanswer=false;
            console.log(localStorage.getItem('tempQuestions'));
            $scope.tempanswers=(localStorage.getItem('tempQuestions')==null || localStorage.getItem('tempQuestions')=='' ) ? [] : JSON.parse(localStorage.getItem('tempQuestions'));
            angular.forEach($scope.tempanswers,function(value,key){
                if(value.index==$scope.questionshow)
                {
                    $scope.style=value.style;
                    $scope.textanswer=value.answer
                }
            })
        });




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
//            $scope.activeanswer=
            $scope.answernotempty=0;
            angular.forEach($scope.tempanswers,function(value,key){
                if(value.index==item)
                {
                   $scope.answernotempty=1;
                }
            });
            if($scope.maxquestion==item+1)
                {
                    if( $scope.tempquestions!='' || $scope.answernotempty==1)
                    {

                        $scope.questioninsertion(item);
                        localStorage.setItem('tempQuestions',JSON.stringify($scope.tempanswers));
                        var myPopup = $ionicPopup.show({
                            template: 'You will have to register to get the value.',
                            title: 'Property Pig',
                            scope: $scope,
                            buttons: [
                                { text: 'Cancel',
                                    type: 'my-postive-color'},
                                {
                                    text: '<b>Continue</b>',
                                    type: 'my-postive-color',
                                    onTap: function(e) {
                                        $state.go('Register');
                                    }
                                }
                            ]
                        });

                        myPopup.then(function(res) {
                            console.log('Tapped!', res);
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

                if( $scope.tempquestions!='' || $scope.answernotempty==1)
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
                $state.go('Register')
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
                $state.go('PermanentQuestions')
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
//            alert(answer);
            $scope.style=index.toString()+quesrtionid.toString();
            if(type==1 || type==4)
            $scope.tempquestions={style:$scope.style,id:quesrtionid,answer:answer,index:$scope.questionshow};
            if(type==2)
                $scope.tempquestions={style:'',id:quesrtionid,answer:answer,index:$scope.questionshow};
            if(type==3)
                $scope.style=answer+quesrtionid.toString();
                $scope.tempquestions={style:$scope.style,id:quesrtionid,answer:answer,index:$scope.questionshow};
        }

});
