<ul class="nav nav-sidebar"><!-- available classes "right-aligned" -->

    <li>
        <a href="{!! URL::route('brand.index', $brand->id) !!}">
            Overview
        </a>
    </li>
    @if(isset($brandEdit))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{{ URL::route('brand.edit', $brand->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Edit</span>
        </a>
    </li>

   
    @if(isset($brandImages))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{!! URL::route('brand.images.index', $brand->id) !!}">
            <span class="visible-xs"><i class="entypo-user"></i></span>
            <span class="hidden-xs">Images</span>
        </a>
    </li>




</ul>