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
    <br> <br> <br> <br>
    <div class="row" >
        <div class="small-4 columns text-right" >
            <h2>姓名：</h2>
        </div>
        <div class="small-7 end columns" >
            <h2>{{ $visitor->name }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="small-4 columns text-right" >
            <h2>电话：</h2>
        </div>
        <div class="small-7 end columns" >
            <h2>{{ $visitor->phone }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="small-4 columns text-right" >
            <h2>邀请码：</h2>
        </div>
        <div class="small-7 end columns" >
            <h2>{{ $visitor->code }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="small-4 columns text-right" >
            <h2>VIP？：</h2>
        </div>
        <div class="small-7 end columns" >
            <h2>{{ $visitor->level == 1 ? 'VIP' : 'GUEST' }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="small-4 columns text-right" >
            <h2>状态：</h2>
        </div>
        <div class="small-3 end columns" >
            @if($visitor->is_entered == 1)
            <h2 style="background-color:red; color: white;"> 已入场 </h2>
            @else
            <h2 style="background-color:green; color: white;"> 未入场 </h2>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="small-4 columns text-right" >
            <h2>备注：</h2>
        </div>
        <div class="small-7 end columns" >
            <h2>{{ $visitor->intro }}</h2>
        </div>
    </div>
        <br>
    <div class="row">
        <div class="small-8 small-centered columns" >
            @if($visitor->is_entered == 1)
            <span>(入场时间： {{ $visitor->entered_at }})</span>
            @else
            <span>(入场时间： 无)</span>
            @endif
        </div>
    </div>

@endsection
