@extends('_layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('invoice.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Show</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('invoice.index') }}">Invoices</a></li>  
            <li class="active">show</li>
        </ol>

        <a href="{{ URL::route('invoice.index') }}" class="btn btn-danger btn-icon icon-left pull-right">Back to overview<i class="entypo-back"></i></a>

        <h2>Invoice <small>show</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <div class="row rowTopPadding">
            <div class="col-md-12"> 
                <div class="col-md-4">                 

                    <table class="table"> 
                        <tr>
                            <td>Id</td>
                            <td scope="row">{{ $invoice->id }}</td>
                        </tr>


                        <tr>
                            <td>Client email</td>
                            <td scope="row">
                                @if($invoice->client)
                                {{ $invoice->client->email }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Created at</td>
                            <td scope="row">{{ $invoice->created_at }}</td>
                        </tr> 
                        <tr>
                            <td>Price with tax</td>
                            <td scope="row">&euro; {{ $invoice->price_with_tax }}</td>
                        </tr>
                        <tr>
                            <td>Price without tax</td>
                            <td scope="row">&euro; {{ $invoice->price_without_tax }}</td>
                        </tr> 
                        <tr>
                            <td>Tax</td>
                            <td scope="row">&euro; {{  $invoice->taxTotal() }}<br/>
                                @foreach ($invoice->taxDetails() as $key => $val)
                                <small>{{ round($key) }}%: &euro; {{ round($val,2) }}</small><br/>
                                @endforeach
                            </td>
                        </tr>
                    </table>                   
            
                    <div class="row rowTopPadding">
                        @if($invoice->invoiceDeliveryAddress)
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Delivery address</div>
                                <div class="panel-body"> 

                                    <table class="table"> 
                                        <tr>
                                            <td>Gender</td>
                                            <td scope="row">{{ $invoice->invoiceDeliveryAddress->gender }}</td>
                                        </tr> 

                                        <tr>
                                            <td>Name</td>
                                            <td scope="row">{{ $invoice->invoiceDeliveryAddress->firstname }} {{ $invoice->invoiceDeliveryAddress->lastname }}</td>
                                        </tr>                                                   
                                        <tr>
                                            <td>Street</td>
                                            <td scope="row">{{ $invoice->invoiceDeliveryAddress->street }} {{ $invoice->invoiceDeliveryAddress->housenumber }} {{ $invoice->invoiceDeliveryAddress->housenumber_suffix }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Zipcode</td>
                                            <td scope="row">{{ $invoice->invoiceDeliveryAddress->zipcode }} {{ $invoice->invoiceDeliveryAddress->city }}</td>
                                        </tr>  
                                        <tr>
                                            <td>Country</td>
                                            <td scope="row">{{ $invoice->invoiceDeliveryAddress->country }}</td>
                                        </tr> 
                                    </table>
                                </div>
                            </div>
                        </div>

                        @endif

                        @if($invoice->invoiceBillAddress)
                        <div class="col-md-12">

                            <div class="panel panel-default">
                                <div class="panel-heading">Bill address</div>
                                <div class="panel-body">                      
                                    <table class="table table-binvoiceed">  
                                        <tr>
                                            <td>Gender</td>
                                            <td scope="row">{{ $invoice->invoiceBillAddress->gender }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Name</td>
                                            <td scope="row">{{ $invoice->invoiceBillAddress->firstname }} {{ $invoice->invoiceBillAddress->lastname }}</td>
                                        </tr>                                                  
                                        <tr>
                                            <td>Street</td>
                                            <td scope="row">{{ $invoice->invoiceBillAddress->street }} {{ $invoice->invoiceBillAddress->housenumber }} {{ $invoice->invoiceDeliveryAddress->housenumber_suffix }}</td>
                                        </tr>
                                        <tr>
                                            <td>Zipcode</td>
                                            <td scope="row">{{ $invoice->invoiceBillAddress->zipcode }} {{ $invoice->invoiceBillAddress->city }}</td>
                                        </tr> 
                                        <tr>
                                            <td>Country</td>
                                            <td scope="row">{{ $invoice->invoiceBillAddress->country }}</td>
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
                                <div class="panel-heading">Invoice rules</div>
                                <div class="panel-body">
                                    @if($invoice->products->count())
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th>&nbsp;</th>
                                            <th>Title</th>
                                            <th>Reference number</th>
                                            <th>Price (tax)</th>
                                            <th>Price (notax)</th>
                                            <th>Tax</th>
                                            <th>Total price (tax)</th>
                                            <th>Total price (notax)</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->products as $rule)
                                           <tr>
                                                <td>{{ $rule->amount }}x</td>
                                                <td>
                                                    {{ $rule->title }}
                                                    @if($rule->productAttribute()->count())
                                                    <br/><small>{{ $rule->product_attribute_title }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $rule->reference_code }}</td>
                                                <td>&euro; {{ $rule->price_with_tax }}</td>
                                                <td>&euro; {{ $rule->price_without_tax }}</td>
                                                <td>{{ $rule->tax_rate }}</td>
                                                <td>&euro; {{ $rule->total_price_with_tax }}</td>
                                                <td>&euro; {{ $rule->total_price_without_tax }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>no products</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($invoice->invoiceSendingMethod)
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Sending Method</div>
                                <div class="panel-body">
                                    <table class="table table-binvoiceed">
                                        <tbody>
                                
                                            <tr>
                                                <td>Title:</td><td>{{ $invoice->invoiceSendingMethod->title }}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Price with tax:</td><td>&euro; {{ $invoice->invoiceSendingMethod->price_with_tax }}</td>                                        
                                            </tr>
                                            <tr>
                                                <td>Price without tax:</td><td>&euro; {{ $invoice->invoiceSendingMethod->price_without_tax }}</td>                                        
                                            </tr> 

                                            <tr>
                                                <td>Tax rate:</td><td>{{ $invoice->invoiceSendingMethod->tax_rate }}</td>                                        
                                            </tr> 

                                        </tbody>
                                    </table>
                                </div>
                            </div>                     
                        </div> 
                        @endif             

                        @if($invoice->invoicePaymentMethod)
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Payment Method</div>
                                <div class="panel-body">
                                    <table class="table table-binvoiceed">
                                        <tbody>
                        
                                            <tr>
                                                <td>Title:</td><td>{{ $invoice->invoicePaymentMethod->title }}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Price with tax:</td><td>&euro; {{ $invoice->invoicePaymentMethod->price_with_tax }}</td>                                        
                                            </tr>
                                            <tr>
                                                <td>Price without tax:</td><td>&euro; {{ $invoice->invoicePaymentMethod->price_without_tax }}</td>                                        
                                            </tr>    

                                            <tr>
                                                <td>Tax rate:</td><td>{{ $invoice->invoicePaymentMethod->tax_rate }}</td>                                        
                                            </tr>                                                             
                                        </tbody>
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
@stop