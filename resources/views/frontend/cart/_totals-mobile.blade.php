<tr class="subtotal-block">
    <td colspan="3" class="text-right">
        Subtotal
    </td>                      
    <td class="price" class="text-right">
        &euro; {!! app('cart')->getSubTotalWithTax() !!}
    </td>  
    <td>
    </td>                      
</tr>

<tr class="sending-method">    

    @if($sendingMethodsList->count() AND app('cart')->getConditionsByType('sending_method_country_price')->count())
    <td colspan="2" class="select">
        @if(isset(app('cart')->getConditionsByType('sending_method_country_price')->first()->getAttributes()['data']['countries']))
        <label>{!! trans('titles.sending-to') !!}</label>

        <div class="sending_block"> 
            {!! Form::select(
            'sending_method_country_price_id', 
            app('cart')->getConditionsByType('sending_method_country_price')->first()->getAttributes()['data']['country_list']->toArray(), 
            app('cart')->getConditionsByType('sending_method_country_price')->first()->getAttributes()['data']['sending_method_country_price_id'], 
            array("data-url" => '/cart/update-sending-method-country-price', 
            "class" => "form-control custom-selectbox selectpicker sending_method_country_price_id")) 
            !!}

        </div>
        @else
        <label>{!! trans('titles.sending-way') !!}</label>     
        @endif
        
    </td>  
    @else
    <td colspan="2" class="select">
        <label>{!! trans('titles.sending-way') !!}</label>
        <div class="sending_block">
  
            {!! Form::select('sending_method_id', $sendingMethodsList->pluck('title', 'id')->toArray(), app('cart')->getConditionsByType('sending_method')->first()->getAttributes()['data']['id'], array("data-url" => '/cart/update-sending-method', "class" => "form-control  sending_method_id")) !!}

        </div>
    </td>
    @endif
    <td colspan="1" class="text-right">
        {!! trans('titles.sending-cost') !!}
    </td>
    <td colspan="1" class="total_price">
        @if(app('cart')->getConditionsByType('sending_cost')->count())
            &euro; {!! app('cart')->getConditionsByType('sending_cost')->first()->getValue() !!}
        @else
            &euro; 0.00
        @endif
    </td>
</tr>

<tr class="tr-total">
    <td class="title text-right" colspan="3">

        {!! trans('titles.total') !!}
   
    </td>
    <td class="price total_price">
        &euro; <span class="total_inc_tax">{!! app('cart')->getTotalWithTax() !!}</span><br/>
        <small>{!! trans('titles.tax-included') !!}</small>   
   </td>
    <td>                        
    </td>
</tr>

<tr class="next-button">

    <td colspan="4" class="text-right">
        
        {!! Form::open(array('route' => array('cart.checkout'), 'class' => 'form-horizontal form-groups-bordered validate')) !!}
        <input type="hidden" name="_token" value="{!! session()->token() !!}">
        {!! Form::submit(trans('buttons.order-continue'), array('class' => 'button button-black')) !!}       
        {!! Form::close() !!}
    </td>
</tr>