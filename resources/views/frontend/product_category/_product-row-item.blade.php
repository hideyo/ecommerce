<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
    <a href="{!! URL::route('product.item', array('productCategorySlug' => $product->productCategory->slug, 'productId' => $product->id, 'productSlug' => $product->slug)) !!}" title="">
        <div class="product-col">

            @if(ProductHelper::getImage($product->id, array($product->attribute_id))) 
            <img src="/files/product/500x500/{!! $product->id !!}/{!! ProductHelper::getImage($product->id, array($product->attribute_id)) !!}" class="img-responsive" alt="{!! $product->title !!}">
            @else
            <img src="/images/product-thumb2.jpg" style="width:200px; height:200px;" />
            @endif

            <div class="text-block">
                <h5>{{ $product->title }}</h5>

                @if(ProductHelper::priceDetails($product, 'discount_tax_value'))


                @if($product->total_amount == 0)
                <p class="sold-out">sold out</p>
                @else
                <p>from <small class="discount-price">&euro; {!! ProductHelper::priceDetails($product, 'original_price_inc_tax_number_format') !!}</small> &euro; {!! ProductHelper::priceDetails($product, 'discount_price_inc_number_format') !!}</p>
                @endif

                @else
                @if($product->amount == 0)
                <p class="sold-out">sold out {!! ProductHelper::priceDetails($product, 'discount_price_inc_number_format') !!}</p>
                @else
                <p>from &euro; {!! ProductHelper::priceDetails($product, 'discount_price_inc_number_format') !!}</p>
                @endif
                @endif 
            </div>
        </div>
    </a>
</div>