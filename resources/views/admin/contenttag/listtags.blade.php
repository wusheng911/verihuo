@foreach($tags as $tag)
    <li> <label >
        <input class="" name="content[tags][]" type="checkbox"
               @if($tag->checked)
               checked="checked"
               @endif
               value="{{ $tag->id }}">
        {{$tag->name}}
    </label> </li>
@endforeach
