$(document).ready(function(){
  $("#table").DataTable(conf());
});

function deleteContent(id){
  $.ajax({
    url: '/admin/content/'+id,
    method: 'DELETE',
    success: function(json){
      console.log(json);
    }
  });
}

function conf(){
  var conf = {
    serverSide: true,
    pagingType: "full_numbers",
    iDisplayLength: 10,
    "lengthMenu": [[10, 20, 50], [10, 20, 50]],
    ajax: {
      url: '/admin/content/lists'
    },
    "columnDefs": [
      {"className": "dt-left", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {
	data: null,
	width: '62px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = '<a class="edit" href="content/'+data.id+'/edit">编辑</a>';
	  html += ' <a class="del" href="javascript:void(0)"\
 onClick="deleteContent('+ data.id +' )">删除</a>';
	  return html;
	}
      },
      {data: 'id', width: '26px'},
      {data: 'title'},
      {data: 'subtitle'},
      {data: 'description'},
    ]
 };

  return conf;
}
