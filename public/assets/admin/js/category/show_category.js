$(document).ready(onReady);


function displaytree() {
  $("#tree").treeview({
    collapsed: true,
    animated: "medium",
    control:"#sidetreecontrol",
    persist: "location"
  });

  $("[data-toggle='popover']").popover();
}
function hasChildrenCategory(){
  $.ajax({
    type: "GET",
    url: getChildrenListUrl,
    data: {id:$("#id").attr("value")},
    dataType: "json",
    success: function(data){
      if(data!=0){
	alert("请先删除其子分类");
      }else{
	window.location.href = indexUrl;
      }
    }});
}


function onReady(){
  displaytree();
  $('#sure-del').on('click', function () {
    $.ajax({
      type: "GET",
      url:getChildrenListUrl,
      data: {id:$("#id").attr("value")},
      dataType: "json",
      success: function(data){
	if(data!=0){
	  alert("请先删除该分类的所有子分类");
	}else{
	  window.location.href = indexUrl;
	}
      }});
    
  })
}
