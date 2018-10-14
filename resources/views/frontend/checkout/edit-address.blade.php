@extends('frontend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/cart/checkout">Checkout</a></li>
            <li ><a href="#" >Edit address</a></li>
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
						@if($type == 'bill')
						@notification()

						{!! Form::model($clientAddress, array('method' => 'post', 'route' => array('cart.edit.address', 'bill'), 'files' => true, 'class' => 'box login')) !!}

						@include('frontend.checkout._default_account_fields')     

						<a href="{!! URL::route('cart.checkout') !!}" class="btn btn-link">{!! trans('buttons.cancel') !!}</a>
						<button type="submit" class="btn btn-success">{!! trans('buttons.edit') !!}</button>

						{!! Form::close() !!}

						@else
				
						<ul>
							<li>{!! $user->clientBillAddress->firstname !!} {!! $user->clientBillAddress->lastname !!}</li>
							<li>{!! $user->clientBillAddress->street !!} {!! $user->clientBillAddress->housenumber !!} {!! $user->clientBillAddress->housenumber_suffix !!}</li>
							<li>{!! $user->clientBillAddress->zipcode !!} {!! $user->clientBillAddress->city !!}</li>

						</ul>


				
						@endif


                    </div>

                    <div class="col-lg-6 ">
                        
                        <h3>Delivery address</h3>

						@if($type == 'delivery')
						@notification()
						{!! Form::model($clientAddress, array('method' => 'post', 'route' => array('cart.edit.address', 'delivery'), 'files' => true, 'class' => 'box login')) !!}

						@include('frontend.checkout._default_account_fields')     

						<a href="{!! URL::route('cart.checkout') !!}" class="btn btn-link">{!! trans('buttons.cancel') !!}</a>
						<button type="submit" class="btn btn-success">{!! trans('buttons.edit') !!}</button>

						{!! Form::close() !!}

						@else
						<ul>
							<li>{!! $user->clientDeliveryAddress->firstname !!} {!! $user->clientDeliveryAddress->lastname !!}</li>
							<li>{!! $user->clientDeliveryAddress->street !!} {!! $user->clientDeliveryAddress->housenumber !!} {!! $user->clientDeliveryAddress->housenumber_suffix !!}</li>
							<li>{!! $user->clientDeliveryAddress->zipcode !!} {!! $user->clientDeliveryAddress->city !!}</li>


						</ul>

						@endif                    
                        
                    </div>

    			</div>

    		</div>
            

        </div>

    </div>
</div>
@stop