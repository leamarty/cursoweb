var cursoweb = angular.module('cursoweb', [
    'ngRoute',
    'ui.select2',
    'restangular',
    'underscore'
]);

cursoweb.config(function (RestangularProvider) {
    RestangularProvider.setBaseUrl('api/');
});
