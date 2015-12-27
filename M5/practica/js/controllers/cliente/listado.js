cursoweb.controller('ClienteListado', function ($scope) {
    $scope.$parent.menuActivo = 'cliente_listado';

    $scope.clientes = [
        {id: 5, razon_social: 'hola'}
    ]
});