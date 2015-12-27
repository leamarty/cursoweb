cursoweb.directive('ocultar', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            setTimeout(function () {
                element.hide();
            }, (attrs.ocultarTiempo || 2) * 1000);
        }
    };
});
