$(document).ready(function(){
	table = $("#table").DataTable(conf());
  $('#sure-del').on('click', function () {
	  $.ajax({
		  method: "POST",
	      url: sendUrl,
	      data: {id:sendId,_method:"delete"},
	      dataType: "json",
	      success: function(data){
	    	  console.log('删除成功');
	    	  console.log(data);
		  window.location.href = window.location.href;
	      }});
	  })
});
var sendUrl="";
var sendId =0;
var table;
var currentIndex=0;
var arrName = ['sEdit','sId','sCategory','sImg','sTitle'];
var arrHidden =[true,false,false,true,false];
function deleteContent(id){
	sendUrl = delContentUrl +'/' + id;
	sendId = id;
	console.log(sendUrl);
}

function conf(){
  var conf = {
    serverSide: true,
    pagingType: "full_numbers",
    iDisplayLength: 10,
    "ordering":true,
    "bSort":true,
    "lengthMenu": [[10, 20, 50], [10, 20, 50]],
    ajax: {
      url: getListUrl,
      data:{'sid':'','scategory':'','stitle':''}
    },
    "columnDefs": [
      {"className": "dt-left", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {data: 'id', width: "18px", ordering:true },
      {data: 'pid', width: '48px' },
      {
	"ordering":true,
	'bSort':true,
	data: 'image_4_3',
	width: '22px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = '<image style="width:20px;height:20px;" src='+data+'></imag>';
	  return html;
	} 
      },
      {data: 'title',"ordering":true,'bSort':true,"asSorting": [ "desc", "asc"] },
      {data: 'tags', render: function(data, type, full, meta){
	var html = "";
	html = data.reduce(function(a, c){return a + c.name + ', ';}, "");
	return html;
      }},
      {
	data: null,
	width: '122px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = '<a class="edit glyphicon glyphicon-edit" href="content/'+data.id+'/edit">编辑</a>';
	  html += ' <a class="del glyphicon glyphicon-remove" data-toggle="modal" data-target="#myModal" href="javascript:void(0)"\
 onClick="deleteContent('+ data.id +' )">删除</a>';
	  return html;
	}
      },
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
		$(table).context[0].ajax.data={'sid':$('#sId').val(),'scategory':$('#sCategory').val(),'stitle':$('#sTitle').val()}
		table.draw();
	      });
      } );
    }
  };

  return conf;
}
