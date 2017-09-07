
$(document).ready(function(){
  CKEDITOR.replace("content");

  jQuery.datetimepicker.setLocale('zh');
  $("#post_at").datetimepicker({
    format:'Y-m-d H:i:s',
    startDate:'+2016-01-01',
  });

});
