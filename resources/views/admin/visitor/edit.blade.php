@extends('layouts.admin')
@section('title', '编辑嘉宾信息')

@push('scripts')
    <script src="/assets/admin/js/visitor/visitor_code.js"></script>
    <script>
    </script>
@endpush

    @section('content')
        
            {!! Form::open(['url' => action('Admin\Visit\VisitorController@update', ['id'=>$visitor->id]), 'method' => 'put','onsubmit'=>"return vaildVisitorCode()" ]) !!}
            <div class="form-group">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">游客信息</h3>
                        </div>
                        <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-1 control-label">名称</label>
                            <input class="col-lg-11 form-control-static" name="visitor[name]" type="text" value="{{$visitor->name}}" placeholder="请输入姓名" aria-describedby="basic-addon1">
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">电话</label>
                            <input id="userPhoneCode" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();" class="col-lg-11 form-control-static" name="visitor[phone]" type="text" value="{{$visitor->phone}}" placeholder="请输入电话" aria-describedby="basic-addon1">
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">介绍</label>
                            <input class="col-lg-11 form-control-static" name="visitor[intro]" type="text" value="{{$visitor->intro}}" placeholder="请输入介绍" aria-describedby="basic-addon1">
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">code</label>
                            <input id="userCode" class="col-lg-11 form-control-static" name="visitor[code]"  type="text" value="{{$visitor->code}}" placeholder="" aria-describedby="basic-addon1">
                            <input id="userId" style="display:none;" readonly type="text" value="{{$visitor->id}}">
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                                        <input  style="width:15px;" class="col-lg-3 form-control-static" name="visitor[level]" type="checkbox"
                                                value="" {{$visitor->level?'checked':''}}>
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">是否为VIP</label>
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">     </label>
                            <input id="createVisitorCode" class="btn btn-primary btn-lg"  type="button" value="生成游客 CODE"  aria-describedby="basic-addon1" >
                        </div>
                        </div>
                    </div> 
                    <input type="submit" class="btn btn-primary btn-lg" value="提交"></input>
                </div>
            </div>
            
            
            {!! Form::close() !!}
            
            
    @endsection
