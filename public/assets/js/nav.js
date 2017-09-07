$(document).ready(function(){
	
	currentId = $('#currentId').attr('value');
	$.ajax({
	      type: "get",
	      url: getNavData,
	      data: {id:currentId},
	      dataType: "json",
	      success: function(data){

	    	  topId = data['topid'];
			  if(topId>0 && topId<5){
				  $('#nav-item-'+topId).addClass('nav-item-default-'+topId);
			  }

			  tags = data['tags'];
			  childs = data['childs'];
			  homeTags = data['homeTags'];
			  setSubnavItem(currentId);
	      }});
});
var currentId = 0;
var topId;
var tags ;
var childs ;
var homeTags;
var maxSubItem = 50;


function setSubnavItem(index){
	var cnt = 0;
	var str = "";
	for(var child in childs){
		if(cnt>=maxSubItem){
			break;
		}
		str+="<div  class='nav-sub-item catogory-tag'>";
		str+= "<a href='"+newsPath+"/"+childs[child].id+"'>"+childs[child].name+"</a></div>";
		cnt++;
	}

	if(childs.length>0 && tags.length>0){
		str+="<div class='nav-sub-item'>|&nbsp&nbsp&nbsp&nbsp热门搜索：</div>";
	}
	//homeTags.reverse();
	for(var homeTag in homeTags){

		if(cnt>=maxSubItem){
			break;
		}
		str+="<div class='nav-sub-item'>";
		str+= "<a href='"+homeTags[homeTag].link+"'>"+homeTags[homeTag].name+"</a></div>";
		cnt++;
	}
	for(var tag in tags){

		if(cnt>=maxSubItem){
			break;
		}
		str+="<div class='nav-sub-item'>";
		str+= "<a href='"+tagPath+"/"+tags[tag].id+"'>"+tags[tag].name+"</a></div>";
		cnt++;
	}

	$($('#nav-sub-container')[0]).append(str);
}
