/**
 * Created by wanghenshuai on 16/12/02.
 */


var customerTable;
var customerId;
var customerCurrentRow=-1;
var customerMaxRow
var servePath;
$(document).ready(function() {
    servePath = App.path;
    customerTable = $("#tableCustomer").DataTable(conf());
    $('#tableCustomer tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableCustomer tbody').on( 'click', 'tr', mouseClick);

    $(document).keydown(keyboardDown);
});

function keyboardDown(event){
    if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
        event.preventDefault();
        if(!customerTable){
            return ;
        }


        customerMaxRow = customerTable.rows()[0].length;
        if(event.keyCode == 13){
            if(!isNaN(customerId)){
                window.open('/'+servePath+"/customer/"+customerId+"/edit");
            }
        }
        if(event.keyCode == 40){
            customerCurrentRow++;

        }else if(event.keyCode == 38){
            customerCurrentRow--;
        }
        if(customerCurrentRow>=customerMaxRow){
            customerCurrentRow = customerMaxRow - 1;
        }
        if(customerCurrentRow<0){
            customerCurrentRow =0;
        }
        customerTable.$('tr.highlight').removeClass('highlight');
        customerId = customerTable.row(customerCurrentRow).data().id
        customerId = parseInt(customerId);
        $(customerTable.row(customerCurrentRow).node()).addClass('highlight');

    }

}
function mouseClick(){
    customerCurrentRow = $(this).context._DT_RowIndex;
    customerTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    customerId = customerTable.row(customerCurrentRow).data().id;
}
function mouseDoubleClick(){
    customerId = customerTable.row(customerCurrentRow).data().id;
    window.open('/'+servePath+"/customer/"+customerId+"/edit");


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
            url: "/"+servePath+"/customer/list",
            data:{}
        },
        "columnDefs": [
            {"customerName": "dt-left", "targets": 0},
            {"customerName": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
            {data: 'user_name'},
		
            {data: 'phone'},

            {data: 'email'},



            { "data": null,
                "width": '90px',
                "render": function ( data, type, full, meta ) {
                    //var is_edit = (data.shop_category == '无效分类') ? ' hide' : '';
                    return '<a class="edit glyphicon glyphicon-edit" href="customer/'+data.id+'/edit">编辑</a><a id="list" class="del glyphicon glyphicon-remove"style="pointer-events: auto" href="javascript:void(0)"\
                  data-id="'+ data.id +'" onclick="deleteCustomer('+data.id+')">删除</a>';
                }}

        ]
    };

    return conf;
}


function deleteCustomer(id) {
    $.confirm({
        content:"确定要删除该学生吗？",
        confirm: function(){
            var sendUrl = 'customer/delete/'+id;
            window.location.href = sendUrl;


        }
    });
}
