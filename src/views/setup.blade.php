@if(config('custom.PKG_DEV'))
    <?php $sms_pkg_path = 'packages/abs/sms-pkg/src/'?>
@else
    <?php $sms_pkg_path = ''?>
@endif

<!-- SMS TEMPLATES -->
<script type="text/javascript">
    var sms_template_list_template_url = "{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-template/list.html')}}";
    var sms_template_form_template_url = "{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-template/form.html')}}";
    var sms_template_delete_url = "{{url('sms-pkg/sms-template/delete/')}}";
</script>
<script type="text/javascript" src="{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-template/controller.js')}}"></script>

<!-- SMS EVENTS -->
<script type="text/javascript">
    var sms_event_list_template_url = "{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-event/list.html')}}";
    var sms_event_form_template_url = "{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-event/form.html')}}";
    var sms_event_delete_url = "{{url('sms-pkg/sms-event/delete/')}}";
</script>
<script type="text/javascript" src="{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-event/controller.js')}}"></script>

<!-- SMS LOGS -->
<script type="text/javascript">
    var sms_log_list_template_url = "{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-log/list.html')}}";
</script>
<script type="text/javascript" src="{{URL::asset($sms_pkg_path.'public/angular/sms-pkg/pages/sms-log/controller.js')}}"></script>
