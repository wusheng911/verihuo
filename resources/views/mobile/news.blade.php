@extends('layouts.mobile', ['subnav' => true])

@section('meta')
@endsection


@if(!empty($seoTitle))
    @section('title', $seoTitle)
@else
    @section('title', 'veriHuo- College application essays and advice')
@endif

@if(!empty($seoDescription))
    @section('description', $seoDescription)
@else
    @section('description', 'College application essays and advice')
@endif

@if(!empty($seoKeywords))
    @section('keywords', $seoKeywords)
@else
    @section('keywords', 'College application essays and advice')
@endif

@section('navigatorTop')
    @if(isset($category['code']))
        @include('mobile.elements.navigatorTop', ['active_nav' => $category['code']])
    @endif
@endsection

@section('content')
    @if (isset($category['child']) && is_array($category['child']))
        @foreach ($category['child'] as $subcategory)
            @include('mobile.elements.articleSubcategory', ['subcategory' => $subcategory])
        @endforeach
    @endif
@endsection

@section('navigatorBottom')
    {{-- @include('mobile.elements.navigatorBottom') --}}
@endsection


