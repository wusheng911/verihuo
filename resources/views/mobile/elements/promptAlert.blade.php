<div id="prompt_alert" data-alert class="alert-box {{isset($message_type)&&($message_type==config('constants.PROMPT_INFO'))?'success':'warning'}}" 
    style="{{(isset($message)&&(strlen($message)>0))?'':'display:none'}}" data-item="">
    {{(isset($message)&&(strlen($message)>0))?$message:''}}
</div>