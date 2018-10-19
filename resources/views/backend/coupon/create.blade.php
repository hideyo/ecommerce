@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('coupon.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('coupon.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('coupon.index') }}">Coupons</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Coupon <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('coupon.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

            <div class="form-group">
                {!! Form::label('coupon_group_id', 'Group', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('coupon_group_id', [null => '--select--'] + $groups, null, array('class' => 'form-control')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('permanent', 'Permanent', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('permanent', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>


         	<div class="form-group">   
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

         	<div class="form-group">   
                {!! Form::label('code', 'Code', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('value', 'Value', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('value', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('type', 'Type', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('type', array('total_price' => 'Total price', 'product' => 'Product', 'product_category' => 'Product category', 'sending_method' => 'Sending method',  'payment_method' => 'Payment method'), null, array('class' => 'form-control')) !!}
                </div>
            </div>           

            <div class="form-group">
                {!! Form::label('discount_way', 'Discount way', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('discount_way', array('percent' => 'Percent', 'total' => 'Total'), null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('product_categories', 'Product categories', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::multiselect2('product_categories[]', $productCategories->toArray()) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('products', 'Products', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::multiselect2('products[]', $products->toArray()) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('sending_methods', 'Sending methods', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::multiselect2('sending_methods[]', $sendingMethods->toArray()) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('payment_methods', 'Payment methods', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::multiselect2('payment_methods[]', $paymentMethods->toArray()) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('published_at', 'Published at', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('published_at', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('unpublished_at', 'Unplublished at', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('unpublished_at', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('coupon.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>


        {!! Form::close() !!}
    </div>
</div>
@stop