<ul>
    @foreach ($dependencies as $dependency)
        <li @if(!$dependency->deleted_at)class="text=muted"@endif>{{ $dependency->name }} | {{$dependency->type_description->description}} | added: {{$dependency->created_at}} 
            @if(!$dependency->deleted_at)
                | <a href="{{route('editDependency', $dependency->id)}}">edit</a> or <a href="{{route('deleteDependency', $dependency->id)}}">delete</a>
            @else 
                (deleted: {{$dependency->deleted_at}} <a href="{{route('restoreDependency', $dependency->id)}}">restore?</a>)
            @endif
        </li>
    @endforeach
</ul>