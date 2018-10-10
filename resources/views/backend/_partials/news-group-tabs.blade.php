<ul class="nav nav-sidebar"><!-- available classes "right-aligned" -->

    <li>
        <a href="{!! URL::route('news-group.index', $newsGroup->id) !!}">
            Overview
        </a>
    </li>
    @if(isset($newsGroupEdit))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{{ URL::route('news-group.edit', $newsGroup->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Edit</span>
        </a>
    </li>

</ul>