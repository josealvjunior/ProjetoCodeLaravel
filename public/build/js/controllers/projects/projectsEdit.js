angular.module('app.controllers')
    .controller('ProjectsEditController',
    ['$scope','$routeParams','$location','$cookies','Projects','Client','appConfig',
        function($scope,$routeParams,$location,$cookies,Projects,Client,appConfig){
        Projects.get({id: $routeParams.id}, function(data){
            $scope.projects = data;
            $scope.clientSelected = data.client;
        });

        $scope.status   = appConfig.projects.status;

        $scope.save = function () {
            if($scope.form.$valid){
                $scope.projects.owner_id = $cookies.getObject('user').id;
            Projects.update({id: $scope.projects.id}, $scope.projects,function(){
                $location.path('/projects');
            });
            }
        };
        $scope.formatName = function(model){
                if(model){
                    return model.name;
                }
                return '';
        };
        $scope.getClients = function(name){
                return Client.query({
                    search: name,
                    searchFields: 'name:like'
                }).$promise;
        };
            $scope.selectClient= function(item){
                $scope.projects.client_id = item.id;
            };
    }]);