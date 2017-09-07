$(document).ready(onReady);
var index = 1000;
var nodeId;
var articleLists;
var currentPromptIndex =-1;
var currentPromptItem;
function onReady(){
	$('#selectNodeTypeContainer').on('change',onSelectNodeType)
	document.onkeydown=function(event){
		 
		  var e = event || window.event || arguments.callee.caller.arguments[0];
		  
		  var childs = $('#promptBox').children();
		  if(e && e.keyCode==38){
			 //按上
			  $(currentPromptItem).css('background-color','#ffffff');
			  currentPromptIndex--;
			  if(currentPromptIndex<-1){
				  currentPromptIndex=-1;
			  }
			  currentPromptItem = childs[currentPromptIndex];
			  $(currentPromptItem).css('background-color','antiquewhite');
			  
		  }else if(e && e.keyCode==40){ 
			 //按下
			  $(currentPromptItem).css('background-color','#ffffff');
			  currentPromptIndex++;
			  if(currentPromptIndex>childs.length-1){
				  currentPromptIndex=0;
			  }
			  currentPromptItem = childs[currentPromptIndex];
			  $(currentPromptItem).css('background-color','antiquewhite');
		  }else if(e && e.keyCode == 13){
			  $('#articleInput').val($(currentPromptItem)[0].textContent);
			  clearPromptBox();
		  } 
	 };
}
function processSelectedFile(filePath, requestingField) {
	console.log(filePath,requestingField);
	  $('#' + requestingField).val(filePath).trigger('change');
	  $('#img_'+requestingField).attr('src',filePath);
	}

function backTap(node_id,node_attr_id,seq_no,value_type,name){
	
	var data = node_id + ","+node_attr_id + "," + seq_no;
	if(value_type=='filepath'){
		element = "<div class='form-group'><label class='col-lg-2 control-label'>"+name+"</label><div class='col-lg-5'>" +
		 "<a href='' class='popup_selector' data-inputid='feature_image_" +
		 index+"'><img id='img_feature_image_" +
		 index+"' style='width:150px;height:150px;' for='feature_image_" +
		 index+"' alt='' src=''></a> " +
		 "<input hidden='false' type='text' data='" +
		 data+"' name='{{$value->node_attr_id}}' id='feature_image_" +
		 index+"'  value=''/>" +
		 "</div></div>";
	}else if(value_type =='strval'){
		
		element = "<div class='form-group'><label class='col-lg-2 control-label'>"+name+"</label><input  data=" +
				data +" class='col-lg-9 form-control-static' type='text' value=''></div>"
    
	}else if(value_type =='article'){
		element = "<div class='form-group'><label id='nameLabel' class='col-lg-2 control-label'>"+name+"</label><input id='articleInput' oninput='onInputChange(this)' name='articleInput' data=" +
		data +" class='col-lg-9 form-control-static' type='text' value=''><div id='promptBox'></div></div>";
	}else{
		element = "<div class='form-group'><label class='col-lg-2 control-label'>"+name+"</label><input  data=" +
		data +" class='col-lg-9 form-control-static' type='text' value=''></div>"
	}
	return element;
	
}
function onMyClick(event){
	$('#articleInput').val(event.target.textContent);
	clearPromptBox();
}
function clearPromptBox(){
	$('#promptBox').empty();
	currentPromptIndex =-1;
}
function onMyMouseOver(){
	
	
	$(currentPromptItem).css('background-color','#ffffff');
	var target = $(event.target)[0];
	if($(target).is('div')){
		$(target).css('background-color','antiquewhite');
	}else if($(target).is('span')){
		target = $(target).parent()[0];
		$(target).css('background-color','antiquewhite');
	}
	currentPromptItem = target;
	var childs = $('#promptBox').children();
	var len = childs.length;
	for(var i=0;i<len;i++){
		if($(childs[i]).attr('id') == $(target).attr('id')){
			currentPromptIndex = i;
		}
	}
}
function onMyMouseOut(evnet){
	var target = $(event.target)[0];
	if($(target).is('div')){
		$(target).css('background-color','#ffffff');
	}else if($(target).is('span')){
		target = $(target).parent()[0];
		$(target).css('background-color','#ffffff');
	}
}
function onInputChange(event){
	clearPromptBox();
	var key = $('#articleInput').val();
	console.log(event);
	if(key!=''){
		$.ajax({
	        type: "GET",
	        url: getArtilesListUrl,
	        data: {name:key},
	        dataType: "json",
	        
	        success: function(data){
	        	articleLists = data;
	        	var len = articleLists.length;
	        	if(len>0){
	        		for(var i=0;i<len;i++){
	        			var element = "<div id='"+articleLists[i].id+"'style='width:350px;height:22px;overflow:hidden;cursor:pointer;' onmouseover=onMyMouseOver() onmouseout=onMyMouseOut() onclick=onMyClick()><span onmouseover=onMyMouseOver() onmouseout=onMyMouseOut()>" +articleLists[i].id+'  '+ articleLists[i].title +"</span></div>";
	        			$('#promptBox').append($(element)); 
	        		}
	        		var left = $('#nameLabel').css('width');
	        		$('#promptBox').css('padding-left',left);
	        	}
	        	
	        	
	    },
	    error:function(error){
			console.log('连接失败！可能是服务器的问题，也可能是路径问题');
	    }
	        });
	}
	
}
function onChangeArticle(event){
	var selectIndex = event.target.selectedIndex;
	var option = $(event.target.children[selectIndex]);
	var input = $(event.target).parent('div').children("input:first")[0];
	$(input).val($(option).attr('id'));
}
function onNodeAttrTypeChange(event){
	var aimDiv = event.target.parentNode;
	 while($(aimDiv).children().size()>1){
	   		$(aimDiv).children(":last").remove();
	   	}
	 var element;
	 index++;
	 nodeId = $('#nodeId').attr('value');
	 
	 var selectIndex = event.target.selectedIndex;
	 var option = $(event.target.children[selectIndex]);
	 console.log($(option).attr('name'));
	 element = backTap(nodeId,$(option).attr('id'),1,$(option).attr('type'),$(option).attr('name'));
	 $(aimDiv).append($(element)); 
}
function saveItems(){
	var inputs = $('#containerList').find('input');
	var len = $(inputs).size();
	var nodes = [];
	var nodeAttrs =[];
	var seq_nos = [];
	var values = [];
	var tmpArr;
	var tmpArticleInput;
	for(var i=0;i<len;i++){
		str = $(inputs[i]).attr('data');
		tmpArticleInput = $(inputs[i]);
		if($(tmpArticleInput).attr('id')=='articleInput'){
			var title = $(tmpArticleInput).val();
			if(title!=''){
				var tmpTitle = parseInt(title);
				if(isNaN(tmpTitle)){
					tmpId = title.split(' ')[0];
					if(isNaN(parseInt(tmpId))){
						alert('请传入文章ID或根据提示选择文章');
						return ;
					}else{
						values.push(tmpId);
					}
				}else{
					values.push(tmpTitle);
				}
			}else{
				alert('请选择文章');
				return ;
			}
		}else{
			values.push($(inputs[i]).val());
		}
		
		tmpArr= str.split(",");
		nodes.push(tmpArr.shift());
		nodeAttrs.push(tmpArr.shift());
		seq_nos.push(tmpArr.shift());
	}
	$.ajax({
        type: "POST",
        url: saveNodeAttrUrl,
        data: {nodes:nodes,nodeAttrs:nodeAttrs,seq_nos:seq_nos,values:values},
        dataType: "json",
        success: function(data){
        	window.location.href = window.location.href;
        	//window.location.href='/admin/adposition/' ;
    },
    error:function(error){
		console.log('连接失败！可能是服务器的问题，也可能是路径问题');
    }
        });
	
}
function addItem(){
	nodeId = $('#nodeId').attr('value');
	nodeTypeId = $('#nodeTypeId').val();
	$.ajax({
        type: "GET",
        url: getNodeListUrl,
        data: {node_type_id:nodeTypeId},
        dataType: "json",
        
        success: function(data){
        	
        	
        	var inputs = $('#containerList').find('input');
        	var len = $(inputs).size();
        	var nodes = [];
        	var nodeAttrs =[];
        	var seq_nos = [];
        	var values = [];
        	var tmpArr;
        	for(var i=0;i<len;i++){
        		str = $(inputs[i]).attr('data');
        		values.push($(inputs[i]).val());
        		tmpArr= str.split(",");
        		nodes.push(tmpArr.shift());
        		nodeAttrs.push(tmpArr.shift());
        		seq_nos.push(tmpArr.shift());
        	}
        	
        	console.log(data);
        	var firstId = -1;
        	var firstItem;
        	var len = data.length;
        	data.reverse();
        	var element = "<div id='container'> <select onchange=onNodeAttrTypeChange()>";
        	for(var i=0;i<len;i++){
        		if(nodeAttrs.indexOf(data[i].id.toString())<0){
        			if(firstId<0){
        				firstId = data[i].id;
        				firstItem = data[i];
        			}
            		element += "<option " + "id="+ data[i].id + " type="+ data[i].value_type +" name="+ data[i].display_label+ ">" + data[i].display_label;
        		}
        	}
        	if(firstId<0){
        		alert('属性数量已达上限！');
        		return;
        	}
        	element+="</select>";
        	element+=backTap(nodeId,firstItem.id,1,firstItem.value_type,firstItem.display_label);
        	element+="</div>";
        	$("#containerList").append($(element));
    },
    error:function(error){
		console.log('连接失败！可能是服务器的问题，也可能是路径问题');
    }
        });
}

function onSelectNodeType(event){
	var selectIndex = event.target.selectedIndex;
	event.target.children[selectIndex].id;
	$('#nodeTypeId').val(event.target.children[selectIndex].id);
}
