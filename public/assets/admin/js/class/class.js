/**
 * Created by wanghenshuai on 16/12/02.
 */


var vClassTable;
var vClassId;
var vClassCurrentRow=-1;
var vClassMaxRow
var servePath;
$(document).ready(function() {
    servePath = App.path;
    vClassTable = $("#tableVClass").DataTable(conf());
    $('#tableVClass tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableVClass tbody').on( 'click', 'tr', mouseClick);

    $(document).keydown(keyboardDown);
});

function keyboardDown(event){
    if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
        event.preventDefault();
        if(!vClassTable){
            return ;
        }


        vClassMaxRow = vClassTable.rows()[0].length;
        if(event.keyCode == 13){
            if(!isNaN(vClassId)){
                window.open('/'+servePath+"/class/"+vClassId+"/edit");
            }
        }
        if(event.keyCode == 40){
            vClassCurrentRow++;

        }else if(event.keyCode == 38){
            vClassCurrentRow--;
        }
        if(vClassCurrentRow>=vClassMaxRow){
            vClassCurrentRow = vClassMaxRow - 1;
        }
        if(vClassCurrentRow<0){
            vClassCurrentRow =0;
        }
        vClassTable.$('tr.highlight').removeClass('highlight');
        vClassId = vClassTable.row(vClassCurrentRow).data().id
        vClassId = parseInt(vClassId);
        $(vClassTable.row(vClassCurrentRow).node()).addClass('highlight');

    }

}
function mouseClick(){
    vClassCurrentRow = $(this).context._DT_RowIndex;
    vClassTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    vClassId = vClassTable.row(vClassCurrentRow).data().id;
}
function mouseDoubleClick(){
    vClassId = vClassTable.row(vClassCurrentRow).data().id;
    window.open('/'+servePath+"/class/"+vClassId+"/edit");


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
            url: "/"+servePath+"/class/list",
            data:{}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
            {data: 'name'},
            {data: 'introduction'},
            { "data": null,


                "render": function ( data, type, full, meta ) {
                    if(data.status==1)
                    {return '是';}else {return '否';}
                }},
            { "data": null,
                "width": '90px',
                "render": function ( data, type, full, meta ) {
                    //var is_edit = (data.shop_category == '无效分类') ? ' hide' : '';
                    return '<a class="edit glyphicon glyphicon-edit" href="class/'+data.id+'/edit">编辑</a><a id="list" class="del glyphicon glyphicon-remove"style="pointer-events: auto" href="javascript:void(0)"\
                  data-id="'+ data.id +'" onclick="deleteClass('+data.id+')">删除</a>';
                }}

        ]
    };

    return conf;
}


function deleteClass(id) {
    $.confirm({
        content:"确定要删除该课程吗？",
        confirm: function(){
            var sendUrl = 'class/delete/'+id;
            window.location.href = sendUrl;


        }
    });
}
