angular.module('app.controllers')
    .controller('ProjectsNewController',
    ['$scope','$location','$cookies','Projects','Client','appConfig',function($scope,$location,$cookies,Projects,Client,appConfig){
        $scope.projects = new Projects();
        $scope.status   = appConfig.projects.status;

        $scope.due_date = {
          status:{
              opened: false
          }
        };
        $scope.open=function($event){
            $scope.due_date.status.opened = true;
        };

        $scope.save = function () {
            if($scope.form.$valid){
                $scope.projects.owner_id = $cookies.getObject('user').id;
            $scope.projects.$save().then(function(){
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