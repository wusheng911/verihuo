$(onReady);

var mobileNumber;
function onReady(){
    mobileNumber = $("input[name='mobile_number']");
    // $(mobileNumber).on('oninput',onInputChange);
    $(mobileNumber).on('change',onInputChange);
    $("input[name='mobile_number']").keyup(onInputChange);
    $('#password-icon-true').on('click',onDisplayPassword);
    $('#password-icon-false').on('click',onHiddenPassword);
}
function onDisplayPassword(e){
    console.log('123')
    var psInput = $('#password-input');
    $('#password-icon-true').addClass('password-icon-hidden');
    $('#password-icon-false').removeClass('password-icon-hidden');
    psInput.attr('type','text');
}
function onHiddenPassword(e){
    var psInput = $('#password-input');
    $('#password-icon-false').addClass('password-icon-hidden');
    $('#password-icon-true').removeClass('password-icon-hidden');
    psInput.attr('type','password');
}
function onInputChange(e){
    mobileNumber.val(mobileNumber.val().replace(/[^0-9]/g,''));
    var num = $.trim(mobileNumber.val());
    var tmpAlert = $('#registerAlert');

    if(num.length == 11){
        $.ajax({
            url:'/customer/isregister',
            data:{'phone':num},
            dataType:'json',
            type:'get',
            async:true,
            success:function(e){
                if(e == true){
                    tmpAlert.removeClass('phone-register-alert-none');
                }else{
                    tmpAlert.addClass('phone-register-alert-none');
                } 
            },
            error:function(e){

            },
        });
    }else{
        tmpAlert.addClass('phone-register-alert-none');
    }
}

