@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
    {{'a'}}
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '用户注册条款'])
@endsection


@section('content')
<style>
  p {
      margin-bottom:0;
      font-size:0.8rem;
  }
</style>

@endsection
