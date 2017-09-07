$(document).ready(function(){
  $("#table").DataTable(conf());
});

function del(id){
  $.ajax({
    url: '/admin/userrole/'+id,
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
	  var html = '<a class="edit" href="userrole/'+data.id+'/edit">修改角色</a>';
	  return html;
	}
      },
      {data: 'id', width: '26px'},
      {data: 'name'},
      {data: 'email'},
      {data: 'roles',
       "render": function(data, type, full, meta) {
	 var html = "";
	 data.forEach(function(a){
	   html += a.display_name + "; ";
	 });
	  return html;
       }},
    ]
 };

  return conf;
}
