@extends('frontend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="#">Checkout</a></li>
        </ol>
    </div>
</div>


<div class="main-cart main-cart-login">
    <div class="row">

        <div class="col-lg-4  ">
            <div class="summary-cart-reload" data-url="/cart/summary-reload" >
                @include('frontend.checkout._summary')
            </div>
        </div>      
    
        <div class="col-lg-8 ">
            <div class="confirm-page">   
                @notification()
                <div class="row">
                    <div class="col-lg-6 ">
                
                        <h3>Bill address</h3>
                        <div class="invoice-address-container">
                            <ul>
                                <li>{!! $noAccountUser['firstname'] !!} {!! $noAccountUser['lastname']  !!}</li>
                                <li>{!! $noAccountUser['email']  !!}</li>
                                <li>&nbsp;</li>
                                <li>{!! $noAccountUser['street']  !!} {!! $noAccountUser['housenumber']  !!} {!! $noAccountUser['housenumber_suffix']  !!}</li>
                                <li>{!! $noAccountUser['zipcode']  !!} {!! $noAccountUser['city']  !!}</li>
                                <li>{!! $noAccountUser['country'] !!}</li>
                            </ul>
                            <a href="{!! URL::route('cart.edit.address', 'bill') !!}" class="btn btn-success">edit</a>

                        </div>

                    </div>

                    <div class="col-lg-6 ">
                        
                        <h3>Delivery address</h3>
                        <div class="delivery-address-container">
                            <ul>
                                <li>{!! $noAccountUser['delivery']['firstname']  !!} {!! $noAccountUser['delivery']['lastname']  !!}</li>
                                <li>{!! $noAccountUser['delivery']['email']  !!}</li>
                                <li>&nbsp;</li>
                                
                                <li>{!! $noAccountUser['delivery']['street']  !!} {!! $noAccountUser['delivery']['housenumber']  !!} {!! $noAccountUser['delivery']['housenumber_suffix']  !!}</li>
                                <li>{!! $noAccountUser['delivery']['zipcode']  !!} {!! $noAccountUser['delivery']['city']  !!}</li>
                                <li>{!! $noAccountUser['delivery']['country'] !!}</li>


                            </ul>
                            <a href="{!! URL::route('cart.edit.address', 'delivery') !!}" class="btn btn-success">edit</a>

                        </div>                      
                        
                    </div>
                    
              </div>
              <hr/>
              <div class="row">

                    <div class="col-lg-12">
                        <div class="paymentway">
                            <h3>Payment way</h3>            
                    

                            <div class="form-group payment_method_row select">
                                <label>Select a payment way:</label>
                                @if($paymentMethodsList)

                                    {!! Form::select('payment_method_id', $paymentMethodsList, app('cart')->getConditionsByType('payment_method')->first()->getAttributes()['data']['id'], array("data-url" => '/cart/update-payment-method', "class" => "form-control  payment_method_id")) !!}
                                @else
                                {!! Form::select('payment_method_id', array('0' => '-- selecteer --'), null, array("disabled" => "disabled", "class" => "selectpicker custom-selectbox payment_method_id")) !!} 
                                @endif
                            </div>               
             
                        </div>


                    </div>


              </div>
              <hr/>
              <div class="row">

                    {!! Form::open(array('route' => array('cart.complete'), 'class' => 'form-group')) !!}
                       
                    <div class="col-lg-12">               
                        <h3>Comments</h3>
                        <p>place a comment</p>
                         {!! Form::textarea('comments', null, array('rows' => 5, 'class' => 'form-control')) !!}  
                    </div>

                    <div class="col-lg-12 text-right"> 
                         <p><a href="{!! URL::route('text', 'general-terms-conditions') !!}" target="_blank">general conditions</a>.</p>
                                                               
                    </div>

                    <div class="col-lg-12 text-right">
                      
                        <div class="button-group">                     

                           <input type="hidden" name="_token" value="{!! Session::token() !!}">
                            @if(app('cart')->getConditionsByType('payment_method')->first()->getAttributes()['data']['payment_external']) 
                            {!! Form::submit('Confirm & Pay', array('class' => 'btn btn-success')) !!}  
                            @else 
                            {!! Form::submit('Confirm', array('class' => 'btn btn-success')) !!} 
                            @endif          
                            
                        </div>
                    
                    </div> 

                    {!! Form::close() !!}

                </div>
            </div>

        </div>

    </div>

</div>
@stop