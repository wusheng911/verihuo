@extends('layouts.admin')
@section('title', '预览 '.$content->title.' 资讯文章')

    @push('stylesheets')
    <style>
    </style>
    @endpush

    @push('scripts')
    @endpush

    @section('content')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">SEO 相关</h3>
            </div>
            <div class="panel-body">
                <p>{{$content->seo_title}}</p>
                <p>{{$content->seo_keywords}}</p>
                <p>{{$content->seo_description}}</p>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-success">预览中</span>
                <button type="button" onClick="window.location.href = '/admin/content/{{$content->id}}/edit';" class="btn btn-success">去编辑</button>

                <button type="button" onClick="window.location.href='/admin/content/create';" class="btn btn-info">去新建</button>
            </div>
            <div class="panel-body">
                <div class="input-group">
                    <h1>{{$content->title}}</h1>
                    <h2>{{$content->subtitle}}</h2>
                    <h3>{{$content->description}}</h3>
                    <p>{!! html_entity_decode($content->content) !!}</p>
                </div>
            </div>
        </div>

    @endsection
