@extends('layouts.admin')
@section('title', '加载文件')

@push('scripts')
    <script src="/assets/admin/js/visitor/visitor_code.js"></script>
@endpush

@section('content')
    {!! Form::open(['url'=>action('Admin\Visit\VisitorController@uploadFile'),'method'=>'post','enctype'=>'multipart/form-data']) !!}
        <label for="file">文件名：</label>
        <input id="uploadFile" type="file" name="file"><br>
        <input type="submit" name="submit" value="提交">
    {!! Form::close() !!}
<script>
</script>
@endsection
