cursoweb.controller('MainController', function ($scope, $timeout) {

    $scope.menuActivo = '';

    $scope.clientes = [];

    $scope.buscar = function () {
        $scope.clientes.push({
            id: $scope.clientes.length + 1,
            nombre: 'Pepe',
            edad: 5
        });
    };

    $scope.borrar = function (cliente, index) {
        $scope.clientes.splice(index, 1);
        alert('Ya borre el cliente ' + cliente.nombre);
    };

});