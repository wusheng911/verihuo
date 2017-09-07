$(document).ready(function(){
	table = $("#table").DataTable(conf());
  
  $('#sure-reset').on('click', function () {
	  console.log('哼哼哈嘿');
	  $.ajax({
	      type: "post",
	      url: resetNodeUrl,
	      data: {id:resetId},
	      dataType: "json",
	      success: function(data){
	    	  console.log('重置成功');
	    	  console.log(data);
		  window.location.href = window.location.href;
	      }});
	  })
	  
	  $('#sure-del').on('click', function () {
		  console.log(delAdUrl,delAdId);
		  $.ajax({
			    url: delAdUrl+'/'+delAdId,
			    method: 'post',
			    data: {id:delAdId,_method:'delete'},
			    dataType: "json",
			    success: function(data){
			      console.log(data);
			      window.location.href = window.location.href;
			    },
			    error:function(error){
					console.log('可能是广告位没有节点或者adposition_node这个表没有对应的数据，导致这个问题的原因是多个广告位共用一个节点');
			    }
			  });
	  })
	  
	   $('#sure-addAdpositions').on('click', function () {
		   createAdpositionForCategory(0);
	  })
	  $('#sure-addAdpositionsForPC').on('click', function () {
		  createAdpositionForCategory(1);
	  })
	  
	 
});
var resetId =0;
var delAdId =0;
var table;
var currentIndex=0;
var arrName = ['sEdit','sId','sName','sCode','sDescript'];
var arrHidden =[true,false,false,false,false];

function deleteContent(id){
  delAdId = id;
}
function resetNode(id){
	resetId = id;
}

function createAdpositionForCategory(type){
	if(type != 1){
		type=0;
	}
	$.ajax({
        type: "GET",
          url: createUrl,
        data: {type:type},
        dataType: "json",
        
        success: function(data){
        	console.log('添加数据成功');
        	window.location.href=adIndexUrl; 
    },
    error:function(error){
		console.log('连接失败！可能是服务器的问题，也可能是路径问题');
    }
        });
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
      url: getListUrl,
      data:{'sid':'','sname':'','scode':'','sdescript':''}
    },
    "columnDefs": [
      {"className": "dt-left", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {
	data: null,
	width: '72px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = '<a class="edit" href="adposition/'+data.id+'/edit">编辑</a>';
	  html += ' <a class="del" data-toggle="modal" data-target="#myModalDel" href="javascript:void(0)"\
 onClick="deleteContent('+ data.id +' )">删除</a>';
 	html+='<a data-toggle="modal" data-target="#myModalReset" href="javascript:void(0)"\
 		onClick="resetNode('+ data.id +' )">重置</a>';
	  return html;
	}
      },
      {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
      {data: 'name'},
      {data: 'adposition_code'},
      {data: 'description'},
	  {data: 'node_type_id'},
    ],
    initComplete: function () {
    	currentIndex = 0;
        var api = this.api();
        api.columns().indexes().flatten().each( function ( i ) {
            var column = api.column( i );
            if(arrHidden[currentIndex]){
            	 var txt = "<input id='" +arrName[currentIndex]+"' hidden='"+arrHidden[currentIndex]+"'/>" ;
            }else{
            	 var txt = "<input id='" +arrName[currentIndex]+"'/>" ;
            }
            currentIndex++;
            var select = $(txt)
                .appendTo( $(column.footer()).empty() ).on('input',function(){
                	$(table).context[0].ajax.data={'sid':$('#sId').val(),'sname':$('#sName').val(),'scode':$('#sCode').val(),'sdescript':$('#sDescript').val()}
                	table.draw();
                });
        } );
    }
 };

  return conf;
}
