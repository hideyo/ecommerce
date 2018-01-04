@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('order-status.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('order-status.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('order-status.index') }}">Order statuses</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Order statuses <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('order-status.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">
 

        <div class="form-group">   
            {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>


        <div class="colorpicker">
        <div class="form-group">   
            {!! Form::label('color', 'Color', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                <div class="input-group ">
                    {!! Form::text('color', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    <span class="input-group-addon"><i></i></span>
                </div>

            </div>
        </div>
    </div>



        <div class="form-group">
            {!! Form::label('order_is_validated', 'Consider order as validated', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_is_validated', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>




        <div class="form-group">
            {!! Form::label('order_is_paid', 'Set order as paid', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_is_paid', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('count_as_revenue', 'Count as revenue', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('count_as_revenue', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('order_is_delivered', 'Set order as delivered', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_is_delivered', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('order_is_cancelled', 'Set order as cancelled', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_is_cancelled', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('send_email_to_customer', 'Send an email to the customer when his/her order status has changed.', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('send_email_to_customer', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('send_email_copy_to', 'Send email copy to', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('send_email_copy_to', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>



        <div class="form-group">
            {!! Form::label('attach_invoice_to_email', 'Add invoice to email', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('attach_invoice_to_email', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('attach_order_to_email', 'Add order to email', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('attach_order_to_email', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('create_shipping_label', 'Create shipping label', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                 {!! Form::select('create_shipping_label', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>

<!--         <div class="form-group">
            {!! Form::label('confirm_shipment_label', 'Confirm shipping label', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('confirm_shipment_label', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div> -->


        <div class="form-group">
            {!! Form::label('order_status_email_template_id', 'Email template', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('order_status_email_template_id', $templates, null, array('class' => 'order_status_email_template_id tax-rate form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('output', 'Email template preview', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                <div class="load-order-status-template" data-url="/admin/order-status-email-template/show-template/">

                </div>
            </div>
        </div>





        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('order-status.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>


    {!! Form::close() !!}
    </div>
</div>
@stop
