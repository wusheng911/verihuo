var container ;
var currentSelectIndex=0;
var aimId=0;
var aimName;
var categoryId=0;
var maxLevel = 3;
var id =0;
$(document).ready(onReady);

function onReady(){
  var aid = parseInt($('#content_category_id').attr('value'));
  if($('#content_category_id') && !isNaN(aid)){
    categoryId = $('#content_category_id').attr('value');
    id = $('#id').attr('value');
    getAllFatherCategory(categoryId);
    
  }else{
    getChildCategory(aimId);
  }
  
}
function getAllFatherCategory(args){
  
  if(currentSelectIndex<maxLevel){
    $.ajax({
      type: "GET",
      url: getParentsUrl,
      data: {id:args},
      dataType: "json",
      
      success: function(data){
	var tree= data.shift();
	tree.reverse();
	data.reverse();
	if(data.length>0){
	  var len = data.length;
	  for(var i=0;i<len;i++){
	    if(data[i].length>0){
	      var elementStr = "<select id='select' class='show-tick' onchange='onSelectCategory()'><select/>";
	      var optionStr = "<option >－－－</option>";
	      $("#selectCategoryContainer").append($(elementStr));
	      $("#select").attr('name',currentSelectIndex+1);
	      $("#select").attr('id','select-'+(currentSelectIndex+1));
	      $("#select-"+(currentSelectIndex+1)).append($(optionStr));
	      for(var j=0;j<data[i].length;j++){
		if(data[i][j].id != id){
		  if(data[i][j].id==tree[i]){
		    optionStr = "<option selected='true' id='tmpOption'>"+data[i][j].title+"</option>";
		  }else{
		    optionStr = "<option id='tmpOption'>"+data[i][j].title+"</option>";
		  }
		  $("#select-"+(currentSelectIndex+1)).append($(optionStr));
		  $("#tmpOption").attr('id',data[i][j].id);
		}
	      }
	    }
	    currentSelectIndex++;
	  }
	}
      },
      error:function(error){
	console.log('连接失败！可能是服务器的问题，也可能是路径问题');
      }
    });
  }else{
    console.log('超过了最大显示级别');
  }
}
function getChildCategory(args){
  
  if(currentSelectIndex<maxLevel){
    $.ajax({
      type: "GET",
      url: getChildrenUrl,
      data: {id:args},
      dataType: "json",
      
      success: function(data){
	
	if(data.length>0){
	  
	  var elementStr = "<select id='select' onchange='onSelectCategory()'><select/>"
	  var optionStr = "<option selected=''>－－－</option>"
	  $("#selectCategoryContainer").append($(elementStr));
	  $("#select").attr('name',currentSelectIndex+1);
	  $("#select").attr('id','select-'+(currentSelectIndex+1));
	  var len = data.length;
	  $("#select-"+(currentSelectIndex+1)).append($(optionStr));
	  for(var i=0;i<len;i++){
	    if(data[i].id != id){
	      optionStr = "<option id='tmpOption'>"+data[i].title+"</option>";
	      $("#select-"+(currentSelectIndex+1)).append($(optionStr));
	      $("#tmpOption").attr('id',data[i].id);
	    }
	  }
	}
      },
      error:function(error){
	console.log('连接失败！可能是服务器的问题，也可能是路径问题');
      }
    });
  }else{
    console.log('超过了最大显示级别');
  }
}
function onSelectCategory(){
  currentSelectIndex = Number(event.target.name);
  aimName = event.target.children[event.target.selectedIndex].label;
    console.log($("#selectCategoryContainer").children().length);
  while($("#selectCategoryContainer").children().length>currentSelectIndex){
    $("#selectCategoryContainer").children("select:last").remove();
  }
  aimId = event.target.children[event.target.selectedIndex].id;
  aimId = parseInt(aimId);
  if(isNaN(aimId)){
    var childCntIndex = $("#selectCategoryContainer").children().length-2;
    if(childCntIndex<0){
      categoryId = 0;
    }else{
      var parentSelect = $($("#selectCategoryContainer").children().get(childCntIndex));
      var selectIndex = $(parentSelect)[0].selectedIndex;
      categoryId =$(parentSelect)[0].children[selectIndex].id;
    }
    $("#selectCategoryContainer").children("select:last");
  }else{
    getChildCategory(aimId);
    categoryId = aimId;
  }
}
