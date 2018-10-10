<ul class="nav nav-sidebar"><!-- available classes "right-aligned" -->

    <li>
        <a href="{!! URL::route('product.index', $product->id) !!}">
            Overview
        </a>
    </li>



    @if(isset($productEdit))
    <li class="active">
        @else
        <li>
            @endif
            <a href="{{ URL::route('product.edit', $product->id) }}">
                <span class="visible-xs"><i class="entypo-gauge"></i></span>
                <span class="hidden-xs">Edit</span>
            </a>
        </li>

        @if(isset($productEditPrice))
        <li class="active">
            @else
            <li>
                @endif
                <a href="{{ URL::route('product.edit_price', $product->id) }}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Price</span>
                </a>
            </li> 


            @if(isset($productExtraFieldValue))
            <li class="active">
                @else
                <li>
                    @endif
                    <a href="{{ URL::route('product.product-extra-field-value.index', $product->id) }}">
                        <span class="visible-xs"><i class="entypo-gauge"></i></span>
                        <span class="hidden-xs">Extra fields</span>
                    </a>
                </li>

                @if(isset($productCombination))
                <li class="active">
                    @else
                    <li>
                        @endif
                        <a href="{{ URL::route('product-combination.index', $product->id) }}">
                            <span class="visible-xs"><i class="entypo-gauge"></i></span>
                            <span class="hidden-xs">Combinations</span>
                        </a>
                    </li>


<!--                     @if(isset($productAmountOption))
                    <li class="active">
                        @else
                        <li>
                            @endif
                            <a href="{{ URL::route('product.product-amount-option.index', $product->id) }}">
                                <span class="visible-xs"><i class="entypo-gauge"></i></span>
                                <span class="hidden-xs">Amount options</span>
                            </a>
                        </li> -->


              @if(isset($productAmountSeries))
                    <li class="active">
                        @else
                        <li>
                            @endif
                            <a href="{{ URL::route('product.product-amount-series.index', $product->id) }}">
                                <span class="visible-xs"><i class="entypo-gauge"></i></span>
                                <span class="hidden-xs">Amount series</span>
                            </a>
                        </li>




                            
                            @if(isset($productImage))
                            <li class="active">
                                @else
                                <li>
                                    @endif
                                    <a href="{{ URL::route('product.images.index', $product->id) }}">
                                        <span class="visible-xs"><i class="entypo-user"></i></span>
                                        <span class="hidden-xs">Images</span>
                                    </a>
                                </li>

                                @if(isset($productRelated))
                                <li class="active">
                                    @else
                                    <li>
                                        @endif
                                        <a href="{{ URL::route('product.related-product.index', $product->id) }}">
                                            <span class="visible-xs"><i class="entypo-user"></i></span>
                                            <span class="hidden-xs">Related products</span>
                                        </a>
                                    </li>

                                </ul>