@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('payment-method.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('payment-method.create') }}">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('payment-method.index') }}">Payment methods</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Payment method <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($paymentMethod, array('method' => 'put', 'route' => array('payment-method.update', $paymentMethod->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

        <div class="form-group">
            {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">   
            {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('percent_of_total', 'Percent total', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('percent_of_total', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('price', 'Price', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('price', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('no_price_from', 'No Price from', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('no_price_from', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('payment_external', 'Payment external', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('payment_external', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('mollie_external_payment_way', 'Mollie payment way', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('mollie_external_payment_way', array('ideal' => 'ideal', 'paysafecard' => 'paysafecard', 'creditcard' => 'creditcard', 'mistercash' => 'mistercash', 'sofort' => 'sofort', 'banktransfer' => 'banktransfer', 'directdebit' => 'directdebit', 'paypal' => 'paypal', 'bitcoin' => 'bitcoin', 'belfius' => 'belfius', 'podiumcadeaukaart' => 'podiumcadeaukaart', 'kbc' => 'kbc', 'bancontact' => 'bancontact'), null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('mollie_api_key', 'Mollie API key', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('mollie_api_key', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('order_confirmed_order_status_id', 'Order status when order confirmed', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_confirmed_order_status_id', $orderStatuses, null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('payment_completed_order_status_id', 'Order status when order paid', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('payment_completed_order_status_id', $orderStatuses, null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('payment_failed_order_status_id', 'Order status when order paid failed', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('payment_failed_order_status_id', $orderStatuses, null, array('class' => 'form-control')) !!}
            </div>
        </div>        

            <div class="form-group">
                {!! Form::label('total_price_discount_type', 'Total price discount type', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('total_price_discount_type', array('amount' => 'Amount','percent' => 'Percent'), null, array('class' => 'tax-rate form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('total_price_discount_value', 'Total price discount value', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('total_price_discount_value', null, array('class' => 'form-control', 'data-validate' => 'number', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('total_price_discount_start_date', 'Total price discount start date', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('total_price_discount_start_date', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('total_price_discount_end_date', 'Total price discount end date', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('total_price_discount_end_date', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('payment-method.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@stop



