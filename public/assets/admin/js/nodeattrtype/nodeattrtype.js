$(document).ready(function(){
	table = $("#table").DataTable(conf());
	
	$('#sure-del').on('click', function () {
		 // console.log(delAdUrl,delAdId);
		  $.ajax({
			    url: delAdUrl+'/'+delAdId,
			    method: 'POST',
			    data: {id:delAdId,_method:"delete"},
			    dataType: "json",
			    success: function(data){
			      console.log(data);
			      window.location.href = window.location.href;
			    },
			    error:function(error){
					console.log(error);
			    }
			  });
	})
	  
});
var delAdId =0;
function deleteContent(id){
	  delAdId = id;
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
		  var html = '<a class="edit" href="nodeattrtype/'+data.id+'/edit">编辑</a>';
		  html += ' <a class="del" data-toggle="modal" data-target="#myModalDel" href="javascript:void(0)"\
	 onClick="deleteContent('+ data.id +' )">删除</a>';
		  return html;
		}
	      },
	      {data: 'id', width: '26px',"bSort":true,"asSorting":"desc"},
	      {data: 'name'},
	      {data: 'display_label'},
	      {data: 'value_type'},
	    ]
	 };

	  return conf;
	}