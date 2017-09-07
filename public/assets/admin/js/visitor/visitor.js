var admin;  
$(document).ready(function(){
    admin = App.admin;

    $('#createCodeBtn').on('click',function(e){
		  $.ajax({
			    url: '/'+admin+'/visit/visitor/createcode',
			    method: 'post',
			    data: {},
			    dataType: "json",
			    success: function(data){
			      window.location.href = window.location.href;
			    },
			    error:function(error){
                    console.log(error);
			    }
			  });
    });
	table = $("#table").DataTable(conf());
    $('#sure-del').on('click',function(e){
		  $.ajax({
			    url: '/'+admin+'/visit/visitor/'+delId,
			    method: 'delete',
			    data: {id:delId,_method:'delete'},
			    dataType: "json",
			    success: function(data){
			      console.log(data);
			      window.location.href = "/"+admin+"/visit/visitor";
			    },
			    error:function(error){
                    console.log(error);
			    }
			  });
    });
})

var delId = 0;
function deleteVisitor(id){
    delId = id;
}

function sendCode(event, id) {
  var btn = $(event);
  btn.attr("disabled","disabled");
  $.ajax({
    method: "POST",
    url: App.sendCodeUrl,
    data: {id:id},
    dataType: "json",
    success: function(data){
      if(data.status == 1){
	Lobibox.notify('success', {
	  size: 'mini',
	  continueDelayOnInactiveTab: true,
	  rounded: true,
	  position: 'center top',
	  delayIndicator: false,
	  msg: '发送id为:' + id + '成功'
	});
      } else {
	Lobibox.notify('error', {
	  size: 'mini',
	  continueDelayOnInactiveTab: true,
	  rounded: true,
	  position: 'center top',
	  delayIndicator: false,
	  msg: '发送id为:' + id + '失败' + data.msg
	});
	btn.removeAttr("disabled");
      }
    }});
}

function conf(){
  var conf = {
    serverSide: true,
    pagingType: "full_numbers",
    iDisplayLength: 10,
    "bSort":true,
    'asSorting':'desc',
    "lengthMenu": [[10, 20, 50], [10, 20, 50]],
    ajax: {
      url: "/"+admin+"/visit/visitor/list",
      data:{},
    },
    "columnDefs": [
      {"className": "dt-left", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
      {data: 'name'},
      {data: 'phone'},
      {data: 'code'},
      {data: 'level'},
      {
	data: null,
	width: '98px',
	orderable: false,
	"render": function ( data, type, full, meta )
	{
	  var html = '';
	  if(data.codesent_at != null){
	    if(data.checked == true){
	      html = data.codesent_at;
	    }else{
	      html = ' <button type="button" class="btn btn-warning " onClick="sendCode(this, '+ data.id + ')">重发邀请码</button>';
	    }
	  }else{
	    html = ' <button type="button" class="btn btn-success " onClick="sendCode(this, '+ data.id + ')">发送邀请码</button>';
	  }
	  return html;
	}
      },
      {data: 'entered_at'},
      {
	data: null,
	orderable: false,
	"render": function ( data, type, full, meta ) 
	{
	  var html = "<a class='edit' href=/"+admin+"/visit/visitor/"+data.id+"/edit>编辑</a>";
	  html += ' <a class="del" data-toggle="modal" data-target="#myModalDel" href="javascript:void(0)"\
	     onClick="deleteVisitor('+ data.id +' )">删除</a>';
	  return html;
	}
      },
    ]
  };

  return conf;
}
