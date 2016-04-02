var app = angular.module('app',
    ['ngRoute','app.controllers', 'angular-oauth2', 'app.services','app.filters'
]);

angular.module('app.controllers',['ngMessages','angular-oauth2']);
angular.module('app.filters',[]);
angular.module('app.services',['ngResource']);

app.provider('appConfig', ['$httpParamSerializerProvider', function($httpParamSerializerProvider) {
   var config = {
       baseUrl: 'http://localhost/projeto/public',
       projects:{
           status:[
               {value: 1, label: 'não iniciado'},
               {value: 2, label: 'Iniciado'},
               {value: 3, label: 'Concluído'},
           ]
       },
       utils:{
           transformRequest: function(data){
             if(angular.isObject(data)){
                 return $httpParamSerializerProvider.$get()(data);
             }
               return data;
           },
           transformResponse: function(data, headers){
               var headersGetter = headers();
               if(headersGetter['content-type'] =='application/json' ||
                   headersGetter['content-type'] == 'text/json') {
                   var dataJson = JSON.parse(data);
                   if(dataJson.hasOwnProperty('data')){
                       dataJson = dataJson.data;
                   }
                   return dataJson;
               }
               return data;
           }
       }
   } ;
    return {
        config: config,
        $get: function(){
            return config;
        }
    }
}]);

app.config([
    '$routeProvider','$httpProvider','OAuthProvider',
    'OAuthTokenProvider','appConfigProvider',
    function($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider){
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.transformRequest = appConfigProvider.config.utils.transformRequest;
        $httpProvider.defaults.transformResponse = appConfigProvider.config.utils.transformResponse;
        $routeProvider
        .when('/login', {
            templateUrl: 'build/views/login.html',
            controller: 'LoginController'
        })
        .when('/home',{
            templateUrl: 'build/views/home.html',
            controller: 'HomeController'
        })
        /*Rotas Clients */
        .when('/clients', {
          templateUrl: 'build/views/client/list.html',
          controller: 'ClientListController'
        })
        .when('/clients/new', {
            templateUrl: 'build/views/client/new.html',
            controller: 'ClientNewController'
        })
        .when('/clients/:id/show', {
            templateUrl: 'build/views/client/show.html',
            controller: 'ClientShowController'
        })
        .when('/clients/:id/edit', {
            templateUrl: 'build/views/client/edit.html',
            controller: 'ClientEditController'
        })
        .when('/clients/:id/remove', {
            templateUrl: 'build/views/client/remove.html',
            controller: 'ClientRemoveController'
        })
            /*Rotas Projects */
            .when('/projects', {
                templateUrl: 'build/views/projects/list.html',
                controller: 'ProjectsListController'
            })
            .when('/projects/new', {
                templateUrl: 'build/views/projects/new.html',
                controller: 'ProjectsNewController'
            })
            .when('/projects/:id/show', {
                templateUrl: 'build/views/projects/show.html',
                controller: 'ProjectsShowController'
            })
            .when('/projects/:id/edit', {
                templateUrl: 'build/views/projects/edit.html',
                controller: 'ProjectsEditController'
            })
            .when('/projects/:id/remove', {
                templateUrl: 'build/views/projects/remove.html',
                controller: 'ProjectsRemoveController'
            })

        /* Rotas de projects notes  */
        .when('/projects/:id/notes', {
            templateUrl: 'build/views/projectNotes/list.html',
            controller: 'ProjectNotesListController'
        })
        .when('/projects/:id/notes/new', {
            templateUrl: 'build/views/projectNotes/new.html',
            controller: 'ProjectNotesNewController'
        })
        .when('/projects/:id/notes/:noteId/show', {
            templateUrl: 'build/views/projectNotes/show.html',
            controller: 'ProjectNotesShowController'
        })
        .when('/projects/:id/notes/:noteId/edit', {
            templateUrl: 'build/views/projectNotes/edit.html',
            controller: 'ProjectNotesEditController'
        })
        .when('/projects/:id/notes/:noteId/remove', {
            templateUrl: 'build/views/projectNotes/remove.html',
            controller: 'ProjectNotesRemoveController'
        });


    OAuthProvider.configure({
        baseUrl: appConfigProvider.config.baseUrl,
        clientId: 'appid1',
        clientSecret: 'secret', // optional
        grantPath: 'oauth/access_token'
    });
    OAuthTokenProvider.configure({
        name: 'token',
        options: {
            secure: false
        }
    });
}]);

app.run(['$rootScope', '$window', 'OAuth', function($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function(event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);