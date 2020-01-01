app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    //CUSTOMER
    when('/sms-pkg/sms/list', {
        template: '<sms-list></sms-list>',
        title: 'Smss',
    }).
    when('/sms-pkg/sms/add', {
        template: '<sms-form></sms-form>',
        title: 'Add Sms',
    }).
    when('/sms-pkg/sms/edit/:id', {
        template: '<sms-form></sms-form>',
        title: 'Edit Sms',
    });
}]);