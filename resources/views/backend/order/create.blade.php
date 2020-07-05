@extends('backend._layouts.default')

@section('main')

<ol class="breadcrumb">
    <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
    <li><a href="{{ URL::route('order.index') }}">Order</a></li>  
    <li class="active">show</li>
</ol>
<div class="  pull-right">
    <a href="{{ URL::route('order.index') }}" class="btn btn-danger  pull-right">Back to overview</a>
</div>



<script type="text/javascript">
    
$(document).ready(function(){
  $('.icheck').each(function(){
    var self = $(this),
      label = self.next(),
      label_text = label.html();

    label.remove();
    self.iCheck({
      checkboxClass: 'icheckbox_line-grey',
      radioClass: 'iradio_line-grey',
      insert: '<div class="icheck_line-icon"></div>' + label_text
    });
  });
});

</script>


        <h2>Order <small>create</small></h2>
        <hr/>
        @include('flash::message')

        <div class="row">
            <div class="col-md-4">

                {!! Form::open(array('route' => array('order.add-product'), 'files' => true, 'class' => 'form-inline validate')) !!}
                <input type="hidden" name="_token" value="{!! Session::token() !!}">

                <h3>Add products</h3>
                <div class="form-group">
                    {!! Form::multiselect2('add-products[]', $ajaxProducts) !!}
                    {!! Form::submit('Add', array('class' => 'btn btn-default')) !!}
                </div>

                {!! Form::close() !!}
            </div>
            <div class="col-md-8">
                
                <h3>Products</h3>
                @if($products )
                <table class="table table-bordered">
                    @foreach($products as $product)
                    <tr>
                        <td>
                                                            <p>{!! $product['title'] !!}</p>
                                @if(isset($product['product_combination_title']))

  {!! Form::select('product_combination_id', array('0' => '-- selecteer --') + $product['combinations'], $product['id'], array("data-url" => "/order/change-product-combination/".$product['id'], "class" => "form-control custom-selectbox change-product-combination")) !!}
                                

                   
                                @endif
                        </td>
                        <td>
                            <input type="text" value="{!! $product['cart']['count'] !!}" class="update-amount update-amount-{!! $product['id'] !!}" data-url="/order/update-amount-product/{!! $product['id'] !!}"  /> 
                        </td>
                        <td>
                            @if($product['cart']['price_details']['discount_price_inc'])
                            &euro; {!! $product['cart']['price_details']['discount_price_inc'] !!}
                            @else
                            &euro; {!! $product['cart']['price_details']['orginal_price_inc_tax'] !!}
                            @endif
                        </td>

                        <td class="total_price"> 
                            &euro; <span class="total_price_inc_tax_{!! $product['id'] !!}">{!! $product['cart']['total_price_inc_tax'] !!}</span>
                        </td>
                        <td>
                            <a href="{{ URL::route('order.delete-product', $product['id']) }}" class="delete-product-{!! $product['id'] !!} btn btn-danger">verwijder</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @else
                <div class="alert alert-warning" role="alert">No products selected</div>
                @endif

                @if($products )
                <h3>Send &amp; payment method</h3>
                


                <table class="sending-method table table-bordered">

                    <tbody>
                        <tr>
                            <td class="title">
                                Sending method                            
                            </td>
                
                  

                            <td>
                                {!! Form::select('sending_method_id', array('0' => '-- selecteer --') + $sendingMethodsList->pluck('title', 'id'), $totals['sending_method_id'], array("data-url" => "/order/update-sending-method", "class" => "form-control custom-selectbox sending_method_id")) !!}
                            </td>

                            <td>
                                &euro; <span class="sending_method_cost_inc_tax">{!! $totals['sending_method_cost_inc_tax'] !!}</span>
                            </td>

                        </tr>
 
                        <tr>
                            <td class="title">
                                Payment method                            
                            </td>    
               
                            <td class="payment_method_row">                        
                                @if($paymentMethodsList)
                                 {!! Form::select('payment_method_id', array('0' => '-- selecteer --') + $paymentMethodsList, $totals['payment_method_id'], array("data-url" => "/order/update-payment-method", "class" => "form-control custom-selectbox payment_method_id")) !!}
                                @else
                                 {!! Form::select('payment_method_id', array('0' => '-- selecteer --'), null, array("disabled" => "disabled", "class" => "custom-selectbox payment_method_id form-control")) !!} 
                                @endif
                            </td>

                            <td>
                                &euro; <span class="payment_method_cost_inc_tax">{!! $totals['payment_method_cost_inc_tax'] !!}</span>
                            </td>


                        </tr>
                    <tbody>

                </table> 
                <h3>Totals</h3>
                <table class="totals table table-bordered">

                    <tbody>

                        <tr>
                            <td class="title">
                                Totaal excl. btw                            
                            </td>
              
                            <td>
                                &euro; <span class="total_ex_tax">{!! $totals['total_ex_tax'] !!}</span>
                           </td>

                            <td>                        
                       
                            </td>
                        </tr>


                        <tr>
                            <td class="title">
                                BTW                            
                            </td>
              
                            <td>
                                &euro; <span class="total_tax">{!! $totals['total_tax'] !!}</span>
                           </td>

                            <td>                        
                       
                            </td>
                        </tr>


                        <tr>
                            <td class="title">
                                Totaal incl. btw                            
                            </td>
              
                            <td>
                                &euro; <span class="total_inc_tax">{!! $totals['total_inc_tax'] !!}</span>
                           </td>

                            <td>                        
                       
                            </td>
                        </tr>
                    <tbody>

                </table> 


                @endif


            </div>


        </div>

        @if($products )  

        <hr/>
        <h3>Client</h3>


        {!! Form::open(array('route' => array('order.add-client'), 'files' => true, 'class' => 'form-inline  validate')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">


            <div class="form-group">
          
                {!! Form::select('add-client', array('' => 'select address') + $ajaxClientAdresses, $totals['client_id'], array('class' => 'form-control')) !!} 
                {!! Form::submit('Select client', array('class' => 'btn btn-default')) !!}
            
            </div>



        {!! Form::close() !!}

        @if($clientAddresses) 
        <div class="row">
                <div class="col-lg-6">
                    <h5>Billing address</h5>
                    @foreach($clientAddresses as $address)

                    <div class="form-group"> 

             
                        @if($totals['client_bill_address_id'] == $address->id)
                        <input type="radio" name="billing_address_id" value="{!! $address->id !!}" class="icheck update-address" data-url="/order/update-billing-address" checked>
                        @else
                        <input type="radio" name="billing_address_id" value="{!! $address->id !!}" class="icheck update-address" data-url="/order/update-billing-address">
                        @endif

                        <label>
                            @if($address->company)
                            {!! $address->company !!}<br/>
                            @endif

                            {!! $address->firstname !!} {!! $address->lastname !!}<br/> 
                            {!! $address->street !!} {!! $address->housenumber !!}<br/>
                            {!! $address->zipcode !!} {!! $address->city !!}<br/> 
                            {!! $address->country !!}
                        </label>
                    </div>
                    @endforeach

                </div>

                <div class="col-lg-6">
                 <h5>Delivery address</h5>
                    @foreach($clientAddresses as $address)

                    <div class="form-group"> 

           
                        @if($totals['client_delivery_address_id'] == $address->id)
                        <input type="radio" name="delivery_address_id" value="{!! $address->id !!}" class="icheck update-address" data-url="/order/update-delivery-address" checked>
                        @else
                        <input type="radio" name="delivery_address_id" value="{!! $address->id !!}" class="icheck update-address" data-url="/order/update-delivery-address">
                        @endif

                        <label>
                            @if($address->company)
                            {!! $address->company !!}<br/>
                            @endif

                            {!! $address->firstname !!} {!! $address->lastname !!}<br/> 
                            {!! $address->street !!} {!! $address->housenumber !!}<br/>
                            {!! $address->zipcode !!} {!! $address->city !!}<br/> 
                            {!! $address->country !!}
                        </label>
                    </div>
                    @endforeach
 
                </div>
        </div>
        @endif

        @endif


    <div class="row">
        <div class="col-lg-12">
                 <hr/>
            <h3>Order details</h3>
       
            {!! Form::open(array('route' => array('order.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}


            <div class="form-group">
                {!! Form::label('order_status_id', 'Order status', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('order_status_id', $orderStatuses, null, array('class' => 'form-control')) !!}
                </div>
            </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('order.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

            {!! Form::close() !!}
        </div>
    </div>


@stop