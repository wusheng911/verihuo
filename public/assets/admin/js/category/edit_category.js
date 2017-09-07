$(document).ready(onReady);

function onReady(){
  displaytree();
  maxLevel = 2;
  $('#sure-del').on('click', function () {
    $.ajax({
      type: "GET",
      url: getChildrenListUrl,
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

function displaytree() {
  $("#tree1").treeview({
    collapsed: true,
    animated: "medium",
    control:"#sidetreecontrol",
    persist: "location"
  });
}
function oncategorychange(){
  $("#content_category_id").attr("value",categoryId);
}
