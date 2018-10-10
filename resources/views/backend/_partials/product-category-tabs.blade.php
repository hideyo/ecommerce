<ul class="nav nav-sidebar"><!-- available classes "right-aligned" -->

    <li>
        <a href="{!! URL::route('product-category.index', $productCategory->id) !!}">
            Back to overview
        </a>
    </li>
    @if(isset($productCategoryEdit))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Edit</span>
        </a>
    </li>

    @if(isset($productCategoryImages))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{!! URL::route('product-category.images.index', $productCategory->id) !!}">
            <span class="visible-xs"><i class="entypo-user"></i></span>
            <span class="hidden-xs">Images</span>
        </a>
    </li>

    @if(isset($productCategoryHighlight))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{!! URL::route('product-category.edit.hightlight', $productCategory->id) !!}">
            <span class="visible-xs"><i class="entypo-user"></i></span>
            <span class="hidden-xs">Highlight</span>
        </a>
    </li>

</ul>