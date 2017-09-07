/**
 * Created by wanghenshuai on 16/12/02.
 */


var schoolTable;
var schoolId;
var schoolCurrentRow=-1;
var schoolMaxRow
var servePath;
$(document).ready(function() {
    servePath = App.path;
    schoolTable = $("#tableSchool").DataTable(conf());
    $('#tableSchool tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableSchool tbody').on( 'click', 'tr', mouseClick);

    $(document).keydown(keyboardDown);
});

function keyboardDown(event){
    if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
        event.preventDefault();
        if(!schoolTable){
            return ;
        }


        schoolMaxRow = schoolTable.rows()[0].length;
        if(event.keyCode == 13){
            if(!isNaN(schoolId)){
                window.open('/'+servePath+"/school/"+schoolId+"/edit");
            }
        }
        if(event.keyCode == 40){
            schoolCurrentRow++;

        }else if(event.keyCode == 38){
            schoolCurrentRow--;
        }
        if(schoolCurrentRow>=schoolMaxRow){
            schoolCurrentRow = schoolMaxRow - 1;
        }
        if(schoolCurrentRow<0){
            schoolCurrentRow =0;
        }
        schoolTable.$('tr.highlight').removeClass('highlight');
        schoolId = schoolTable.row(schoolCurrentRow).data().id
        schoolId = parseInt(schoolId);
        $(schoolTable.row(schoolCurrentRow).node()).addClass('highlight');

    }

}
function mouseClick(){
    schoolCurrentRow = $(this).context._DT_RowIndex;
    schoolTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    schoolId = schoolTable.row(schoolCurrentRow).data().id;
}
function mouseDoubleClick(){
    schoolId = schoolTable.row(schoolCurrentRow).data().id;
    window.open('/'+servePath+"/school/"+schoolId+"/edit");


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
            url: "/"+servePath+"/school/list",
            data:{}
        },
        "columnDefs": [
            {"schoolName": "dt-left", "targets": 0},
            {"schoolName": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
            {data: 'name'},
		{ "data": null,
			                
						   
			                "render": function ( data, type, full, meta ) {
						                   
						                  return '<img src="'+data.logo+'" style="height:50px;width:50px" >';
						                }},
            {data: 'introduction'},
            { "data": null,


                "render": function ( data, type, full, meta ) {
                    if(data.type==1)
                    {return '高校';}else {return '普通';}
                }},
            { "data": null,
                "width": '90px',
                "render": function ( data, type, full, meta ) {
                    //var is_edit = (data.shop_category == '无效分类') ? ' hide' : '';
                    return '<a class="edit glyphicon glyphicon-edit" href="school/'+data.id+'/edit">编辑</a><a id="list" class="del glyphicon glyphicon-remove"style="pointer-events: auto" href="javascript:void(0)"\
                  data-id="'+ data.id +'" onclick="deleteSchool('+data.id+')">删除</a>';
                }}

        ]
    };

    return conf;
}


function deleteSchool(id) {
    $.confirm({
        content:"确定要删除该学校吗？",
        confirm: function(){
            var sendUrl = 'school/delete/'+id;
            window.location.href = sendUrl;


        }
    });
}
