<ul class="nav nav-sidebar"><!-- available classes "right-aligned" -->

    <li>
        <a href="{!! URL::route('content.index', $content->id) !!}">
            Overview
        </a>
    </li>
    @if(isset($contentEdit))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{{ URL::route('content.edit', $content->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Edit</span>
        </a>
    </li>
 
    @if(isset($contentImages))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{!! URL::route('content.images.index', $content->id) !!}">
            <span class="visible-xs"><i class="entypo-user"></i></span>
            <span class="hidden-xs">Images</span>
        </a>
    </li>




</ul>