/**
 * Created by wanghenshuai on 16/11/17.
 */

var shopBrandTable;
var shopBrandId ;
var shopBrandCurrentRow=-1;
var shopCategoryMaxRow;
$(document).ready(onReady);

function onReady(){
    servePath = App.path;
    $('#btnForEditBrand').attr('disabled',true);
    shopBrandTable = $("#tableBrand").DataTable(conf());
    $('#tableBrand tbody').on( 'click', 'tr', mouseClick);
    $('#tableBrand tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#btnForEditBrand').on('click',function(){
        window.open('/'+servePath+"/shop/brand/"+shopBrandId+"/edit");
    })

    $(document).keydown(function(event){
        //console.log(event.keyCode);
        if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
            event.preventDefault();
            if(!shopBrandTable){
                return ;
            }
            shopBrandMaxRow = shopBrandTable.rows()[0].length;
            if(event.keyCode == 13){
                if(!isNaN(shopBrandId)){
                    if(!$('#btnForEditBrand').attr('disabled')){
                        $('#btnForEditBrand').click();
                    }
                }
            }
            if(event.keyCode == 40){
                shopBrandCurrentRow++;

            }else if(event.keyCode == 38){
                shopBrandCurrentRow--;
            }
            if(shopBrandCurrentRow>=shopBrandMaxRow){
                shopBrandCurrentRow = shopBrandMaxRow - 1;
            }
            if(shopBrandCurrentRow<0){
                shopBrandCurrentRow =0;
            }
            shopBrandTable.$('tr.highlight').removeClass('highlight');
            shopBrandId = shopBrandTable.row(shopBrandCurrentRow).data().id
            shopBrandId = parseInt(shopBrandId);
            $(shopBrandTable.row(shopBrandCurrentRow).node()).addClass('highlight');
            $('#btnForEditBrand').attr('disabled',false);

        }


    });

}

function mouseClick(){
    shopBrandCurrentRow = $(this).context._DT_RowIndex;
    shopBrandTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    shopBrandId = shopBrandTable.row(shopBrandCurrentRow).data().id;
    $('#btnForEditBrand').attr('disabled',false);
}
function mouseDoubleClick(){
    shopBrandId = shopBrandTable.row(shopBrandCurrentRow).data().id;
    window.open('/'+servePath+"/shop/brand/"+shopBrandId+"/edit");
}

function conf(){
    var conf = {
        "bLengthChange":false,
        serverSide: true,
        pagingType: "full_numbers",
        "searching":false,
        "ordering":true,
        "bSort":true,
        "lengthMenu": false,
        "bPaginate":true,
        ajax: {
            url: "/"+servePath+"/shop/brand/list",
            data:{}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'name'},
            {
                "ordering":true,
                'bSort':true,
                data: 'logo',
                width: '62px',
                orderable: false,
                "render": function ( data, type, full, meta ) {
                    var html = '<image style="width:30px;height:30px;" src='+data+'></imag>';
                    return html;
                }
            },
            {data: 'description'},
        ],

    };

    return conf;
}