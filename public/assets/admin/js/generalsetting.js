$(document).ready(function(){
	$('#forwordBtn').on('click',changeKVOrder);
	$('#backBtn').on('click',changeKVOrder);
})

function changeKVOrder(event){
	var path = App.path;
	var value =1;
	if(event.currentTarget.id != "forwordBtn"){
		value = 0;	
	}
		  $.ajax({
					type: "POST",
					url: "/"+path+"/adposition/kvorder",
					data:{order: value},
					dataType: "json",
					success: function(data){
						alert('修改主KV顺序成功');
					},
					error:function(error){
						console.log(error);
						console.log('报错了');
					}
		        });
}
