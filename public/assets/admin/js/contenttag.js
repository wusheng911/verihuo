var tagTable;
var table;

$(document).ready(function(){
  table = $("#table").DataTable(conf());
  tagTable = $("#tagTable").DataTable(tagConf());
  $('#sure-del').on('click', function () {
    $.ajax({
      type: "POST",
      url: sendUrl,
      data: {id:sendId,_method:"delete"},
      dataType: "json",
      success: function(data){
	$("#myModal").modal('toggle');
	table.draw('page');
	tagTable.draw('page');
      }});
  });

  $("button[name=newtag]").click(function(){
    $(this).hide(200);
    $("#newtag").show(200);
  });
  $("#newtag-save").click(function(){
    var name = $("#input-newtag").val();
    $.post(newTagUrl,{ name: name }, function(data){
      console.log(data);
    }).done(function(){
      $("#newtag").hide(200);
      $("button[name=newtag]").show(200);
      table.draw();
      tagTable.draw('page');
    });
  });

  // 一键生成所有contenttag
  $("button[name='gentag']").click(function(){
    var yn = confirm("您确定要重新生成所有文章的标签吗？");
    if(yn){
      $.post(reGenerateTagsUrl, {}, function(data){
	console.log(data);
      });
      $(this).attr("disabled","disabled");
    }
  });

});
var sendUrl="";
var sendId =0;
function deleteContent(id){
  sendUrl = delTagUrl;
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
      url: tagListUrl
    },
    "columnDefs": [
      {className: "hidden-id", "targets": 0},
      {"className": "dt-left", "targets": 2},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {data: 'id'},
      {data: 'name'},
      {
	data: null,
	width: '28px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = ' <a class="del" data-toggle="modal" data-target="#myModal" href="javascript:void(0)"\
 onClick="deleteContent('+ data.id +' )">删除</a>';
	  return html;
	}
      },
    ],
    drawCallback: function(){
      var oldName = "";
      $("#table tbody td:nth-last-child(2)").editable(modTagUrl, {
	type : "text",
	tooltip : "点击我编辑",
	onblur: "submit",
	name: "name",
	style: "display:inline",
	submitdata : function(value, settings) {
	  oldName = value;
	  var id = $(this).parent().children(":first-child").html();
	  return {id: id};
	},
	callback: function(value, settings) {
	  var ret = JSON.parse(value);
	  if(ret.status == true){
	    $(this).html(ret.newName);
	  }else{
	    $(this).html(oldName);
	  }
	  tagTable.draw('page');
	}
      });
    }
  };

  return conf;
}

function tagConf(){
  var conf = {
    serverSide: true,
    pagingType: "full_numbers",
    iDisplayLength: 10,
    "ordering":true,
    "bSort":true,
    "lengthMenu": [[10, 20, 50], [10, 20, 50]],
    ajax: {
      url: catListUrl
    },
    "columnDefs": [
      {className: "hidden-id", "targets": 0},
      {"className": "dt-left", "targets": 1},
      {"className": "dt-center", "targets": "_all"}
    ],
    columns:[
      {data: 'id'},
      {
	data: null,
	width: '40px',
	orderable: false,
	"render": function ( data, type, full, meta ) {
	  var html = '<a class="edit" href="contenttag/category/'+data.id+'/edit">编辑</a>';
	  return html;
	}
      },
      {data: 'title'},
      {
	data: 'tags',
	"render": function ( data, type, full, meta ) {
	  var html = "";
	  if(data.length > 0){
	    html = data.reduce(function(a, c){return a + c.name + ', ';}, "");
	  }
	  return html;
	}
      }
    ]
  };

  return conf;
}
