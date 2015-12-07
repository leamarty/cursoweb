var clientesProviderApp = angular.module('clientesProviderApp', ['ui.bootstrap']);

clientesProviderApp.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);

clientesProviderApp.controller('ListadoCtrl', ['$scope', '$http',
    function ($scope, $http) {

        /*
        $scope.mostrar_alert = function (tab, id) {
            $('div#alerts-container-' + tab).children('div:not(#' + id + ')').hide().parent().children('div#' + id).show();
            // $('div#alerts-container-' + tab).children('div').hide().parent().children('div#' + id).show();
        };
        */

        $scope.boton_buscar_disabled = false;
        $scope.alert_shown = 'realice-busqueda';

        $scope.boton_buscar = function () {

            if ($scope.boton_buscar_disabled) {
                return;
            }

            $scope.boton_buscar_disabled = true;
            $scope.alert_shown = 'comprobando-disponibilidad';

            $http.get('http://ccvalsina.com/cursoweb/autorizacion.php', {cache: false}
            ).error(function (data) {
                if (data.status == 401) {
                    $scope.alert_shown = 'ocupado';
                    $scope.boton_buscar_disabled = false;
                } else {
                    $scope.alert_shown = 'disponible';
                    $scope.pedir_clientes();
                }
            }).success(function () {
                $scope.alert_shown = 'disponible';
                $scope.pedir_clientes();
            });
        };

        $scope.clientes = [];

        $scope.pedir_clientes = function () {

            var impar_encontrado = 1;
            var par_encontrado = 2;

            for (var i = $scope.clientes.length - 1; i >= 0; i--) {
                if (impar_encontrado == 1 && parseInt($scope.clientes[i].id) % 2 == 1) {
                    impar_encontrado = parseInt($scope.clientes[i].id) + 2;
                }
                if (par_encontrado == 2 && parseInt($scope.clientes[i].id) % 2 == 0) {
                    par_encontrado = parseInt($scope.clientes[i].id) + 2;
                }
                if (impar_encontrado != 1 && par_encontrado != 2) {
                    break;
                }
            }

            function pedir_posta (desde) {
                $http.get('api/cliente/' + desde + '/5', {cache: false}
                ).error(function () {
                    $scope.alert_shown = 'error';
                    $scope.boton_buscar_disabled = false;
                }).success(function (data) {
                    angular.forEach(data, function (cliente) {
                        var exists = false;
                        for (var i = $scope.clientes.length - 1; i >= 0; i--) {
                           if ($scope.clientes[i].id == cliente.id) {
                               exists = true;
                               break;
                           }
                        }
                        if (!exists) {
                            $scope.clientes.push(cliente);
                        }
                    });
                    if (data.length < 5) {
                        $scope.verificar_clientes_agotados();
                    }
                    $scope.habilitar_boton_buscar();
                });
            }

            pedir_posta(impar_encontrado);
            pedir_posta(par_encontrado);
        };

        $scope.tablas_cargadas = 0;

        $scope.habilitar_boton_buscar = function () {

            if (++$scope.tablas_cargadas >= 2) {
                if ($scope.clientes_agotados >= 1) {
                    $scope.clientes_agotados = 0;
                }
                $scope.tablas_cargadas = 0;
                $scope.boton_buscar_disabled = false;
            }
        };

        $scope.clientes_agotados = 0;

        $scope.verificar_clientes_agotados = function () {

            if (++$scope.clientes_agotados >= 2) {
                $scope.clientes_agotados = 0;
                $scope.alert_shown = 'agotado';
            }
        };

        $scope.remove_cliente_warning_shown = false;

        $scope.borrar_cliente = function (id) {
            if (!$scope.remove_cliente_warning_shown) {
                $scope.remove_cliente_warning_shown = true;
            } else {
                $http.delete('api/cliente/' + id
                ).error(function () {
                    $scope.alert_shown = 'error';
                }).success(function () {
                    for (var i = $scope.clientes.length - 1; i >= 0; i--) {
                        if ($scope.clientes[i].id == id) {
                            $scope.clientes.splice(i, 1);
                            break;
                        }
                    }
                });
            }
        };
    }
]);

clientesProviderApp.filter('filtrarParidad', function () {
    return function (input, paridad) {
        var r = [];
        for (var i in input) {
            if (input[i].id % 2 == (paridad == 'impar' ? 1 : 0)) {
                r.push(input[i]);
            }
        }
        return r;
    }
});

clientesProviderApp.controller('SubtitleCtrl', ['$scope', '$http',
    function ($scope, $http) {

        $scope.subtitulo = 'Listado de clientes oficiales';

        /*
        si el usuario clickea afuera del texto (pero dentro del <a>, no se llama a esta funcion
        http://stackoverflow.com/questions/14943644/angularjs-how-to-watch-tab-selection
        */
        $scope.modificar_subtitulo = function (id) {
            if (id == 'listado') {
                $scope.subtitulo = 'Listado de clientes oficiales';
            } else if (id == 'nuevo') {
                $scope.subtitulo = 'Formulario de alta para nuevo cliente';
                if (!$scope.presidentes.length && !$scope.presidentes_cargando) {
                    $scope.presidentes_cargando = true;
                    $scope.cargar_presidentes();
                }
                if (!$scope.ciudades.length && !$scope.ciudades_cargando) {
                    $scope.ciudades_cargando = true;
                    $scope.cargar_ciudades();
                }
            }
        };

        $scope.presidentes = [];
        $scope.presidentes_cargando = false;

        $scope.cargar_presidentes = function() {
            $http.get('api/presidente', {cache: false}
            ).error(function (data) {
                $scope.presidentes_cargando = false;
            }).success(function (data) {
                $scope.presidentes = data;
                $scope.presidentes_cargando = false;
            });
        };

        $scope.ciudades = [];
        $scope.ciudades_cargando = false;

        $scope.cargar_ciudades = function() {
            $http.get('api/ciudad', {cache: false}
            ).error(function (data) {
                $scope.ciudades_cargando = false;
            }).success(function (data) {
                $scope.ciudades = data;
                $scope.ciudades_cargando = false;
            });
        };
    }
]);

clientesProviderApp.controller('NuevoCtrl', ['$scope', '$http',
    function ($scope, $http) {

        $scope.alert_shown = '';
        $scope.campos = [];

        // puto el que lee

        $scope.alta_cliente = function () {
            $http.post('api/cliente', $scope.campos
            ).error(function () {
                $scope.alert_shown = 'cargado-error';
            }).success(function () {
                $scope.campos = [];
                $scope.alert_shown = 'cargado-exito';
            });
        };
    }
]);
