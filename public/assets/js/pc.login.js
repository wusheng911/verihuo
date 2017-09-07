$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});
$(document).ready(function(){
  $('#login2').click(function(){
    $.ajax({
      method: "POST",
      url: '/customer/login',
      data: {
	email: $('input[name=email]').val(),
	password: $('input[name=password]').val()
      },
      dataType: "json",
      success: function(data){
	console.log(data);
	if(data.status == 1){
	  window.location.href = window.location.href;
	}else{
	  $('#err').html(data.err);
	}
      }});
  });
});
