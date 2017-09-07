/**
 * Created by wanghenshuai on 16/10/11.
 */

var shopAttriteValueAimId;
var shopAttributeValueByIdTable;
var shopAttributeTable;
var shopAttributeId ;
var shopAttributeValueId;
var currentAttributeValueRowIndex=-1;;
var currentAttributeRowIndex=-1;
var shopAttributeMaxRow;
var shopAttributeValueMaxRow;
var currentTableIsAttribute = true;
var shopCategoryId =0;
var servePath;
var isSell=0;
var isBase=0;
var skus=[];
var products=[];
var attributeChilds = [];
var isLast=false;
$(document).ready(function() {
    servePath = App.path;
    $("#sureAddAttributeValue").on("click",sureAddAttributeValue);
    $("#sureEditAttribute").on("click",sureEditAttribute);
    $("#sureAddChildAttribute").on("click",sureAddChildAttribute);
    $("#sureEditAttributeValue").on("click",sureEditAttributeValue);
    $("#sureCreateNewAttribute").on("click",sureCreateNewAttribute);
    //$("#sureDelAttribute").on("click",sureDeleteAttribute);
    $("#btnForDelAttribute").on('click',alertDelAttribute);


    $("#btnForDelAttributeValue").confirm(
        {
            content: "<div style='color: red;'>您确定要强制删除该属性值吗?</div>",
            confirm: function(button) {
                sureDeleteAttributeValue();
            }
        });


    $("#editAttributeSell").on('click',function(){
        if($("#editAttributeSell").is(':checked')){$("#editAttributeRequired").prop('checked',true)}
    });
    $("#editAttributeRequired").on('click',function(){
        if($("#editAttributeSell").is(':checked')){$("#editAttributeRequired").prop('checked',true)}
    });
    $("#addChildAttributeSell").on('click',function(){
        if($("#addChildAttributeSell").is(':checked')){$("#addChildAttributeRequired").prop('checked',true)}
    });
    $("#addChildAttributeRequired").on('click',function(){
        if($("#addChildAttributeSell").is(':checked')){$("#newAttributeRequired").prop('checked',true)}
    });
    $("#newAttributeSell").on('click',function(){
        if($("#newAttributeSell").is(':checked')){$("#newAttributeRequired").prop('checked',true)}
    });
    $("#addChildAttributeRequired").on('click',function(){
        if($("#addChildAttributeSell").is(':checked')){$("#addChildAttributeRequired").prop('checked',true)}
    });
    $("#btnForEditAttribute").on('click',function(){editShopAttribute();});
    $("#btnForAddAttributeValue").on('click',function(){addShopAttributeValue();});
    $("#btnForAddChildAttribute").on('click',function(){addShopChildAttribute();});
    $("#btnForEditAttributeValue").on('click',function(){editShopAttributeValue();});
    $("#sureCreateAimAttributeChildAttribute").on('click',function(){createShopAttribute();});

    setAttributeAboutBtnDisabled(true);
    setAttributeValueAboutBtnDisabled(true);


    shopAttriteValueAimId =0;
    shopAttributeTable = $("#tableAttribute").DataTable(conf());
    shopAttributeValueByIdTable = $("#tableAttributeValue").DataTable(confValue());
    $('#tableAttribute tbody').on( 'click', 'tr', function () {
            currentAttributeRowIndex = $(this).context._DT_RowIndex;
            currentTableIsAttribute = true;
            shopAttributeTable.$('tr.highlight').removeClass('highlight');
            $(this).addClass('highlight');
            shopAttributeId = shopAttributeTable.row(currentAttributeRowIndex).data().id;
            console.log(shopAttributeId);
            freshenAttributeValueTable(shopAttributeId);
            setAttributeAboutBtnDisabled(false);
            getAttributeInfo();
    } );
    $('#tableAttribute tbody').on( 'dblclick', 'tr', function () {
        $("#btnForEditAttribute").click();
    } );
    $('#tableAttributeValue tbody').on( 'dblclick', 'tr', function () {
        $("#btnForEditAttributeValue").click();
    } );


    $('#tableAttributeValue tbody').on( 'click', 'tr', function () {
        currentAttributeValueRowIndex = $(this).context._DT_RowIndex;
        currentTableIsAttribute = false;
        shopAttributeValueByIdTable.$('tr.highlight').removeClass('highlight');
        shopAttributeValueId = shopAttributeValueByIdTable.row(currentAttributeValueRowIndex).data().id;
        console.log(shopAttributeValueId);
        $(this).addClass('highlight');
        setAttributeValueAboutBtnDisabled(false);
        if(isSell){
            $("#btnForCreateAimAttributeChildAttribute").attr('disabled',true);
        }
    } );

    $(document).keydown(function(event){
        //console.log(event.keyCode);
        // 37  38  39  40  左上右下
        if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
            event.preventDefault();
            if(!shopAttributeTable){
                return ;
            }
            shopAttributeMaxRow = shopAttributeTable.rows()[0].length;
            if(shopAttributeValueByIdTable){
                shopAttributeValueMaxRow = shopAttributeValueByIdTable.rows()[0].length;
            }
            if(currentTableIsAttribute){
                if(event.keyCode == 13){
                    if(!isNaN(shopAttributeId)){
                        if(!$('#btnForEditAttribute').attr('disabled')){
                            $('#btnForEditAttribute').click();
                        }
                    }
                    return ;
                }
                if(event.keyCode == 40){
                    currentAttributeRowIndex++;

                }else if(event.keyCode == 38){
                    currentAttributeRowIndex--;

                }
                if(currentAttributeRowIndex>=shopAttributeMaxRow){
                    currentAttributeRowIndex = shopAttributeMaxRow - 1;
                }
                if(currentAttributeRowIndex<0){
                    currentAttributeRowIndex =0;
                }

                shopAttributeTable.$('tr.highlight').removeClass('highlight');
                shopAttributeId = shopAttributeTable.row(currentAttributeRowIndex).data().id;
                shopAttributeId = parseInt(shopAttributeId);
                if(event.keyCode == 40 || event.keyCode == 38){
                    getAttributeInfo();

                }
                $(shopAttributeTable.row(currentAttributeRowIndex).node()).addClass('highlight');

                if(event.keyCode == 39){
                    if(shopAttributeValueMaxRow>0){
                        currentTableIsAttribute = false;
                        currentAttributeValueRowIndex =0;
                        setAttributeValueAboutBtnDisabled(false);
                        if(isSell){
                            $("#btnForCreateAimAttributeChildAttribute").attr('disabled',true);
                        }
                        $(shopAttributeValueByIdTable.row(currentAttributeValueRowIndex).node()).addClass('highlight');
                        shopAttributeValueId = shopAttributeTable.row(currentAttributeRowIndex).data().id;
                        shopAttributeValueId = parseInt(shopAttributeValueId);
                    }
                    return ;
                }
                console.log(currentAttributeRowIndex);
                //$(shopAttributeTable.row(currentAttributeRowIndex).node()).addClass('highlight');
                //$('#btnForEditCategory').attr('disabled',false);
                freshenAttributeValueTable(shopAttributeId);
                setAttributeAboutBtnDisabled(false);


            }else{
                if(event.keyCode == 13){
                    if(!isNaN(shopAttributeValueId)){
                        if(!$('#btnForEditAttributeValue').attr('disabled')){
                            console.log('哼哼哈嘿');
                            $('#btnForEditAttributeValue').click();
                        }
                    }
                    return ;
                }
                if(event.keyCode == 40){
                    currentAttributeValueRowIndex++;
                }else if(event.keyCode == 38){
                    currentAttributeValueRowIndex--;
                }
                console.log(currentAttributeValueRowIndex,shopAttributeValueMaxRow);
                if(currentAttributeValueRowIndex>= shopAttributeValueMaxRow){
                    currentAttributeValueRowIndex = shopAttributeValueMaxRow -1;
                }
                if(currentAttributeValueRowIndex<0){
                    currentAttributeValueRowIndex =0;
                }

                shopAttributeValueByIdTable.$('tr.highlight').removeClass('highlight');
                if(event.keyCode == 37){
                    currentTableIsAttribute = true;
                    shopAttributeValueId=-1;
                    setAttributeValueAboutBtnDisabled(true);
                    return;
                }
                shopAttributeValueId = shopAttributeValueByIdTable.row(currentAttributeValueRowIndex).data().id;
                shopAttributeValueId = parseInt(shopAttributeValueId);

                $(shopAttributeValueByIdTable.row(currentAttributeValueRowIndex).node()).addClass('highlight');


            }
        }




    });

});
function alertDelAttribute(){
    $.confirm(
        {
            content: setAlertAttributeText(),
            confirm: function(button) {
                if(isLast){
                    alert('最后一个属性不能删除');
                }else{
                    sureDeleteAttribute();
                }

            }
        });
}
function setAlertAttributeText(){
    console.log('我是上来就运行的');
    var str="<div style='color: red;'>";
    str+='该属性的子属性包括:';
    var len = attributeChilds.length;
    for(var i=0;i<len;i++){
        str+= '《'+attributeChilds[i].name +'》';
    }
    str+="</div>";
    if(attributeChilds.length ==0){
        str ="";
    }
    var productStr ='';
    productStr+="<div style='color: red;'>";
    productStr+='该属性的涉及到的产品包括:';
    len = products.length;
    var tmpProduct = {};
    for(i=0;i<len;i++){

        if(!tmpProduct[products[i].name]){
            tmpProduct[products[i].name] = true;
            productStr+= "<a href=/"+servePath+'/shop/product/'+products[i].id+'/edit>'+'《'+products[i].name +'》'+'</a>';
        }

    }
    productStr+="</div>";
    if(len == 0){
        productStr = '';
    }
    var lastStr = "";
    if(isLast){
        lastStr+="<div style='color: red;'>此属性为最后一个销售属性,不可删除!</div>";
    }
    str += productStr + lastStr;
    return str;
}
function setAttributeAboutBtnDisabled(boo){
    $("#btnForEditAttribute").attr('disabled',boo);
    $("#btnForAddAttributeValue").attr('disabled',boo);
    $("#btnForDelAttribute").attr('disabled',boo);
    $("#btnForAddChildAttribute").attr('disabled',boo);
}
function setAttributeValueAboutBtnDisabled(boo){
    $("#btnForEditAttributeValue").attr('disabled',boo);
    $("#btnForDelAttributeValue").attr('disabled',boo);
    $("#btnForCreateAimAttributeChildAttribute").attr('disabled',boo);

}
function sureCreateNewAttribute(){
    var name = $("#newAttributeName").val();
    var status = $("#newAttributeStatus").is(':checked')?1:0;
    var pos = $("#newAttributePos").val();
    var showName = $("#newAttributeShowName").val();
    var shopCategoryId = $("#categoryPid").val();
    var required = $("#newAttributeRequired").is(':checked')?1:0;
    var base = $("#newAttributeBase").is(':checked')?1:0
    var sell = $("#newAttributeSell").is(':checked')?1:0;
    var optional = $("#newAttributeOptional").is(':checked')?1:0;
    if(name =='' || showName==''){
        alert('请输入属性名称');
        return ;
    }
    if(base==0 && sell==0 && optional==0){
        alert('请选择属性类型');
        return ;
    }
    shopCategoryId = parseInt(shopCategoryId);
    if(isNaN(shopCategoryId) || shopCategoryId<1){
        alert('请选择分类');
        return ;
    }
    console.log(shopCategoryId,name,pos,status,showName,required,base,sell,optional);
    if(sell == 1){
        $.confirm(
            {
                content: "<div style='color: red;'>您确定要添加新的《销售属性》吗?该分类下所对应的'产品'都会被删除!</div>",
                confirm: function(button) {
                    $.ajax({
                        url: "/"+servePath+"/shop/attribute/createattribute",
                        method: 'get',
                        data: {shop_category_id:shopCategoryId,name:name,pos:pos,status:status,show_name:showName,required:required,is_base:base,is_sell:sell,is_optional:optional},
                        dataType: "json",
                        success: function(data){
                            $("#newAttributeName").val("");
                            $("#newAttributePos").val("");
                            $("#newAttributeShowName").val("");
                            $("#categoryPid").val("");
                            $("#newAttributeStatus").prop('checked',true);
                            $("#newAttributeRequired").removeAttr('checked');
                            $("#newAttributeBase").prop('checked',true);
                            $("#newAttributeSell").removeAttr('checked');
                            $("#newAttributeOptional").removeAttr('checked');
                            $('#myModalNewAttribute').modal('hide');
                            checkShopAttributeValue(shopAttributeId);
                            shopCategoryReset();
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                }
            });
    }else{
        $.ajax({
            url: "/"+servePath+"/shop/attribute/createattribute",
            method: 'get',
            data: {shop_category_id:shopCategoryId,name:name,pos:pos,status:status,show_name:showName,required:required,is_base:base,is_sell:sell,is_optional:optional},
            dataType: "json",
            success: function(data){
                $("#newAttributeName").val("");
                $("#newAttributePos").val("");
                $("#newAttributeShowName").val("");
                $("#categoryPid").val("");
                $("#newAttributeStatus").prop('checked',true);
                $("#newAttributeRequired").removeAttr('checked');
                $("#newAttributeBase").prop('checked',true);
                $("#newAttributeSell").removeAttr('checked');
                $("#newAttributeOptional").removeAttr('checked');
                $('#myModalNewAttribute').modal('hide');
                checkShopAttributeValue(shopAttributeId);
                shopCategoryReset();
            },
            error:function(error){
                console.log(error);
            }
        });
    }



}
function sureEditAttribute(){
    var name = $("#editAttributeName").val();
    var status = $("#editAttributeStatus").is(':checked')?1:0;
    var pos = $("#editAttributePos").val();
    var showName = $("#editAttributeShowName").val();
    var required = $("#editAttributeRequired").is(':checked')?1:0;
    var base = $("#editAttributeBase").is(':checked')?1:0;
    var sell = $("#editAttributeSell").is(':checked')?1:0;
    var optional = $("#editAttributeOptional").is(':checked')?1:0;
    $.ajax({
        url: "/"+servePath+"/shop/attribute/editattribute",
        method: 'get',
        data: {id:shopAttributeId,name:name,pos:pos,status:status,show_name:showName,required:required,is_base:base,is_sell:sell,is_optional:optional},
        dataType: "json",
        success: function(data){
            $('#myModalEditShopAttribute').modal('hide');
            checkShopAttributeValue(shopAttributeId);
            resetAttributeByCategoryID();
        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureAddChildAttribute(){
    var name = $("#addChildAttributeName").val();
    var pos = $("#addChildAttributePos").val();
    var showName = $("#addChildAttributeShowName").val();
    var status = $("#addChildAttributeStatus").is(':checked')?1:0;
    var required = $("#addChildAttributeRequired").is(':checked')?1:0;
    var base = $("#addChildAttributeBase").is(':checked')?1:0;
    var sell = $("#addChildAttributeSell").is(':checked')?1:0;
    var optional = $("#addChildAttributeOptional").is(':checked')?1:0;

    $.ajax({
        url: "/"+servePath+"/shop/attribute/addchildattribute",
        method: 'get',
        data: {id:shopAttributeId,name:name,pos:pos,status:status,show_name:showName,required:required,is_base:base,is_sell:sell,is_optional:optional},
        dataType: "json",
        success: function(data){
            $('#myModalAddShopChildAttribute').modal('hide');
            checkShopAttributeValue(shopAttributeId);
        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureEditAttributeValue(){
    var status = $("#editAttributeValueStatus").is(':checked')?1:0;
    var pos = $("#editAttributeValuePos").val();
    var value = $("#editAttributeValueValue").val();
    $.ajax({
        url: "/"+servePath+"/shop/attributevalue/editattributevalue",
        method: 'get',
        data: {id:shopAttributeValueId,pos:pos,status:status,value:value},
        dataType: "json",
        success: function(data){
            shopAttributeId = data;
            $('#myModalEditShopAttributeValue').modal('hide');
            //checkShopAttributeValue(data.id);
            freshenAttributeValueTable(data);
        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureAddAttributeValue(){
    shopAttributeId = $("#attributeIdForAddAttributeValue").val();
        if(isNaN(shopAttributeId)){
            console.log('shopAttributeId不是数字',shopAttributeId);
            return ;
        }
        var value = $("#addAttributeValueValue").val();
        var status = $("#addAttributeValueStatus").is(':checked')?1:0;;
        var pos = $("#addAttributeValuePos").val();
        var shopAttributeId = shopAttributeId;
    $.ajax({
        url: "/"+servePath+"/shop/attributevalue/addvalue",
        method: 'get',
        data: {value:value,pos:pos,status:status,shop_attribute_id:shopAttributeId},
        dataType: "json",
        success: function(data){
            $("#addAttributeValueValue").val("");
            $("#addAttributeValuePos").val("");
            $("#addAttributeValueStatus").prop('checked',true);
            $('#myModalAddShopAttributeValue').modal('hide')
            //checkShopAttributeValue(shopAttributeId);
            freshenAttributeValueTable(shopAttributeId);
        },
        error:function(error){
            console.log(error);
        }
    });

}
function addShopChildAttribute(){
    $("#addChildAttributeName").val('');
    $("#addChildAttributeStatus").val('');
    $("#addChildAttributeShowName").val('');
    $("#addChildAttributeLevel").val('');
    $("#addChildAttributeStatus").prop('checked',true);
    $("#addChildAttributeRequired").removeAttr('checked');
    $("#addChildAttributeBase").removeAttr('checked');
    $("#addChildAttributeSell").removeAttr('checked');
    $("#addChildAttributeOptional").removeAttr('checked');
}
function addShopAttributeValue(){

    $("#attributeIdForAddAttributeValue").val(shopAttributeId);
    $("#addAttributeValueValue").val('');
    $("#addAttributeValuePos").val('');
    $("#addAttributeValueStatusFalse").prop('checked',true);

}
//该方法用于 根据属性值创建属性
function createShopAttribute(){
    console.log(shopAttributeValueId);
    $.ajax({
        url: "/"+servePath+"/shop/attribute/addattribute",
        method: 'get',
        data: {id:shopAttributeValueId},
        dataType: "json",
        success: function(data){
            $('#myModalCreateAimAttributeChildAttribute').modal('hide');
            if(data.id<0){
                alert('不能创建重复的属性');
            }else{
                resetAttributeByCategoryID();
                checkShopAttributeValue(data.id);
            }


        },
        error:function(error){
            console.log(error);
        }
    });
}
function editShopAttribute(){

    $("#InfoTextForAttribute").html('请求数据中,请稍后……');
    $("#editAttributeName").val('');
    $("#editAttributeLevel").val('');
    $("#editAttributePos").val('');
    $("#editAttributeShowName").val('');
    $("#editAttributeStatus").removeAttr('checked');
    $("#editAttributeRequired").removeAttr('checked');
    $("#editAttributeBase").removeAttr('checked');
    $("#editAttributeSell").removeAttr('checked');
    $("#editAttributeOptional").removeAttr('checked');
    $("#editAttributeName").attr('readOnly',true);
    $("#editAttributePos").attr('readOnly',true);
    $("#editAttributeShowName").attr('readOnly',true);

    //shopAttributeId = id;
    $.ajax({
        url: "/"+servePath+"/shop/attribute/getattribute",
        method: 'get',
        data: {id:shopAttributeId},
        dataType: "json",
        success: function(data){
            $("#InfoTextForAttribute").html('请编辑');
            $("#editAttributeName").val(data.name);
            $("#editAttributePos").val(data.pos);
            $("#editAttributeLevel").val(data.level);
            $("#editAttributeShowName").val(data.show_name);
            $("#editAttributeName").removeAttr('readOnly');
            $("#editAttributePos").removeAttr('readOnly');
            $("#editAttributeShowName").removeAttr('readOnly');
            if(data.status){$("#editAttributeStatus").prop('checked',true)}else{$("#editAttributeStatus").removeAttr("checked")};
            if(data.required){$("#editAttributeRequired").prop('checked',true)}else{$("#editAttributeRequired").removeAttr("checked")};
            if(data.is_base){$("#editAttributeBase").prop('checked',true)}else{$("#editAttributeBase").removeAttr("checked")};
            if(data.is_sell){$("#editAttributeSell").prop('checked',true)}else{$("#editAttributeSell").removeAttr("checked")};
            if(data.is_optional){$("#editAttributeOptional").prop('checked',true)}else{$("#editAttributeOptional").removeAttr("checked")};

        },
        error:function(error){
            console.log(error);
        }
    });
    
}
function editShopAttributeValue(){

    $("#InfoTextForAttributeValue").html('请求数据中,请稍后……');
    $("#editAttributeValueValue").val('');
    $("#editAttributeValueStatusFalse").prop('checked',true);
    $("#editAttributeValuePos").val('');
    $("#editAttributeValueValue").attr('readOnly',true);
    $("#editAttributeValuePos").attr('readOnly',true);
    $.ajax({
        url: "/"+servePath+"/shop/attributevalue/getattributevalue",
        method: 'get',
        data: {id:shopAttributeValueId},
        dataType: "json",
        success: function(data){
            console.log(data);
            $("#InfoTextForAttributeValue").html('请编辑');

            $("#editAttributeValueValue").removeAttr('readOnly');
            $("#editAttributeValuePos").removeAttr('readOnly');

            $("#editAttributeValueValue").val(data.value);
            if(data.status){$("#editAttributeValueStatus").prop('checked',true);}else{$("#editAttributeValueStatus").removeAttr('checked');}
            $("#editAttributeValuePos").val(data.pos);

        },
        error:function(error){
            console.log(error);
        }
    });
}
function freshenAttributeValueTable(id){
    shopAttriteValueAimId = id;
    $(shopAttributeValueByIdTable).context[0].ajax.data={id:shopAttriteValueAimId};
    shopAttributeValueByIdTable.draw();
    setAttributeValueAboutBtnDisabled(true);
}
function checkShopAttributeValue(id){

    //$("#tableAttributeValue").context[0].ajax.data = {id:id};
    shopAttriteValueAimId = id;
    $(shopAttributeValueByIdTable).context[0].ajax.data={id:shopAttriteValueAimId};
    //shopAttributeValueByIdTable.draw();
    shopAttributeTable.draw();
    setAttributeAboutBtnDisabled(true);
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
            url: "/"+servePath+"/shop/attribute/list",
            data:{id:0}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {
                data: 'level',
                width: '170px',
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
            {data: 'shop_category_id' ,width: '48px', },
            {data: 'show_name',"ordering":true,'bSort':true,"asSorting": [ "desc", "asc"] },
            {data: 'required'},
            {data: 'is_sell'},
        ],
    };

    return conf;
}

function confValue(){
    var conf = {
        "bPaginate":false,
        serverSide: true,
        pagingType: "full_numbers",
        iDisplayLength: 100,
        "searching":false,
        "ordering":true,
        "bSort":true,
        "lengthMenu": false,
        ajax: {
            url: "/"+servePath+"/shop/attributevalue/listbyid",
            data:{id:shopAttriteValueAimId}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'value', width: '48px' },
            {data: 'status', width: '48px' },
            {data: 'pos',width: '48px'},
        ],
    };

    return conf;
}
function resetAttributeByCategoryID(){
    var aimID = shopCategoryId;
    $(shopAttributeTable).context[0].ajax.data={id:aimID};
    shopAttributeTable.draw();
}
function sureSelectCategory(id){
    shopCategoryId = id;
    resetAttributeByCategoryID();
}
function sureDeleteAttribute(){
    $('#myModalDelAttribute').modal('hide');
    $.ajax({
        url: "/"+servePath+"/shop/attribute/delattribute",
        method: 'get',
        data: {id:shopAttributeId},
        dataType: "json",
        success: function(data){
            console.log(data);
            var value = data['value'];
            var message = data['message'];
            if(value == 0){
                window.location.reload();
            }else{
                alert(message);
            }
        },
        error:function(error){
            console.log(error);
        }
    });
}
function sureDeleteAttributeValue(){
    $('#myModalDelAttributeValue').modal('hide');
    $.ajax({
        url: "/"+servePath+"/shop/attributevalue/delattributevalue",
        method: 'get',
        data: {id:shopAttributeValueId},
        dataType: "json",
        success: function(data){
            console.log(data);
            if(data['value'] != 0){
                alert(data['message']);
            }else{
                window.location.reload();
            }

        },
        error:function(error){
            console.log(error);
        }
    });
}
function getAttributeInfo(){
    $.ajax({
        url: "/"+servePath+"/shop/attribute/attributeinfo",
        method: 'get',
        data: {id:shopAttributeId},
        dataType: "json",
        success: function(data){
            console.log(data);
            $("#sureDelAttribute").attr('disabled',true);
            if(data['value'] == 0){
                attributeChilds = data['childs'];
                products = data['products'];
                skus = data['skus'];
                isSell = data['attr'].is_sell;
                isBase = data['attr'].is_base;
                isLast = data['isLast'];
                console.log('isLast:'+isLast);
            }else{
                alert(data['message']);
            }
        },
        error:function(error){
            console.log(error);
        }
    });
}