@extends('layouts.visit')

@section('script-file-bottom')
    <script>
     $(document).ready(function(){
         $('#check_code').click(function(){
             var code = $('input[name=code]').val();
             var url = "{{ action('Visit\InvitationController@check_code') }}";
             var p = "code=" + code;
             $.post(url, p).success(function(data){
                 if(data.status == 1) {
                     window.location.href = data.url;
                 }else{
                     $('p.error').show();
                     $('p.error').fadeOut(5000);
                     //设置不存在提示!;
                 }
             });
         });
     });
    </script>
 	
@endsection

@section('content')

    <div class="row visit-invitation">
        <div class="small-12" >
            <div class="row code-input" >
                <div class="small-9 small-centered columns" >
                    <input type="text" class="code" name="code" value="" placeholder="请输入您的邀请码">
                </div>
            </div>
            <div class="row" style="margin-top:30px;">
                <div class="small-7 small-centered columns text-center" >
                    <button id="check_code" class="small"></button>
                </div>
            </div>
            <p class="error" style="display:none;">邀请码有误请确认后重新输入</p>
        </div>
    </div>

@endsection
