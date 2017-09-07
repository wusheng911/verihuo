var admin;  
$(document).ready(function(){
    admin = App.admin;
    $('#createVisitorCode').on('click',function(e){
        createSingleCode(); 
        // createUserCode("G")
    });
    $("#uploadFile").on('change',function(obj)
    {
         $('#uploadFilePath').val(this.value);
    });
})
function vaildVisitorCode()
{
    var code = $('#userCode').val();
    var codeId = $('#userId').val();
    var value = true;
   $.ajax({
        url:'/'+admin+'/visit/visitor/vaildvisitorcodeunique',
        method:'put',
        async:false,
        data:{'code':code,'id':codeId},
        success:function(e){
            console.log(e);
            if(e == 0){
                value = false;
            }else{
                value = true;
            }
        },    

        error:function(e){
            console.log(e);
            value = false;
        }
   })
   if(!value){
        alert('code已被使用');
   }
    return value; 
}
function createSingleCode()
{
    $.ajax({
        url: '/'+admin+'/visit/visitor/createsinglecode',
        method:'post',        
        data:null,
        success:function(e){
            $('#userCode').val(e);
        },
        error:function(e){
            console.log(e);
        },
    }); 
}
function createUserCode(value)
{
    var phoneCode = $('#userPhoneCode').val();
    if(phoneCode.length == 0)
    {
        alert('请输入电话号码');
        return;
    }
    phoneCode =value + phoneCode.substring(phoneCode.length-5);
    $('#userCode').val(phoneCode);
}
