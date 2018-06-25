<tr class="product-row">

    <td class="image show-for-medium">   
        <a href="/{{ $product['attributes']['product_category_slug'] }}/{{ $product['id'] }}/{{ $product['attributes']['slug'] }}" title="terug naar product">   
            @if(ProductHelper::getImage($product['attributes']['id'], array())) 
            <img src="/files/product/100x100/{!! $product['attributes']['id'] !!}/{!! ProductHelper::getImage($product['attributes']['id'], array()) !!}" alt="">
            @else
            <img src="/images/default-product-thumb.png" style="width:100px;"  />
            @endif                                       
        </a>
    </td>

    <td class="title">
        <a href="/{{ $product['attributes']['product_category_slug'] }}/{{ $product['id'] }}/{{ $product['attributes']['slug'] }}" title="terug naar product"> 
            <p>{!! $product['attributes']['title'] !!}</p>
            <ul>
                <li>Artnr: {!! $product['attributes']['reference_code'] !!}</li>
                @if(isset($product['attributes']['product_combination_title']))                                        
                @foreach($product['attributes']['product_combination_title'] as $title => $value)
                <li>{!! trans('titles.'.$title) !!}: {!! $value !!}</li>
                @endforeach                                        
                @endif
            </ul>
        </a>
    </td>

    <td class="price show-for-medium">
        &euro; {!! $product->getOriginalPriceWithTaxAndConditions() !!}
        @if($product->hasConditions())
        @endif
    </td>

    <td class="amount">   
        @if($product['attributes']['product_amount_series'])  
        {!! Form::select('amount', $product['product_amount_series_range'], $product['cart']['count'], array('class' => 'form-control update-amount', 'data-width' => 'auto', 'data-url' => '/cart/update-amount-product/'.$product['id'])) !!}
        @else  
        {!! Form::select('amount', array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7 ,8 => 8, 9 => 9 ,10 => 10, 11 => 11, 12 => 12), $product['quantity'], array('class' => 'form-control update-amount amount-input', 'data-width' => 'auto', 'data-url' => '/cart/update-amount-product/'.$product['id'])) !!}
        @endif
    </td>

    <td class="total_price"> 
        &euro; <span class="total_price_inc_tax_{!! $product['id'] !!}">{!! $product->getOriginalPriceWithTaxSum() !!}</span>
    </td>
    
    <td class="delete">             
        <a href="/cart/delete-product/{!! $product['id'] !!}" class="delete-product" title="verwijder een product"><span class="glyphicon glyphicon-remove"></span></a>
    </td>
</tr>