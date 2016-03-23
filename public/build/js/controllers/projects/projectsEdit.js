angular.module('app.controllers')
    .controller('ProjectsEditController',
    ['$scope','$routeParams','$location','$cookies','Projects','Client','appConfig',
        function($scope,$routeParams,$location,$cookies,Projects,Client,appConfig){
        $scope.projects = Projects.get({id: $routeParams.id});
        $scope.clients  = Client.query();
        $scope.status   = appConfig.projects.status;

        $scope.save = function () {
            if($scope.form.$valid){
                $scope.projects.owner_id = $cookies.getObject('user').id;
            Projects.update({id: $scope.projects.id}, $scope.projects,function(){
                $location.path('/projects');
            });
            }
        }
    }]);