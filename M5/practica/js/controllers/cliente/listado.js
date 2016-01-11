cursoweb.controller('ClienteListado', function ($scope, Cliente) {
    $scope.$parent.menuActivo = 'cliente_listado';

    $scope.clientes = [];

    $scope.borrar = function (cliente, index) {
        Cliente.one(cliente.id).remove().then(function () {
            $scope.clientes.splice(index, 1);
            alert('Se ha borrado correctamente el cliente ' + cliente.razon_social);
        });
    };

    $scope.buscar = function () {
        Cliente.getList().then(function (result) {
            $scope.clientes = result;
        });
    };

});