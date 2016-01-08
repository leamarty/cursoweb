cursoweb.controller('ClienteListado', function ($scope) {
    $scope.$parent.menuActivo = 'cliente_listado';

    $scope.clientes = [
        {id: 1, razon_social: 'puto', saldo_positivo: 333, saldo_negativo: 555},
        {id: 2, razon_social: 'el', saldo_positivo: 345345, saldo_negativo: 32133},
        {id: 3, razon_social: 'que', saldo_positivo: 1, saldo_negativo: 9999},
        {id: 4, razon_social: 'lee', saldo_positivo: 55, saldo_negativo: 8746},
        {id: 5, razon_social: 'por', saldo_positivo: 456, saldo_negativo: 12},
        {id: 6, razon_social: 'que', saldo_positivo: 1234, saldo_negativo: 4},
        {id: 7, razon_social: 'mierda', saldo_positivo: 987, saldo_negativo: 684},
        {id: 8, razon_social: 'estas', saldo_positivo: 142536, saldo_negativo: 12358},
        {id: 9, razon_social: 'leyendo', saldo_positivo: 3233, saldo_negativo: 55555},
        {id: 10, razon_social: 'esto', saldo_positivo: 321, saldo_negativo: 777}
    ];

    $scope.headers = [
        'ID',
        'Razon Social',
        'Saldo Positivo',
        'Saldo Negativo',
        'Borrar'
    ]
});