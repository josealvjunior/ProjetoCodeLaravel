angular.module('app.controllers')
    .controller('ProjectsRemoveController',
    ['$scope','$location','$routeParams','Projects',
        function($scope,$location,$routeParams, Projects){
        $scope.projects = Projects.get({id: $routeParams.id});

        $scope.remove = function () {
            $scope.projects.$delete({id: $scope.projects.id}).then(function(){
                $location.path('/projects/');
            });
        }
    }]);