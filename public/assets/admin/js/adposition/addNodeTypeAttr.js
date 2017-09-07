$(document).ready(function(){
	$('#addNodeTypeAttrBtn').on('click', function () {
		  if(!addNodeTypeAttrId){
			  alert('请选择要添加的属性');
			  return;
		  }
		  myNodeTypeID = $('#nodeTypeID').val();
		  console.log(addNodeTypeAttrUrl);
		  $.ajax({
		        type: "POST",
		        url: addNodeTypeAttrUrl,
		        data: {id:addNodeTypeAttrId,nodeTypeID:myNodeTypeID},
		        dataType: "json",
		        
		        success: function(data){
		        	console.log('添加数据成功');
		        	console.log(nodeTypeUrl);
		        	var newstr=nodeTypeUrl.substring(0,nodeTypeUrl.length-1);
		        	newstr +=myNodeTypeID;
		        	console.log(newstr);
		        	window.location.href=newstr; 
		    },
		    error:function(error){
		    	console.log(error);
				console.log('连接失败！可能是服务器的问题，也可能是路径问题');
		    }
		        });
	  });
});
var addNodeTypeAttrId =0;
function onSelectAddNodeTypeAttr(){
	console.log(event);
	var selectIndex = event.target.selectedIndex;
	 var option = $(event.target.children[selectIndex]);
	 addNodeTypeAttrId = $(option).attr('id');
}