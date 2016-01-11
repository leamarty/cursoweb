cursoweb.config(function ($routeProvider) {
    $routeProvider
        .when('/cliente/listado', {
            templateUrl: 'html/cliente/listado.html',
            controller: 'ClienteListado'
        })
        .when('/cliente/nuevo', {
            templateUrl: 'html/cliente/nuevo.html',
            controller: 'ClienteNuevo',
            resolve: {
                Paises: function (Pais) {return Pais.getList();},
                Presidentes: function (Presidente) {return Presidente.getList();}
            }
        })
        .when('/cliente/:id', {
            templateUrl: 'html/cliente/nuevo.html',
            controller: 'ClienteEditar',
            resolve: {
                Cliente: function(Cliente, $route) {return Cliente.one($route.current.params.id).get();},
                Paises: function (Pais) {return Pais.getList();},
                Presidentes: function (Presidente) {return Presidente.getList();}
            }
        })
        .otherwise({
            redirectTo: '/cliente/listado'
        })
});