@extends('layouts.admin')

@section('title',' 新建属性')



@push('stylesheets')
<style>
    table tr a.del{
        color: red;
        font-size: 12px;
    }
    table tr a.edit{
        color: blue;
        font-size: 18px;
    }
    table tr td{
        border-left: dashed 1px lightgray;
        border-right: dashed 1px lightgray;
    }
</style>
@endpush

@push('scripts')

@endpush

@section('content')

    @if(empty($friendlyLink))
    {!! Form::open(['url'=>action('Admin\Shop\FriendlyLinkController@store'),'method'=>'post']) !!}
    <div class="form-group">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">基础选项</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon">标题</span>
                        <input type="text" class="form-control" name="friendlylink[title]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">链接</span>
                        <input type="text" class="form-control" name="friendlylink[link]" placeholder="请输入" value="" >
                    </div>
                    <br>
                </div>
            </div>
            <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
        </div>
        </div>

    {!! Form::close() !!}
    @else
    {!! Form::open(['url'=>action('Admin\Shop\FriendlyLinkController@update',['id'=>$friendlyLink->id]),'method'=>'put']) !!}
            <div class="form-group">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">标题</span>
                                <input type="text" class="form-control" name="friendlylink[title]" placeholder="请输入" value="{{$friendlyLink->title}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">链接</span>
                                <input type="text" class="form-control" name="friendlylink[link]" placeholder="请输入" value="{{$friendlyLink->link}}" >
                            </div>
                        </div>
                    </div>

                    <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection