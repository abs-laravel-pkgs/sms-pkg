app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    //CUSTOMER
    when('/sms-pkg/sms-template/list', {
        template: '<sms-template-list></sms-template-list>',
        title: 'SMS Templates',
    }).
    when('/sms-pkg/sms-template/add', {
        template: '<sms-template-form></sms-template-form>',
        title: 'Add SMS Template',
    }).
    when('/sms-pkg/sms-template/edit/:id', {
        template: '<sms-template-form></sms-template-form>',
        title: 'Edit SMS Template',
    });
}]);

app.component('smsTemplateList', {
    templateUrl: sms_template_list_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $location) {
        $scope.loading = true;
        var self = this;
        self.theme = admin_theme;
        self.hasPermission = HelperService.hasPermission;
        var dataTable = $('#sms_template_list').DataTable({
            "dom": dom_structure,
            "language": {
                "search": "",
                "searchPlaceholder": "Search",
                "lengthMenu": "Rows Per Page _MENU_",
                "paginate": {
                    "next": '<i class="icon ion-ios-arrow-forward"></i>',
                    "previous": '<i class="icon ion-ios-arrow-back"></i>'
                },
            },
            stateSave: true,
            pageLength: 10,
            processing: true,
            serverSide: true,
            paging: true,
            ordering: false,
            ajax: {
                url: laravel_routes['getSmsTemplateList'],
                type: "GET",
                dataType: "json",
                data: function(d) {
                    d.sms_name = $("#sms_name").val();
                }
            },
            columns: [
                { data: 'action', searchable: false, class: 'action' },
                { data: 'name', name: 'sms_templates.name', searchable: true },
                { data: 'description', name: 'sms_templates.description', searchable: true },
            ],
            "infoCallback": function(settings, start, end, max, total, pre) {
                $('#table_info').html(total + '/' + max)
            },
            rowCallback: function(row, data) {
                $(row).addClass('highlight-row');
            },
            initComplete: function() {
                $('.search label input').focus();
            },
        });
        $('.dataTables_length select').select2();

        /* Page Title Appended */
        $('.page-header-content .display-inline-block .data-table-title').html('SMS Templates <span class="badge badge-secondary" id="table_info">0</span>');
        $('.page-header-content .search.display-inline-block .add_close_button').html('<button type="button" class="btn btn-img btn-add-close"><img src="' + image_scr2 + '" class="img-responsive"></button>');
        $('.page-header-content .refresh.display-inline-block').html('<button type="button" class="btn btn-refresh"><img src="' + image_scr3 + '" class="img-responsive"></button>');
        /* Add & Filter Button Appended */
        $('.page-header-content .alignment-right .add_new_button').html(
            '<a href="#!/sms-pkg/sms-template/add" role="button" class="btn btn-secondary">Add New</a>' +
            '<a role="button" id="open" data-toggle="modal"  data-target="#sms-tempalte-filter" class="btn btn-img"> <img src="' + image_scr + '" alt="Filter" onmouseover=this.src="' + image_scr1 + '" onmouseout=this.src="' + image_scr + '"></a>'
        );
        $('.btn-add-close').on("click", function() {
            $('#sms_template_list').DataTable().search('').draw();
        });

        $('.btn-refresh').on("click", function() {
            $('#sms_template_list').DataTable().ajax.reload();
        });

        //DELETE
        $scope.deleteSmsTemplate = function($id) {
            $('#sms_id').val($id);
        }
        $scope.deleteConfirm = function() {
            $id = $('#sms_id').val();
            $http.get(
                laravel_routes['deleteSmsTemplate'], {
                    params: {
                        id: $id,
                    }
                }
            ).then(function(response) {
                if (response.data.success) {
                    custom_noty('success', response.data.message);
                    $('#sms_template_list').DataTable().ajax.reload();
                    $scope.$apply();
                } else {
                    custom_noty('error', errors);
                }
            });
        }

        //FOR FILTER
        $('#sms_name').on('keyup', function() {
            dataTable.draw();
        });
        $scope.reset_filter = function() {
            $("#sms_name").val('');
            dataTable.draw();
        }

        $rootScope.loading = false;
    }
});
//------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------
app.component('smsTemplateForm', {
    templateUrl: sms_template_form_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope) {
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        self.angular_routes = angular_routes;
        $http.get(
            laravel_routes['getSmsTemplateFormData'], {
                params: {
                    id: typeof($routeParams.id) == 'undefined' ? null : $routeParams.id,
                }
            }
        ).then(function(response) {
            console.log(response.data);
            self.sms_template = response.data.sms_template;
            self.extras = response.data.extras;
            self.action = response.data.action;
            self.theme = response.data.theme;
            $rootScope.loading = false;
            if (self.action == 'Edit') {
                if (self.sms_template.deleted_at == null) {
                    self.switch_value = 'Active';
                } else {
                    self.switch_value = 'Inactive';
                }
            } else {
                self.switch_value = 'Active';
            }
        });

        /* Tab Funtion */
        $('.btn-nxt').on("click", function() {
            $('.editDetails-tabs li.active').next().children('a').trigger("click");
            tabPaneFooter();
        });
        $('.btn-prev').on("click", function() {
            $('.editDetails-tabs li.active').prev().children('a').trigger("click");
            tabPaneFooter();
        });

        //ADD PARAMETERS
        $scope.addSmsTemplateParameters = function() {
            self.sms_template.params.push({
                id: '',
                name: '',
                type_id: '',
                default_value: '',
                field_type_id: '',
                display_order: '',
            });
        }
        //REMOVE PARAMETERS
        self.sms_template_removal_ids = [];
        $scope.removeSmsTemplateParameters = function(index, parameter_id) {
            if (parameter_id) {
                self.sms_template_removal_ids.push(parameter_id);
                $("#sms_template_removal_ids").val(JSON.stringify(self.sms_template_removal_ids));
            }
            self.sms_template.params.splice(index, 1);
        }

        var form_id = '#form';
        var v = jQuery(form_id).validate({
            ignore: '',
            rules: {
                'name': {
                    required: true,
                    minlength: 3,
                    maxlength: 191,
                },
                'description': {
                    required: true,
                    minlength: 3,
                    maxlength: 255,
                },
                'content': {
                    required: true,
                    minlength: 3,
                    maxlength: 255,
                },
            },
            invalidHandler: function(event, validator) {
                custom_noty('error', 'You have errors,Please check all tabs');
            },
            submitHandler: function(form) {
                let formData = new FormData($(form_id)[0]);
                $('#submit').button('loading');
                $.ajax({
                        url: laravel_routes['saveSmsTemplate'],
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                    })
                    .done(function(res) {
                        if (res.success == true) {
                            custom_noty('success', res.message);
                            $location.path('/sms-pkg/sms-template/list');
                            $scope.$apply();
                        } else {
                            if (!res.success == true) {
                                $('#submit').button('reset');
                                var errors = '';
                                for (var i in res.errors) {
                                    errors += '<li>' + res.errors[i] + '</li>';
                                }
                                custom_noty('error', errors);
                            } else {
                                $('#submit').button('reset');
                                custom_noty('success', res.message);
                                $location.path('/sms-pkg/sms-template/list');
                                $scope.$apply();
                            }
                        }
                    })
                    .fail(function(xhr) {
                        $('#submit').button('reset');
                        custom_noty('error', 'Something went wrong at server');
                    });
            }
        });
    }
});
