@extends('layouts.visit')



@section('script-file-bottom')
    <script>
     $(document).ready(function(){
         $('#check_code').click(function(){
             var code = $('input[name=code]').val();
             //check code;
         });
     });
    </script>
 	
@endsection

@section('content')
    <img src="{{ $qr_i }}" style="width:100%;">
    <span class="visit-qr-qa" >长按屏幕保存此邀请函</span>
@endsection
