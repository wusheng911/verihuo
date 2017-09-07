@include('mobile.elements.blockHeader', 
    [
        'blockHeader' => $subcategory['title'], 
        'blockMore' => '/news/' . $subcategory['id']
    ]
)
{{-- Banner --}}
@include('nodes.index', 
    [
        'position' => 'Mobile|News|'. $subcategory['pid'] . '-' . $subcategory['id'] .'|A1'
    ]) 

@if(array_search('Mobile|News|'. $subcategory['pid'] . '-' . $subcategory['id'] .'|A1', array_column($adpositions, 'adposition_code')) !== false)
    <hr class="chm-divider-transparent-4"/>
@endif

{{-- Article1 --}}
@include('nodes.index', 
    [
        'position' => 'Mobile|News|'. $subcategory['pid'] . '-' . $subcategory['id'] .'|A2'
    ]) 

<hr class="chm-divider-transparent-4"/>

{{-- Article2 --}}
@include('nodes.index', 
    [
        'position' => 'Mobile|News|'. $subcategory['pid'] . '-' . $subcategory['id'] .'|A3'
    ])  

<hr class="chm-divider-transparent-4"/>
