/**
 * Created by wanghenshuai on 16/9/27.
 */

var shopCategoryTable;
var shopCategoryId ;
var shopCategoryCurrentRow=-1;
var shopCategoryMaxRow;
var currentTable;
var displayIds={};
var brandId ;
var brandIds =[];
$(document).ready(onReady);
function onReady(){
    servePath = App.path;
    $('#btnForDelCategory').attr('disabled',true);
    $('#btnForNewChildCategory').attr('disabled',true);
    $('#btnForEditCategory').attr('disabled',true);
    shopCategoryTable = $("#tableCategory").DataTable(conf());

    $('#tableCategory tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableCategory tbody').on( 'click', 'tr', mouseClick);
    $('#btnForEditCategory').on('click',function(){
        window.open('/'+servePath+"/shop/category/"+shopCategoryId+"/edit");
    })
    $('#btnForSureNewCategory').on('click',sureNewCategory);
    $('#btnForSureNewChildCategory').on('click',sureNewChildCategory);
    $('#btnForSureDelShopCategory').on('click',sureDelShopCategory);



    $(document).keydown(function(event){
        //console.log(event.keyCode);
        if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
            event.preventDefault();
            if(!shopCategoryTable){
                return ;
            }


            shopCategoryMaxRow = shopCategoryTable.rows()[0].length;
            if(event.keyCode == 13){
                if(!isNaN(shopCategoryId)){
                    if(!$('#btnForEditCategory').attr('disabled')){
                        $('#btnForEditCategory').click();
                    }
                }
            }
            if(event.keyCode == 40){
                shopCategoryCurrentRow++;

            }else if(event.keyCode == 38){
                shopCategoryCurrentRow--;
            }
            if(shopCategoryCurrentRow>=shopCategoryMaxRow){
                shopCategoryCurrentRow = shopCategoryMaxRow - 1;
            }
            if(shopCategoryCurrentRow<0){
                shopCategoryCurrentRow =0;
            }
            shopCategoryTable.$('tr.highlight').removeClass('highlight');
            shopCategoryId = shopCategoryTable.row(shopCategoryCurrentRow).data().id
            shopCategoryId = parseInt(shopCategoryId);
            $(shopCategoryTable.row(shopCategoryCurrentRow).node()).addClass('highlight');
            $('#btnForEditCategory').attr('disabled',false);
            $('#btnForNewChildCategory').attr('disabled',false);
            $('#btnForDelCategory').attr('disabled',false);

        }


    });
}
function setBtnDisabled(value){
    $('#btnForEditCategory').attr('disabled',value);
    $('#btnForNewChildCategory').attr('disabled',value);
    $('#btnForDelCategory').attr('disabled',value);
}
function sureDelShopCategory(){
    $.ajax({
        url: "/"+servePath+"/shop/category/delcategory",
        method: 'get',
        data: {id:shopCategoryId},
        dataType: "json",
        success: function(data){
            console.log(data);
            if(data['value'] == 0){
                console.log('删除成功');
                window.location.reload();
            }else{
                alert(data['message']);
            }
            //shopCategoryReset();

        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureNewChildCategory(){
    var name = $('#newChildCategoryName').val();
    var description = $('#newChildCategoryDescription').val();
    var pos = $('#newChildCategoryPos').val();
    var pid = shopCategoryId;
    var brandList = $('.childSelectBrand');
    var len = brandList.size();
    brandIds =[];
    for(var i=0;i<len;i++){
        //console.log($(brandList[i]).is(':checked')?1:0);
        if($(brandList[i]).is(':checked')){
            brandIds.push($(brandList[i]).attr('id'));
        }
    }
    // if(brandIds.length ==0){
    //     alert('请选择品牌!');
    //     return ;
    // }
    console.log(name,description,pos,pid,brandId);
    $.ajax({
        url: "/"+servePath+"/shop/category/createcategory",
        method: 'get',
        data: {pid:pid,name:name,pos:pos,description:description,bids:brandIds},
        dataType: "json",
        success: function(data){
            shopCategoryReset();
            for(var i=0;i<len;i++){
                $(brandList[i]).prop('checked',false);
            }
            shopCategoryId =0;
            shopCategoryCurrentRow =0;
            $('#btnForEditCategory').attr('disabled',true);
            $('#btnForNewChildCategory').attr('disabled',true);
            $('#newCategoryName').val('');
            $('#newCategoryDescription').val('');
            $('#newCategoryPos').val('');
            $('#newChildCategoryName').val('');
            $('#newChildCategoryDescription').val('');
            $('#newChildCategoryPos').val('');
            //$('#categoryPid').val('');
            $('#myModalNewCategory').modal('hide');
            $('#myModalNewChildCategory').modal('hide');
            $(shopCategoryTable).context[0].ajax.data={};
            shopCategoryTable.draw();
            categorys = data;
            brandId = 0;
            $($('#selectBrand').children()[0]).attr('selected',true);
            $($('#childSelectBrand').children()[0]).attr('selected',true);
        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureNewCategory(){
    var name = $('#newCategoryName').val();
    var description = $('#newCategoryDescription').val();
    var pos = $('#newCategoryPos').val();
    var pid = $('#categoryPid').val();
    var brandList = $('.selectBrand');
    var len = brandList.size();
    brandIds =[];
    for(var i=0;i<len;i++){
        //console.log($(brandList[i]).is(':checked')?1:0);
        if($(brandList[i]).is(':checked')){
            brandIds.push($(brandList[i]).attr('id'));
        }
    }
    // if(brandIds.length ==0){
    //     alert('请选择品牌!');
    //     return ;
    // }
    $.ajax({
        url: "/"+servePath+"/shop/category/createcategory",
        method: 'get',
        data: {pid:pid,name:name,pos:pos,description:description,bids:brandIds},
        dataType: "json",
        success: function(data){
            shopCategoryReset();
            for(var i=0;i<len;i++){
                $(brandList[i]).prop('checked',false);
            }
            $('#newCategoryName').val('');
            $('#newCategoryDescription').val('');
            $('#newCategoryPos').val('');
            $('#myModalNewCategory').modal('hide');
            $(shopCategoryTable).context[0].ajax.data={};
            brandId = 0;
            $($('#selectBrand').children()[0]).attr('selected',true);
            $($('#childSelectBrand').children()[0]).attr('selected',true);
            shopCategoryTable.draw();
            console.log(data);
            categorys = data;
            setBtnDisabled(true);
        },
        error:function(error){
            console.log(error);
        }
    });
}
function mouseClick(){
    shopCategoryCurrentRow = $(this).context._DT_RowIndex;
    shopCategoryTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    shopCategoryId = shopCategoryTable.row(shopCategoryCurrentRow).data().id;
    $('#btnForEditCategory').attr('disabled',false);
    $('#btnForNewChildCategory').attr('disabled',false);
    $('#btnForDelCategory').attr('disabled',false);
}
function mouseDoubleClick(){
    shopCategoryId = shopCategoryTable.row(shopCategoryCurrentRow).data().id;
    window.open('/'+servePath+"/shop/category/"+shopCategoryId+"/edit");

}
function processSelectedFile(filePath, requestingField) {
    $('#' + requestingField).val(filePath).trigger('change');
    $('#img_'+requestingField).attr('src',filePath);
}
function deleteCategoryAlert(id){
    shopCategoryId = id;
    $.confirm(
        {
            content: "<div style='color: red;'>您确定要删除分类吗?那会导致该分类下所有的子分类都被删除!</div>",
            confirm: function(button) {
                sureDelShopCategory();
            }
        });
}
function conf(){
    var conf = {
        "bLengthChange":false,
        serverSide: true,
        pagingType: "full_numbers",
        iDisplayLength: 100,
        "searching":false,
        "ordering":true,
        "bSort":true,
        "lengthMenu": false,
        "bPaginate":true,
        ajax: {
            url: "/"+servePath+"/shop/category/list",
            data:{}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[

            {
                data: 'level',
                width: '50%',
                orderable: false,
                "render": function ( data, type, full, meta ) {
                    var name ="";
                    var html="" ;
                    if(data >0){
                        for(var i =0;i<(data-1);i++){
                            html+='&#12288;&#12288;';
                        }
                        if(full.islast == 0){
                            html+='├─ '
                        }else{
                            html+='└─ ';
                        }
                        name = full.name;
                    }else{
                        name = "<font><b>"+full.name+"</b></font>";
                    }

                    return html + name;
                }

            },
            {data: 'description'},
            {
                data: null,
                width: '72px',
                orderable: false,
                "render": function ( data, type, full, meta ) {
                    var html = '<a class="edit" href="category/'+data.id+'/edit">编辑</a>';
                    html += ' <a class="del" data-toggle="modal" data-target="#myModalDel" href="javascript:void(0)"\
                    onClick="deleteCategoryAlert('+ data.id +' )">删除</a>';
                    return html;
                }
            },
        ],

    };

    return conf;
}
