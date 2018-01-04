@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('sending-payment-method-related.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('sending-payment-method-related.create') !!}">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('sending-payment-method-related.index') !!}">Order templates</a></li>  
            <li class="active">edit</li>
        </ol>
   
        <h2>Order templates <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}
        <div class="row">
            <div class="col-md-8">
                {!! Form::model($sendingPaymentMethodRelated, array('method' => 'put', 'route' => array('sending-payment-method-related.update', $sendingPaymentMethodRelated->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

                <div class="form-group">
                     <div class="col-sm-3 control-label">
                        {!! Form::label('pdf_text', 'PDF text', array('class' => '')) !!}
                        <p><small>text below order and invoice pdf</small></p>
                    </div>
                    <div class="col-sm-9">
                      
                        {!! Form::textarea('pdf_text', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                    </div>
                </div>

                <div class="form-group">
                     <div class="col-sm-3 control-label">
                        {!! Form::label('payment_text', 'Payment text') !!}
                        <p><small>Text after finishing order in shop. Only for not external payment way</small></p>
                    </div>

                    
                    <div class="col-sm-9">
                        {!! Form::textarea('payment_text', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                    </div>
                </div>

                <div class="form-group">

                     <div class="col-sm-3 control-label">
                        {!! Form::label('payment_confirmed_text', 'Payment confirmed text') !!}
                        <p><small>Text after returning from payment and finishing order in shop. Only for external payment way</small></p>
                    </div>

                    <div class="col-sm-9">
                        {!! Form::textarea('payment_confirmed_text', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                    </div>
                </div>



                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                        <a href="{!! URL::route('sending-payment-method-related.index') !!}" class="btn btn-large">Cancel</a>
                    </div>
                </div>


                {!! Form::close() !!}
            </div>

            <div class="col-md-4">
      
                <table class="well table table-bordered">
                    <thead>
                        <tr>
                            <th>tag</th>
                            <th>description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>[clientFirstname]</td>
                            <td>client firstname</td>
                        </tr>

                        <tr>
                            <td>[clientLastname]</td>
                            <td>client lastname</td>
                        </tr>

                        <tr>
                            <td>[clientEmail]</td>
                            <td>client email</td>
                        </tr>


                        <tr>
                            <td>[clientCompany]</td>
                            <td>client company</td>
                        </tr>


                        <tr>
                            <td>[orderId]</td>
                            <td>orderid</td>
                        </tr>

                        <tr>
                            <td>[orderCreated]</td>
                            <td>order created</td>
                        </tr>

                        <tr>
                            <td>[orderTotalPriceWithTax]</td>
                            <td>order total price with tax</td>
                        </tr>

                        <tr>
                            <td>[orderTotalPriceWithoutTax]</td>
                            <td>order total price without tax</td>
                        </tr>

                        <tr>
                            <td>[clientDeliveryStreet]</td>
                            <td>delivery street</td>
                        </tr>


                        <tr>
                            <td>[clientDeliveryHousenumber]</td>
                            <td>delivery housenumber</td>
                        </tr>

                        <tr>
                            <td>[clientDeliveryHousenumberSuffix]</td>
                            <td>delivery housenumber suffix</td>
                        </tr>


                        <tr>
                            <td>[clientDeliveryZipcode]</td>
                            <td>delivery zipcode</td>
                        </tr>


                        <tr>
                            <td>[clientDeliveryCity]</td>
                            <td>delivery city</td>
                        </tr>

                        <tr>
                            <td>[clientDeliveryCountry]</td>
                            <td>delivery country</td>
                        </tr>

                        <tr>
                            <td>[clientDeliveryCompany]</td>
                            <td>delivery company</td>
                        </tr>


                        

                    </tbody>
                </table>
                  
            </div>

    </div>
</div>
@stop


