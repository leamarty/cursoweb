cursoweb.controller('ClienteNuevo', function ($scope, Cliente, Paises, Presidentes) {
    $scope.$parent.menuActivo = 'cliente_nuevo';

    $scope.paises = Paises;
    $scope.presidentes = Presidentes;

    $scope.cliente = {};

    $scope.guardar = function () {
        Cliente.post($scope.cliente);
    };
});
