@if(isset($nodes[$position]['template']))
    @include(strtolower($nodes[$position]['template']), ['node' => $nodes[$position]])
@endif





