app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    //SMS LOG
    when('/sms-pkg/sms-log/list', {
        template: '<sms-log-list></sms-log-list>',
        title: 'SMS Templates',
    });
}]);

app.component('smsLogList', {
    templateUrl: sms_log_list_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $location) {
        $scope.loading = true;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        var table_scroll;
        table_scroll = $('.page-main-content').height() - 37;
        var dataTable = $('#sms_logs_list').DataTable({
            "dom": cndn_dom_structure,
            "language": {
                // "search": "",
                // "searchPlaceholder": "Search",
                "lengthMenu": "Rows _MENU_",
                "paginate": {
                    "next": '<i class="icon ion-ios-arrow-forward"></i>',
                    "previous": '<i class="icon ion-ios-arrow-back"></i>'
                },
            },
            pageLength: 10,
            processing: true,
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('CDataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function(settings) {
                var state_save_val = JSON.parse(localStorage.getItem('CDataTables_' + settings.sInstance));
                if (state_save_val) {
                    $('#search_sms_log').val(state_save_val.search.search);
                }
                return JSON.parse(localStorage.getItem('CDataTables_' + settings.sInstance));
            },
            serverSide: true,
            paging: true,
            stateSave: true,
            ordering: false,
            scrollY: table_scroll + "px",
            scrollCollapse: true,
            ajax: {
                url: laravel_routes['getSmsLogList'],
                type: "GET",
                dataType: "json",
                data: function(d) {
                    d.sms_log_code = $('#sms_log_code').val();
                    d.sms_log_name = $('#sms_log_name').val();
                    d.mobile_no = $('#mobile_no').val();
                    d.email = $('#email').val();
                },
            },

            columns: [
                { data: 'action', class: 'action', name: 'action', searchable: false },
                { data: 'code', name: 'sms_logs.code' },
                { data: 'name', name: 'sms_logs.name' },
                { data: 'mobile_no', name: 'sms_logs.mobile_no' },
                { data: 'email', name: 'sms_logs.email' },
            ],
            "infoCallback": function(settings, start, end, max, total, pre) {
                $('#table_info').html(total)
                $('.foot_info').html('Showing ' + start + ' to ' + end + ' of ' + max + ' entries')
            },
            rowCallback: function(row, data) {
                $(row).addClass('highlight-row');
            }
        });
        $('.dataTables_length select').select2();

        $scope.clear_search = function() {
            $('#search_sms_log').val('');
            $('#sms_logs_list').DataTable().search('').draw();
        }

        var dataTables = $('#sms_logs_list').dataTable();
        $("#search_sms_log").keyup(function() {
            dataTables.fnFilter(this.value);
        });

        //DELETE
        $scope.deleteSmsTemplate = function($id) {
            $('#sms_log_id').val($id);
        }
        $scope.deleteConfirm = function() {
            $id = $('#sms_log_id').val();
            $http.get(
                sms_log_delete_data_url + '/' + $id,
            ).then(function(response) {
                if (response.data.success) {
                    $noty = new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: 'SMS Log Deleted Successfully',
                    }).show();
                    setTimeout(function() {
                        $noty.close();
                    }, 3000);
                    $('#sms_logs_list').DataTable().ajax.reload(function(json) {});
                    $location.path('/sms-pkg/sms-log/list');
                }
            });
        }

        //FOR FILTER
        $('#sms_log_code').on('keyup', function() {
            dataTables.fnFilter();
        });
        $('#sms_log_name').on('keyup', function() {
            dataTables.fnFilter();
        });
        $('#mobile_no').on('keyup', function() {
            dataTables.fnFilter();
        });
        $('#email').on('keyup', function() {
            dataTables.fnFilter();
        });
        $scope.reset_filter = function() {
            $("#sms_log_name").val('');
            $("#sms_log_code").val('');
            $("#mobile_no").val('');
            $("#email").val('');
            dataTables.fnFilter();
        }

        $rootScope.loading = false;
    }
});