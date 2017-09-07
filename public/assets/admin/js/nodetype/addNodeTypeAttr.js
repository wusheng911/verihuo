$(document).ready(function(){

	$('#setNodeTypeAttrBtn').on('click', function () {

		var arrIds = [];
		$("input:checkbox:checked").each(function(){
			arrIds.push($(this).attr('data'));
		});

		myNodeTypeID = $('#nodeAttrs').attr('data');
		$.ajax({
			type: "POST",
			url: setNodeTypeAttrUrl,
			data: {ids:arrIds,nodeTypeID:myNodeTypeID},
			dataType: "json",

			success: function(data){
				var newstr=nodeTypeUrl+'/'+myNodeTypeID+'/edit';
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
