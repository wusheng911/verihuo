$(document).ready(function () {
    var table = $('#product_table').DataTable({
        dom: "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        //dom: 't<"dataTables_info"il>p',
        "serverSide": true,
        "pagingType": "full_numbers",
        "iDisplayLength": 10,
        "lengthMenu": [[10, 20, 50], [10, 20, 50]],
        "ordering":true,
        //"bSort":true,
        "aoColumnDefs": [{"bSortable": false, "aTargets": [1,2,3,4,5,6,7]}],
        "bStateSave": true,
        ajax: {
            url: getListUrl,
            //data:{'sid':'','scategory':'','stitle':''}
        },
        "columns": [
            { "data": "id","width": '25px' },
            { "data": "name" },
            { "data": "shop_category" },
            { "data": "is_available","width": '65px' },
            { "data": "status","width": '65px' },
            { "data": "show_price","width": '80px' },
            { "data": "nums","width": '50px' },
            { "data": null,
              "width": '90px',
              "render": function ( data, type, full, meta ) {
                  //var is_edit = (data.shop_category == '无效分类') ? ' hide' : '';
                  return '<a class="edit glyphicon glyphicon-edit" href="product/'+data.id+'/edit">编辑</a><a id="list" class="del glyphicon glyphicon-remove"style="pointer-events: auto" href="javascript:void(0)"\
                  data-id="'+ data.id +'" onclick="deleteProduct('+data.id+')">删除</a>';
            }}
        ],
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
    });
    $('#category_search,#product_available,#product_status').on('changed.bs.select', function(e) {
        //console.log($(this).val());
        pCategory = $('#category_search').val();
        pAvailable = $('#product_available').val();
        pStatus = $('#product_status').val();
        //pName = $('#product_name').val();
        table.search(pCategory+','+pAvailable+','+pStatus+','+'').draw();
    });
    $(".s-product-name").click(function(){
        pName = $('#product_name').val();
        table.search(''+','+''+','+''+','+pName).draw();
    })
});

function deleteProduct(id) {
    $.confirm({
        content:"确定要删除该商品吗？",
        confirm: function(){
            var sendUrl = delContentUrl+'/'+id;
            $.ajax({
                method: "POST",
                url: sendUrl,
                data: {id:id,_method:"delete"},
                dataType: "json",
                success: function(data){
                    //console.log(data);
                    if(data.status==0){
                        alert(data.errorMessage);
                    } else {
                        window.location.href = window.location.href;
                    }
                }
            });
        }
    });
}