<table class="m-table" style="margin-top:@if($hasImg == 'true') 15px @else 7px @endif">
    <tr>
        @foreach ($node["localAttributes"]['keywordlist']['values'][1]['value'] as $keyword)
            <td class="m-table-td"><a href="{{ action('ViewController@tagSearchForPc',null) }}/{{isset($keyword)?$keyword:''}}">{{isset($keyword)?$keyword:''}}</a></td>
            <td class="m-table-td-1"></td>
        @endforeach
    </tr>
</table>


