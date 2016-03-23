angular.module('app.controllers')
    .controller('ProjectsNewController',
    ['$scope','$location','$cookies','Projects','Client','appConfig',function($scope,$location,$cookies,Projects,Client,appConfig){
        $scope.projects = new Projects();
        $scope.clients  = Client.query();
        $scope.status   = appConfig.projects.status;

        $scope.save = function () {
            if($scope.form.$valid){
                $scope.projects.owner_id = $cookies.getObject('user').id;
            $scope.projects.$save().then(function(){
                $location.path('/projects');
            });
            }
        }
    }]);