<div class="order-summary jumbotron">

    <h5>Summary</h5>
    @foreach (app('cart')->getContent()->sortBy('id') as $product)
    <div class="row">     
       
            <div class="col-lg-8">            
  
                <a href="/{{ $product['attributes']['product_category_slug'] }}/{{ $product['id'] }}/{{ $product['attributes']['slug'] }}" title="terug naar product">

                    {!! $product['quantity'] !!}x {!! $product['attributes']['title'] !!}
            
                    @if(isset($product['attributes']['product_combination_title']))
                    <ul>

                    @if(isset($product['attributes']['product_combination_title']))                                        
                    @foreach($product['attributes']['product_combination_title'] as $title => $value)
                    <li>{!! trans('titles.'.$title) !!}: {!! $value !!}</li>
                    @endforeach                                        
                    @endif
                    </ul>
                    @endif        
                </a>
        
            </div>
            <div  class="col-lg-4 text-right">
                &euro; <span class="total_price_inc_tax_{!! $product['id'] !!}">{!! $product->getOriginalPriceWithTaxSum() !!}</span>
            </div>  

    </div>

    @endforeach  
    <div class="totals">
        <div class="row">  
            <div class="col-lg-8">
                Subtotal
            </div>

            <div class="col-lg-4 text-right">
                       
                &euro; <span class="sub_total_inc_tax">{!! app('cart')->getSubTotalWithTax() !!}</span>
           
            </div>  
        </div>   



        <div class="row">  
            <div class="col-lg-8">
               {!! trans('titles.sending-cost') !!} ({!! app('cart')->getConditionsByType('sending_method')->first()->getAttributes()['data']['title'] !!})
            </div>
            <div class="col-lg-4 text-right">

                @if(app('cart')->getConditionsByType('sending_cost')->count()) 

              
                    &euro; <span class="sending_method_cost_inc_tax">{!! app('cart')->getConditionsByType('sending_cost')->first()->getValue() !!}</span>
            

                @endif

            </div>  
        </div>  



        <div class="row">  
            <div class="col-lg-8 ">
                @if(app('cart')->getVoucher())
                <strong>Total</strong>
                @else
                <strong>To pay</strong>
                @endif
            </div>

            <div class="col-lg-4  text-right">
                <strong>

         
                    &euro; <span class="payment_method_cost_inc_tax">{!! app('cart')->getTotalWithTax() !!}</span>
                  
            
                </strong>
            </div>  
        </div>

        @if(app('cart')->getVoucher())
        <div class="row">  
            <div class="col-lg-8  ">
                {!! trans('titles.gift-voucher-total') !!}
            </div>
            <div class="col-lg-4 text-right">
                - &euro; <span class="total_inc_tax">{!! app('cart')->getVoucher()['used_value_with_tax'] !!}</span><br/>
            </div>  
        </div>

        <div class="row">  
            <div class="col-lg-8 columns">
                <strong>{!! trans('titles.to-pay') !!}</strong>
            </div>

            <div class="col-lg-4 text-right">
                <strong>         
                    &euro; <span class="payment_method_cost_inc_tax">{!! app('cart')->getToPayWithTax() !!}</span>            
                </strong>
            </div>  
        </div>

        @endif

        <div class="row">
              <div class="col-lg-12 text-right">
      
                <a href="{!! URL::route('cart.index') !!} " class="btn btn-link">edit cart</a>
            </div>
        </div>
    </div>
</div>