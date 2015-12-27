cursoweb.config(function ($routeProvider) {
    $routeProvider
        .when('/cliente/listado', {
            templateUrl: 'html/cliente/listado.html',
            controller: 'ClienteListado'
        })
        .when('/cliente/nuevo', {
            templateUrl: 'html/cliente/nuevo.html',
            controller: 'ClienteNuevo'
        })
        /*
        .when('/cliente/:id', {
            templateUrl: '',
            controller: ''
        })
        */
        .otherwise({
            redirectTo: '/cliente/listado'
        })
});