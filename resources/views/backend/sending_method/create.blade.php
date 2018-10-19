@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('sending-method.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('sending-method.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('sending-method.index') }}">Sending methods</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Sending method <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('sending-method.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
    	    <input type="hidden" name="_token" value="{!! Session::token() !!}">
     
            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>

         	<div class="form-group">   
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('price', 'Price', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('price', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('no_price_from', 'No Price from', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('no_price_from', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('minimal_weight', 'Minimal weight', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('minimal_weight', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('maximal_weight', 'Maximal weight', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('maximal_weight', null, array('class' => 'form-control')) !!}
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
                {!! Form::label('payment_methods', 'Payment methods', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('payment_methods[]', $paymentMethods, null, array('multiple' => 'multiple', 'class' => 'select2 form-control')) !!}                   
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('sending-method.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@stop