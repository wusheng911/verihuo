$(document).ready(function(){
  $("#table").DataTable(conf());
});

function deleteRole(id){
  $.ajax({
    url: '/admin/role/'+id,
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
      url: getListUrl
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
	  var html = '<a class="edit" href="role/'+data.id+'/edit">编辑</a>';
	  html += ' <a class="del" href="javascript:void(0)"\
 onClick="deleteRole('+ data.id +' )">删除</a>';
	  return html;
	}
      },
      {data: 'id', width: '26px'},
      {data: 'display_name'},
      {data: 'description'},
    ]
 };

  return conf;
}
