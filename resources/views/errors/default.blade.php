@extends('layouts.mobile')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
    {{'error—verihuo'}}
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => ''])
@endsection


@section('content')
<div class="row">
    <div class="small-12" style="height:100px;"></div>
</div>
<div class="row">
    <div class="small-12 text-center">
      <img src="/assets/img/error-default.png"/>
    </div>
</div>

@endsection
