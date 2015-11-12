var clientesProviderApp = angular.module('clientesProviderApp', []);

clientesProviderApp.controller('ClientesListCtrl', ['$scope', '$http',

    function ($scope, $http) {

        $scope.pedirClientes = function () {
            $http.get(
                'clientes.php', {
                    cache: false,
                    params: {
                        desde: $scope.desde,
                        cantidad: $scope.cantidad,
                        filtro: "impar"
                    }
                }).success(function (data) {
                    $(data).each(function () {
                        $scope.clientes.push(this);
                    });
                    // scope.desde = scope.desde + scope.cantidad;
                });
            $http.get(
                'clientes.php', {
                    cache: false,
                    params: {
                        desde: $scope.desde,
                        cantidad: $scope.cantidad,
                        filtro: "par"
                    }
                }).success(function (data) {
                    $(data).each(function () {
                        $scope.clientes.push(this);
                    });
                    // scope.desde = scope.desde + scope.cantidad;
                });
        };
    }]);

clientesProviderApp.filter('impar', function() {
    return function (input) {
        var r = [];
        for (var i in input) {
            if (input[i].id % 2 == 1) {
                r.push(input[i]);
            }
        }
        return r;
    }
});

clientesProviderApp.filter('par', function() {
    return function (input) {
        var r = [];
        for (var i in input) {
            if (input[i].id % 2 == 0) {
                r.push(input[i]);
            }
        }
        return r;
    }
});
