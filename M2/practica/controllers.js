var clientesProviderApp = angular.module('clientesProviderApp', []);

function pedir_clientes($scope, $http, paridad) {
    $http.get(
        'clientes.php', {
            cache: false,
            params: {
                desde: $("tbody#tbody-" + paridad).children("tr").length * 2 + 1,
                cantidad: 10,
                filtro: paridad
            }
        }).success(function (data) {
            $(data).each(function () {
                $scope.clientes.push(this);
            });
        });
}

clientesProviderApp.controller('ClientesListCtrl', ['$scope', '$http',

    function ($scope, $http) {

        $scope.pedirClientes = function () {
            for (var paridad in {impar: "", par: ""}) {
                pedir_clientes($scope, $http, paridad);
            }
        };
    }]);

clientesProviderApp.filter("filtrarParidad", function () {
    return function (input, paridad) {
        var r = [];
        for (var i in input) {
            if (input[i].id % 2 == (paridad == "impar" ? 1 : 0)) {
                r.push(input[i]);
            }
        }
        return r;
    }
});