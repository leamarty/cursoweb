cursoweb.controller('ClienteEditar', function ($scope, $location, Cliente, Paises, Presidentes) {
    $scope.$parent.menuActivo = 'cliente_nuevo';

    $scope.paises = Paises;
    $scope.presidentes = Presidentes;

    $scope.cliente = Cliente;

    $scope.guardar = function () {
        $scope.cliente.put().then(function (result) {
            alert('El cliente ' + $scope.cliente.razon_social + ' se ha modificado correctamente');
            $location.path('/cliente/listado');
        });
    };
});
