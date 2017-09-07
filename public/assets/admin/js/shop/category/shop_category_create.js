/**
 * Created by wanghenshuai on 16/9/28.
 */

var servePath;
var categorys;
var categoryStack = ['#parentCategory_0'];
var currentSelectName= categoryStack[0];
var currentSelectLevel;
var currentSelectId;
var levelIds =[];
var currentCategory;
var selecterCnt;
var selects;
function processSelectedFile(filePath, requestingField) {
    $('#' + requestingField).val(filePath).trigger('change');
    $('#img_'+requestingField).attr('src',filePath);
}
$(document).ready(onReady);

function onReady(){
    servePath = App.path;
    categorys = App.categorys;
    currentCategory = App.category;
    console.log(categorys);
    selects = $('select');
    selecterCnt = selects.size();
    $(categoryStack[0]).on('change',onCategorySelected);
    var option='';
    var len = categorys.length;
    var category;
    if(typeof sureSelectCategory === 'function' ){
        registerSelectContainerName('selectFilterContainer',sureSelectCategory,['form-control']);
    }

    //registerSelectContainerName('selectFilterBag',sureSelectCategory,'form-control');

    if(!currentCategory){
        for(var i=0;i<len;i++){
            category = categorys[i];
            if(!category.level){
                option+="<option id='"+ category.id+"'data="+category.pid+" level='"+category.level+"'>"+category.name+"</option>"
            }
        }
        $('#parentCategory_0').on('change',onCategorySelected);
        $('#parentCategory_0').append($(option));
    }else{
        var level = currentCategory.level;
        console.log('我来了哈哈哈');
        if(level == 0){
            for(var i=0;i<len;i++){
                category = categorys[i];

                //判断level是否为0
                if(!category.level && category.id != currentCategory.id){
                    option+="<option id='"+ category.id+"'data="+category.pid+" level='"+category.level+"'>"+category.name+"</option>"
                }
            }
            $(categoryStack[0]).append($(option));
        }else{
            var tmpCategory = currentCategory;
            for(i=0;i<level;i++){
                for(var j=0;j<len;j++){
                    category = categorys[j];
                    if(category.id == tmpCategory.pid){
                        levelIds.unshift(category.id + "");
                        tmpCategory = category;
                        break;
                    }
                }
            }
            for(i=0;i<levelIds.length;i++){

                var str="";
                for(j=0;j<len;j++){
                    if((categorys[j].level+"") == i ){
                        if(currentCategory.id != categorys[j].id){
                            if(categorys[j].id == levelIds[i]){
                                str+= "<option selected='true' id='"+ categorys[j].id+"'data="+categorys[j].pid+" level='"+categorys[j].level+"'>"+categorys[j].name+"</option>";
                            }else{
                                str+= "<option id='"+ categorys[j].id+"'data="+categorys[j].pid+" level='"+categorys[j].level+"'>"+categorys[j].name+"</option>";
                            }
                        }
                    }
                }
                if(i!=0){
                    var tmpHead = "<select onchange='onCategorySelected(event)' id='parentCategory_"+(i) +"' level='"+(i)+"'><option>--</option>";
                    str = tmpHead + str + "</select>";
                    $('#selectContainer').append($(str));
                    categoryStack.push('#parentCategory_'+ i);
                }else{
                    $('#parentCategory_0').append($(str));
                }
            }

        }
    }
}
function shopCategoryReset(){
    var len = categoryStack.length;
    for(var j=0;j<len-1;j++){
        $("#selectContainer").children("select:last").remove();
        var  t = categoryStack.pop();
        levelIds.pop();
    }
    var selectedNode = $('#selectContainer').children(':last').children()[0];
    $(selectedNode).attr('selected',true);
}
function onCategorySelected(event){
     var len = categoryStack.length;
    var index=0;
    for(var i=0;i<len;i++){
        if("#"+event.currentTarget.id == categoryStack[i]){
            index =i+1;
            break;
        }
    }
    var len = categoryStack.length;
    for(var j=0;j<len - index;j++){
        $("#selectContainer").children("select:last").remove();
        var  t = categoryStack.pop();
        levelIds.pop();
    }
        currentSelectId = event.target.children[event.target.selectedIndex].id;
        currentSelectLevel = $(event.target).attr('level');
        currentSelectLevel = parseInt(currentSelectLevel);
        levelIds[currentSelectLevel] = currentSelectId;


    var tmpOptions =[];

    len = categorys.length;
    var str = "<select onchange='onCategorySelected(event)' id='parentCategory_"+(currentSelectLevel+1) +"' level='"+(currentSelectLevel+1)+"'><option>--</option>";

    for(i=0;i<len;i++){
        if(categorys[i].pid == currentSelectId && currentSelectId!=0){
            if((currentCategory && categorys[i].id != currentCategory.id) || !currentCategory){
                tmpOptions.push(categorys[i]);
                str+="<option id='"+ categorys[i].id+"'data="+categorys[i].pid+" level='"+categorys[i].level+"'>"+categorys[i].name+"</option>";
            }

        }
    }
    str +="</select>";
    if(currentSelectId == ""){
        currentSelectLevel--;
        currentSelectId =0;
        for(i=0;i<levelIds.length;i++){
            if(levelIds[i]==""){
                break;
            }
            currentSelectId = levelIds[i];
        }
    }
    console.log("当前选择ID",currentSelectId);
    console.log("当前选择级别",currentSelectLevel+1);
    console.log(levelIds);
    $('#categoryPid').val(currentSelectId);
    $('#categoryLevel').val((currentSelectLevel+1));

    if(tmpOptions.length>0){
        categoryStack.push('#parentCategory_'+(currentSelectLevel+1));
        $('#selectContainer').append($(str));
    }

}