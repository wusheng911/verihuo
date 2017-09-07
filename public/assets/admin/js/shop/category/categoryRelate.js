/**
 * Created by wanghenshuai on 16/12/1.
 */

var otherCurrentSelectName;
var otherCurrentSelectLevel;
var otherCurrentSelectId;
var otherCurrentContainer;
var otherCurrentContainerName;
var categorys;
var containerNames ={};
var selectChangeFuns={};
var selectClasses = {};
function onReady(){
    categorys = App.categorys;
}
function registerSelectContainerName(selectContainerName,selectFun,selectClass){
    if(containerNames[selectContainerName]){
        alert('元素名称:'+selectContainerName+"已被注册");
        return ;
    }
    containerNames[selectContainerName] = true;
    selectChangeFuns[selectContainerName] = selectFun;
    selectClasses[selectContainerName] = selectClass;
    console.log('注册的样式为',selectClass)
    var option='';
    var len = categorys.length;
    var category;
    for(var i=0;i<len;i++){
        category = categorys[i];
        if(!category.level){
            option+="<option id='"+ category.id+"'data="+category.pid+" level='"+category.level+"'>"+category.name+"</option>"
        }
    }
    var select = $("#"+selectContainerName).find('select:first')
    select.append($(option));
    select.on('change',onOtherCategorySelected);
}
function onOtherCategorySelected(event){
    otherCurrentSelectName = event.currentTarget.id;
    otherCurrentSelectId = event.target.children[event.target.selectedIndex].id;
    console.log(otherCurrentSelectId);
    otherCurrentSelectLevel = $(event.target).attr('level');
    otherCurrentContainer = $('#'+otherCurrentSelectName).parent();

    otherCurrentContainerName = $(otherCurrentContainer).attr('id');
    console.log('容器的名字:',otherCurrentContainerName);
    var tmpPid=0;
    var totalSelecterCnt = $('#'+otherCurrentSelectName).parent().children("select").size();
    var boo =true;
    while (boo){
        if($('#'+otherCurrentSelectName).parent().children("select:last").attr('id') == otherCurrentSelectName){
            boo = false;
            break;
        }
        $('#'+otherCurrentSelectName).parent().children("select:last").remove();
    }

    if( !otherCurrentSelectId){

        var prveSelect = $('#'+otherCurrentSelectName).prev('select');
        if($(prveSelect).is('select')){
            var option = $(prveSelect).children()[prveSelect[0].selectedIndex];
            var prveId = $(option).attr('id');
            selectChangeFuns[otherCurrentContainerName](prveId);
            //sureSelectCategory(prveId);
        }else{
            selectChangeFuns[otherCurrentContainerName](0);
        }
        return ;
    }
    sureSelectCategory(otherCurrentSelectId);
    var len = categorys.length;
    var str ="";
    var tmpChilds = [];
    for(var i=0;i<categorys.length;i++){
        if(categorys[i].pid == otherCurrentSelectId){
            tmpChilds.push(categorys[i]);
        }
    }
    if(tmpChilds.length>0){
        var css = selectClasses[otherCurrentContainerName];
        var tmpClass = "";
        if(css){
            for(var i=0;i<css.length;i++){
                tmpClass += css[i];
                tmpClass +=" ";
            }
        }
        console.log('拿到的样式为:',css);
        console.log('生成的样式为:',tmpClass);

        str+="<select onchange='onOtherCategorySelected(event)' class="+tmpClass+" id='"+otherCurrentContainerName+"_select_"+(totalSelecterCnt) +"' level='"+(totalSelecterCnt)+"'><option>--</option>";
        for(i=0;i<tmpChilds.length;i++){
            str+="<option id='"+ tmpChilds[i].id+"'data="+tmpChilds[i].pid+" level='"+(totalSelecterCnt)+"'>"+tmpChilds[i].name+"</option>";
        }
        str +="</select>";
        otherCurrentContainer.append($(str));
    }


}