@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('order-status-email-template.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('order-status-email-template.create') !!}">Create</a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('order-status-email-template.index') !!}">Order Email templates</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Order Email templates <small>create</small></h2>
        <hr/>
        @include('flash::message')
        <div class="row">
            <div class="col-md-8">
    {!! Form::open(array('route' => array('order-status-email-template.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">

        <div class="form-group">   
            {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-9">
                {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a subject')) !!}
            </div>
        </div>
        
        <div class="form-group">   
            {!! Form::label('subject', 'Subject', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-9">
                {!! Form::text('subject', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a subject')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('content', 'Content', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-9">
                {!! Form::textarea('content', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
            </div>
        </div>
  
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('order-status-email-template.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>
            </div>
    {!! Form::close() !!}



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
