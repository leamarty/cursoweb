cursoweb.controller('ClienteNuevo', function ($scope) {
    $scope.$parent.menuActivo = 'cliente_nuevo';

    $scope.inputs = [
        { id: 'username',
        symbol: '@',
        type: 'text',
        placeholder: 'Username' },

        { id: 'password',
        symbol: '●',
        type: 'password',
        placeholder: 'Password' },

        { id: 'saldo_positivo',
        symbol: '$+',
        type: 'text',
        placeholder: 'Saldo Positivo' },

        { id: 'saldo_negativo',
        symbol: '$-',
        type: 'text',
        placeholder: 'Saldo Negativo' }
    ];
});