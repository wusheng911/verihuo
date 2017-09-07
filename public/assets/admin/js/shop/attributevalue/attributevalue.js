/**
 * Created by wanghenshuai on 16/10/11.
 */


$(document).ready(function() {
    table = $("#table").DataTable(conf());

});
function conf(){
    return ;
    var conf = {
        serverSide: true,
        pagingType: "full_numbers",
        iDisplayLength: 10,
        "ordering":true,
        "bSort":true,
        "lengthMenu": [[10, 20, 50], [10, 20, 50]],
        ajax: {
            url: "/max/shop/attributevalue/list",
            data:{}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {
                data: null,
                width: '42px',
                orderable: false,
                "render": function ( data, type, full, meta ) {
                    var html = '<a class="edit" href="attributevalue/'+data.id+'/edit">编辑</a>';
                    html += ' <a class="del" data-toggle="modal" data-target="#myModal" href="javascript:void(0)"\
 onClick="deleteContent('+ data.id +' )">删除</a>';
                    return html;
                }
            },
            {data: 'id', width: "18px", ordering:true },
            {data: 'shop_attribute_id', width: '48px' },
            {data: 'value', width: '48px' },
            {data: 'status', width: '48px' },
            {data: 'pos',"ordering":true,'bSort':true,"asSorting": [ "desc", "asc"] },
        ],
    };

    return conf;
}