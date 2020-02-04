@if(config('sms-pkg.DEV'))
    <?php $sms_pkg_path = 'packages/abs/sms-pkg/src/'?>
@else
    <?php $sms_pkg_path = ''?>
@endif

<!-- SMS TEMPLATES -->
<script type="text/javascript">
    var sms_template_list_template_url = "{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-template/list.html')}}";
    var sms_template_form_template_url = "{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-template/form.html')}}";
</script>
<script type="text/javascript" src="{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-template/controller.js')}}"></script>

<!-- SMS EVENTS -->
<script type="text/javascript">
    var sms_event_list_template_url = "{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-event/list.html')}}";
    var sms_event_form_template_url = "{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-event/form.html')}}";
</script>
<script type="text/javascript" src="{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-event/controller.js')}}"></script>

<!-- SMS LOGS -->
<script type="text/javascript">
    var sms_log_list_template_url = "{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-log/list.html')}}";
</script>
<script type="text/javascript" src="{{asset($sms_pkg_path.'public/themes/'.$theme.'/sms-pkg/sms-log/controller.js')}}"></script>
