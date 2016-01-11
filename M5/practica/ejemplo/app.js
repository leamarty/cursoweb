var cursoweb = angular.module('cursoweb', [
    'ngRoute',
    'ui.select2',
    'restangular'
]);

cursoweb.run(function (uiSelect2Config) {
    uiSelect2Config.allowClear = true;
});

cursoweb.config(function($routeProvider) {
    $routeProvider
        .when('/cliente', {
            templateUrl: 'cliente/listado.html',
            controller: 'ClienteListadoController'
        })
        .when('/cliente/nuevo', {
            templateUrl: 'cliente/nuevo.html',
            controller: 'ClienteNuevoController',
            resolve: {
                delay: function($q, $timeout) {
                    var delay = $q.defer();
                    $timeout(delay.resolve, 1000);
                    return delay.promise;
                }
            }
        });
});

cursoweb.controller('MainController', function($scope) {
    $scope.menuActivo = 'cliente_listado';
});

cursoweb.controller('ClienteListadoController', function($scope, $http) {
    $scope.$parent.menuActivo = 'cliente_listado';

    $scope.clientes = {
        impar: [],
        par: []
    };

    $scope.buscando = 0;
    $scope.borrando = {};

    $scope.buscar = function () {
        $scope.obtenerClientes('impar');
        $scope.obtenerClientes('par');
    };

    $scope.obtenerClientes = function (parImpar) {
        $scope.buscando++;
        var lista = $scope.clientes[parImpar];
        var parametros = {
            desde: (!lista.length) ? 0 : (lista[lista.length - 1].id + 1),
            cantidad: 5,
            filtro: parImpar
        };
        $http.get('../api/cliente', {params: parametros}).success(function (clientes) {
            $scope.clientes[parImpar] = $scope.clientes[parImpar].concat(clientes);
            $scope.buscando--;
        });
    };

    $scope.borrar = function (cliente, index) {
        $scope.borrando[cliente.id] = true;
        $http.delete('../api/cliente/' + cliente.id).success(function () {
            $scope.clientes[cliente.id % 2 ? 'impar' : 'par'].splice(index, 1);
            $scope.borrando[cliente.id] = false;
        });
    };
});

/*
if (tablaClientesBody.find('tr').length == 1) {
    tablaClientesBody.html('');
}
*/

cursoweb.controller('ClienteNuevoController', function($scope) {
    $scope.$parent.menuActivo = 'cliente_nuevo';

    $(document).ready(function () {
        $('#menu-nuevo').click(function () {
            $('#div-listado').hide();
            $('#div-nuevo').show();
            $('#menu-listado').removeClass('active');
            $('#menu-nuevo').addClass('active');
        });

        $.getJSON('api/presidente', function(response) {
            $(response).each(function(key, presidente) {
                $('#presidente_id').append(
                    $('<option>').attr('value', presidente.id).text(presidente.nombre + ' ' + presidente.apellido)
                )
            });
        });
    });
});