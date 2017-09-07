@extends('layouts.admin')

@section('title','学校管理')


@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>




@endpush

@section('content')

    <form name="cpform" method="post" action=""  enctype="multipart/form-data">
    <div class="form-group">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
		    <h3 class="panel-title">学校批量上传</h3>
                     <a href="/uploadifive/gs.xlsx" target="_blank" download="gs.xlsx" >下载excel上传样板</a>
                </div>
                <div class="panel-body">


                    <div class="form-group">
                        <label class="col-lg-2 control-label">excel批量上传</label>
                        <input type="file" name="file" />
                        <input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                    </div>
                    <br>


                    <br>
                </div>
            </div>
            <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
        </div>
    </div>

    </form>

@endsection
