/**
 * Created by wanghenshuai on 16/12/20.
 */
var ads;
$(document).ready(function(){
    ads = App.ads;
    console.log(App.ads);
    $('#sureCreateAdposition').on('click',sureCreateNewAdposition)
});
function sureCreateNewAdposition(event){
    var adcode = $('#adcode').val();
    adcode = trim(adcode);
    var len = ads.length;
    for(var i=0;i<len;i++){
        if(adcode == ads[i].adposition_code){
            event.preventDefault();
            alert('该广告码已被使用');
            return false ;
        }
    }
}
function trim(str){ //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}