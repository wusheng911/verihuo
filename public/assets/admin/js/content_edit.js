
function oncategorychange(){
  $("#content_category_id").attr("value",categoryId);
}
function processSelectedFile(filePath, requestingField) {
  $('#' + requestingField).val(filePath).trigger('change');
  $('#img_'+requestingField).attr('src',filePath);
}
function onMouseDown(){
  var maxDescriptionCnt = 200;
  var description = $('#contentDescription').val();
  var category = $("#content_category_id").attr("value");

  if(description ==''){
    alert('请输入文章的描述内容,不少于'+maxDescriptionCnt+'字!');
    return false;
  }
  if(description.length<maxDescriptionCnt){
    alert('描述内容要大于'+maxDescriptionCnt+'字!');
    return false;
  }
  if(category<=0){
    alert('请输入文章所属类型! 麻溜的!');
  }
  if($('#img_feature_image_2').attr('src')== '/assets/img/article_icon_4_3.jpg'){
    alert('小伙喳/小菇凉,,请传入4:3比例图片!');
    return false;
  }


  if(event.target.id == "sureAndBackList"){
    $('#backtype').attr('value',0);
  }else{
    $('#backtype').attr('value',1);
  }
}

$(document).ready(function(){
  CKEDITOR.replace("content");

  jQuery.datetimepicker.setLocale('zh');
  $("#post_at").datetimepicker({
    format:'Y-m-d H:i:s',
    startDate:'+2016-01-01',
  });

  $("#contentDescription").on('change keyup paste', function(event){
    refreshTags();
  });

  refreshTags();

  function refreshTags(){
    var content = $("#contentDescription").val();
    $.ajax({
      url: getTagsHtml,
      async: false,
      method: 'POST',
      data:{'content':content,
	    'id':App.contentid},
      success: function(html){
	$("#tag-lists").html(html);
      }
    });
  }

});
