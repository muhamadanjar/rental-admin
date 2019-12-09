@if ((count($category->children) > 0) OR ($category->parent_id > 0))

    <li class="nav-item nav-item-submenu"><a class="nav-link" href="{{ url($category->url) }}"><i class="{{$category->icon == '' ? 'icon-list-unordered':$category->icon }}"></i> <span>{{ $category->title }}</span> <i class="fa fa-chevron-right"></i></a>

@else

    <li class="nav-item"><a class="nav-link" href="{{ url($category->url) }}"><i class="{{$category->icon == '' ? 'icon-list-unordered':$category->icon }}"></i> <span>{{ $category->title }}</span></a>

@endif

    @if (count($category->children) > 0)

        <ul class="nav nav-group-sub">

        @foreach($category->children as $category)

            @include('layouts.elements.menu', $category)

        @endforeach

        </ul>

    @endif

</li>