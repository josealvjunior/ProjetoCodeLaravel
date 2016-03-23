angular.module('app.controllers')
    .controller('ProjectsListController',['$scope','Projects','Client','appConfig',function($scope,Projects,Client,appConfig){
        $scope.projects = Projects.query();
        $scope.clients  = Client.query();
        $scope.status   = appConfig.projects.status;
    }]);