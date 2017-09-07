/**
 * Created by wanghenshuai on 16/12/02.
 */


var teacherTable;
var teacherId;
var teacherCurrentRow=-1;
var teacherMaxRow
var servePath;
$(document).ready(function() {
    servePath = App.path;
    teacherTable = $("#tableTeacher").DataTable(conf());
    $('#tableTeacher tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableTeacher tbody').on( 'click', 'tr', mouseClick);

    $(document).keydown(keyboardDown);
});

function keyboardDown(event){
    if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
        event.preventDefault();
        if(!teacherTable){
            return ;
        }


        teacherMaxRow = teacherTable.rows()[0].length;
        if(event.keyCode == 13){
            if(!isNaN(teacherId)){
                window.open('/'+servePath+"/teacher/"+teacherId+"/edit");
            }
        }
        if(event.keyCode == 40){
            teacherCurrentRow++;

        }else if(event.keyCode == 38){
            teacherCurrentRow--;
        }
        if(teacherCurrentRow>=teacherMaxRow){
            teacherCurrentRow = teacherMaxRow - 1;
        }
        if(teacherCurrentRow<0){
            teacherCurrentRow =0;
        }
        teacherTable.$('tr.highlight').removeClass('highlight');
        teacherId = teacherTable.row(teacherCurrentRow).data().id
        teacherId = parseInt(teacherId);
        $(teacherTable.row(teacherCurrentRow).node()).addClass('highlight');

    }

}
function mouseClick(){
    teacherCurrentRow = $(this).context._DT_RowIndex;
    teacherTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    teacherId = teacherTable.row(teacherCurrentRow).data().id;
}
function mouseDoubleClick(){
    teacherId = teacherTable.row(teacherCurrentRow).data().id;
    window.open('/'+servePath+"/teacher/"+teacherId+"/edit");


}
function conf(){
    var conf = {
        serverSide: true,
        "bSort":true,
        iDisplayLength: 100,
        'asSorting':'desc',
        "lengthMenu": false,
        "bPaginate":true,
        "bLengthChange":false,
        ajax: {
            url: "/"+servePath+"/teacher/list",
            data:{}
        },
        "columnDefs": [
            {"teacherName": "dt-left", "targets": 0},
            {"teacherName": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
            {data: 'name'},
		
            {data: 'phone'},

            {data: 'email'},
		{ "data": null,
			                
						   
			                "render": function ( data, type, full, meta ) {
						                   
						                  return '<img src="'+data.portrait+'" style="height:50px;width:50px" >';
						                }},
            {data: 'introduction'},
            { "data": null,


                "render": function ( data, type, full, meta ) {
                   if(data.level==1)
                   {return '是';}else {return '否';}
                }},


            { "data": null,
                "width": '90px',
                "render": function ( data, type, full, meta ) {
                    //var is_edit = (data.shop_category == '无效分类') ? ' hide' : '';
                    return '<a class="edit glyphicon glyphicon-edit" href="teacher/'+data.id+'/edit">编辑</a><a id="list" class="del glyphicon glyphicon-remove"style="pointer-events: auto" href="javascript:void(0)"\
                  data-id="'+ data.id +'" onclick="deleteTeacher('+data.id+')">删除</a>';
                }}

        ]
    };

    return conf;
}


function deleteTeacher(id) {
    $.confirm({
        content:"确定要删除该老师吗？",
        confirm: function(){
            var sendUrl = 'teacher/delete/'+id;
            window.location.href = sendUrl;


        }
    });
}
