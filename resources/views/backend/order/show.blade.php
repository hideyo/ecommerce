@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('order.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Show</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('order.index') }}">Orders</a></li>  
            <li class="active">show</li>
        </ol>

        <a href="{{ URL::route('order.index') }}" class="btn btn-danger btn-icon icon-left pull-right">Back to overview<i class="entypo-back"></i></a>

        <h2>Order <small>show</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <div class="row rowTopPadding">
            <div class="col-md-12">

                <div class="col-md-4">
                    
                    <table class="table"> 
                        <tr>
                            <td>Id</td>
                            <td scope="row">{{ $order->generated_custom_order_id }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td scope="row">
                                 {!! Form::open(array('route' => array('order.update-status', $order->id), 'files' => true, 'class' => 'form-inline validate')) !!}

                                <div class="form-group">
                                    @if($order->orderStatus)
                                    {!! Form::select('order_status_id', $orderStatuses->toArray(), $order->orderStatus->id, array('class' => 'form-control')) !!}
                                    @else
                                    {!! Form::select('order_status_id', array('' => '--Select--') + $orderStatuses->toArray(), null, array('class' => 'form-control')) !!}
                                    @endif

                                    {!! Form::submit('Update status', array('class' => 'btn btn-default')) !!}
                                </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>

                        <tr>
                            <td>Client email</td>
                            <td scope="row">
                                @if($order->client)
                                {{ $order->client->email }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Created at</td>
                            <td scope="row">{{ date('d F H:i', strtotime($order->created_at)) }}</td>
                        </tr> 

                       <tr>
                            <td>Discount</td>
                            <td scope="row">&euro; {{ $order->getTotalDiscountNumberFormat() }}</td>
                        </tr> 

                        @if($order->coupon_code)
                       <tr>
                            <td>Coupon</td>
                            <td scope="row">
                                code: {{ $order->coupon_code }}<br/>
                                title: {!! $order->coupon_title !!}
                                @if($order->coupon->couponGroup)
                                <br/>
                                
                                group: {!! $order->coupon->couponGroup->title !!}
                                @endif

                            </td>
                        </tr> 
                        @endif                        

                        <tr>
                            <td>Price with tax</td>
                            <td scope="row">&euro; {{ $order->getPriceWithTaxNumberFormat() }}</td>
                        </tr>
                        <tr>
                            <td>Price without tax</td>
                            <td scope="row">&euro; {{ $order->getPriceWithoutTaxNumberFormat() }}</td>
                        </tr> 
                        <tr>
                            <td>Tax</td>
                            <td scope="row">&euro; {{  $order->taxTotal() }}<br/>
                                @foreach ($order->taxDetails() as $key => $val)
                                <small>{{ round($key) }}%: &euro; {{ round($val,2) }}</small><br/>
                                @endforeach
                            </td>
                        </tr> 
                        <tr>
                            <td>Mollie payment id</td>
                            <td>{{ $order->mollie_payment_id }}</td>
                        </tr>
                        <tr>
                            <td>Payment log</td>
                            <td scope="row">
                                @if($order->orderPaymentLog)
                                <ul>
                                @foreach($order->orderPaymentLog as $log)
                                <?php $arrayLog = unserialize($log->log); ?>
                                <li>id: {!! $arrayLog['id'] !!}</li>
                                <li>description: {!! $arrayLog['description'] !!}</li>
                                <li>amount: {!! $arrayLog['amount'] !!}</li>
                                <li>status: {!! $arrayLog['status'] !!}</li>
                                <li>------------</li>
                                @endforeach
                            </ul>
                                @else
                                none
                                @endif
                            </td>
                        </tr>


                    </table>                   
                
                    <div class="row rowTopPadding">
                        @if($order->orderDeliveryAddress)
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Delivery address</div>
                                <div class="panel-body">                      
                                    <table class="table table-bordered">
                   

                                        <tr>
                                            <td>Name</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->firstname }} {{ $order->orderDeliveryAddress->lastname }}</td>
                                        </tr>                                                   
                                        <tr>
                                            <td>Street</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->street }} {{ $order->orderDeliveryAddress->housenumber }} {{ $order->orderDeliveryAddress->housenumber_suffix }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Zipcode</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->zipcode }} {{ $order->orderDeliveryAddress->city }}</td>
                                        </tr>  
                                        <tr>
                                            <td>Country</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->country }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Phone</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->phone }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Company</td>
                                            <td scope="row">{{ $order->orderDeliveryAddress->company }}</td>
                                        </tr> 


                                    </table>
                                </div>
                            </div>
                        </div>

                        @endif

                        @if($order->orderBillAddress)
                        <div class="col-md-12">

                            <div class="panel panel-default">
                                <div class="panel-heading">Bill address</div>
                                <div class="panel-body">                      
                                    <table class="table table-bordered">  
                       
                                        <tr>
                                            <td>Name</td>
                                            <td scope="row">{{ $order->orderBillAddress->firstname }} {{ $order->orderBillAddress->lastname }}</td>
                                        </tr>                                                  
                                        <tr>
                                            <td>Street</td>
                                            <td scope="row">{{ $order->orderBillAddress->street }} {{ $order->orderBillAddress->housenumber }} {{ $order->orderDeliveryAddress->housenumber_suffix }}</td>
                                        </tr>
                                        <tr>
                                            <td>Zipcode</td>
                                            <td scope="row">{{ $order->orderBillAddress->zipcode }} {{ $order->orderBillAddress->city }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Country</td>
                                            <td scope="row">{{ $order->orderBillAddress->country }}</td>
                                        </tr>

                                        <tr>
                                            <td>Phone</td>
                                            <td scope="row">{{ $order->orderBillAddress->phone }}</td>
                                        </tr>

                                        <tr>
                                            <td>Company</td>
                                            <td scope="row">{{ $order->orderBillAddress->company }}</td>
                                        </tr>


                                    </table>
                                </div>
                            </div>
                        </div>         
                        @endif
                    </div>

                </div>
                    
                <div class="col-md-8">

                    <div class="row rowTopPadding">
                        <div class="col-md-12">

                            <div class="panel panel-default">
                                <div class="panel-heading">Products</div>
                                <div class="panel-body">
                                    @if($order->products->count())
                                    <table class="table">
                                        <thead>
                                            @if($order->shop->wholesale)
                                          <tr>
                                            <th>Ref.</th>
                                            <th>&nbsp;</th>
                                            
                                            <th>Title</th>
                                            
                                            <th>Orig. price</th>
                                            <th>Price</th>

                                            <th>Total</th>
                                          </tr>
                                            @else
                                          <tr>
                                            <th>&nbsp;</th>
                                            <th>Title</th>
                                            <th>Reference number</th>
                                            <th>Original price</th>
                                            <th>Price</th>

                                            <th>Total</th>
                                          </tr>
                                          @endif
                                        </thead>
                                        <tbody>
                                            @foreach($order->products as $rule)
                                            @if($order->shop->wholesale)
                                           <tr>
                                                <td>{{ $rule->reference_code }}</td>
                                                <td>{{ $rule->amount }}x</td>
                                                <td>
                                                    {{ $rule->title }}
                                                    @if($rule->productAttribute()->count())
                                                    <br/><small>{{ $rule->product_attribute_title }}</small>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    &euro; {{ $rule->getOriginalPriceWithoutTaxNumberFormat() }}</td>
                                                <td>
                                                    @if($rule->original_price_with_tax != $rule->price_with_tax AND $rule->original_price_with_tax != 0)
                                                    &euro; {{ $rule->getPriceWithoutTaxNumberFormat() }}
                                                    <br/><small>discount
                                                    {{ number_format((($rule->original_price_with_tax - $rule->price_with_tax) / $rule->original_price_with_tax) * 100)  }}%
                                                    </small>
                                                    @else
                                                    &euro; {{ $rule->getPriceWithoutTaxNumberFormat() }}
                                                    @endif                                          
                                                </td>
             
                                                <td>&euro; {{ $rule->getTotalPriceWithoutTaxNumberFormat() }}</td>
                                            </tr>
                                            @else
                                           <tr>
                                                <td>{{ $rule->amount }}x</td>
                                                <td>
                                                    {{ $rule->title }}
                                                    @if($rule->productAttribute()->count())
                                                    <br/><small>{{ $rule->product_attribute_title }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $rule->reference_code }}</td>
                                                <td>
                                                    &euro; {{ $rule->getOriginalPriceWithTaxNumberFormat() }}
                                                </td>
                                                <td>
                                                    @if($rule->original_price_with_tax != $rule->price_with_tax AND $rule->original_price_with_tax != 0)
                                                    &euro; {{ $rule->getPriceWithTaxNumberFormat() }}
                                                    <br/><small>discount
                                                    {{ number_format((($rule->original_price_with_tax - $rule->price_with_tax) / $rule->original_price_with_tax) * 100)  }}%
                                                    </small>
                                                    @else
                                                    &euro; {{ $rule->getPriceWithTaxNumberFormat() }}
                                                    @endif                                          
                                                </td>
             
                                                <td>&euro; {{ $rule->getTotalPriceWithTaxNumberFormat() }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>no products</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($order->orderSendingMethod)
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Sending Method</div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                
                                            <tr>
                                                <td>Title:</td><td>{{ $order->orderSendingMethod->title }}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Price with tax:</td><td>&euro; {{ $order->orderSendingMethod->getPriceWithTaxNumberFormat() }}</td>                                        
                                            </tr>
                                            <tr>
                                                <td>Price without tax:</td><td>&euro; {{ $order->orderSendingMethod->getPriceWithoutTaxNumberFormat() }}</td>                                        
                                            </tr> 



                                        </tbody>
                                    </table>
                                </div>
                            </div>                     
                        </div> 
                        @endif             

                        @if($order->orderPaymentMethod)
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Payment Method</div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <tbody>
                        
                                            <tr>
                                                <td>Title:</td><td>{{ $order->orderPaymentMethod->title }}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Price with tax:</td><td>&euro; {{ $order->orderPaymentMethod->getPriceWithTaxNumberFormat() }}</td>                                        
                                            </tr>
                                            <tr>
                                                <td>Price without tax:</td><td>&euro; {{ $order->orderPaymentMethod->getPriceWithoutTaxNumberFormat() }}</td>                                        
                                            </tr>    



                                                             
                                        </tbody>
                                    </table>
                                </div>
                            </div>                     
                        </div>  
                        @endif                 
       

                        @if($order->comments)
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Comments</div>
                                <div class="panel-body">
                                    {{ $order->comments }}
                                </div>
                            </div>                     
                        </div> 
                        @endif 


                        @if($order->browser_detect)
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Browser information</div>
                                <div class="panel-body">

                               
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Device</td>
                                            @if($order->getBrowserDetectArray()['isMobile'])
                                            <td>Mobile</td>
                                
                                            @elseif($order->getBrowserDetectArray()['isTablet'])
                                            <td>Tablet</td>
                                         
                                            

                                            @elseif($order->getBrowserDetectArray()['isDesktop'])
                                            <td>Desktop</td>
                                            @endif

                                        </tr>

                                        <tr>
                                            <td>Browser</td>
                                            <td>{!! $order->getBrowserDetectArray()['browserFamily'] !!}</td>
                                        </tr>

                                        <tr>
                                        
                                            <td>OS</td>
                                            <td>{!! $order->getBrowserDetectArray()['osFamily'] !!}</td>

                                        </tr>


                                    </table>
                                </div>
                            </div>                     
                        </div> 


                        @endif


                        @if($order->present_message)
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Present service</div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                gender:
                                            </td>
                                            <td>
                                                {{ $order->present_gender }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                occassion:
                                            </td>
                                            <td>
                                                {{ $order->present_occassion }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                message:
                                            </td>
                                            <td>
                                                {{ $order->present_message }}
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </div>
                            </div>                     
                        </div> 
                        @endif 



                    </div>
                                    
                </div>            
            </div>
        </div>
    </div>
</div>
@stop