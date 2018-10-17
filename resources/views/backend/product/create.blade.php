@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('product.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('product.create') !!}">Create</a></li>
            <li><a href="{{ URL::route('product.export') }}">Export</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Products</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Product <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('product.store'), 'files' => true, 'role' => 'form', 'class' => 'form-horizontal', 'data-focus' => 'true', 'data-toggle' => 'validator')) !!}
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
                {!! Form::text('title', null, 
                array(
                    'class' => 'form-control', 
                    'minlength' => 4, 
                    'maxlength' => 65, 
                    'data-error' => trans('validation.between.numeric', ['attribute' => 'title', 'min' => 4, 'max' => 65]), 
                    'required' => 'required'
                )) !!}
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="form-group">
              {!! Form::label('product_category_id', 'Category', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
  
                {!! Form::select('product_category_id', $productCategories, null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('brand_id', 'Brand', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('brand_id', [null => '--select--'] + $brands, null, array('class' => 'form-control')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('subcategories', 'Subcategories', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('subcategories[]', $productCategories, null, array('multiple' => 'multiple', 'class' => 'select2 form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('reference_code', 'Reference code', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('reference_code', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ean_code', 'Ean code', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('ean_code', null, array('class' => 'form-control', 'data-validate' => 'number')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('mpn_code', 'MPN code', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('mpn_code', null, array('class' => 'form-control', 'data-validate' => 'number')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount', 'Amount', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::number('amount', null, array('class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('short_description', 'Short Description', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::textarea('short_description', null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::textarea('description', null, array('class' => 'form-control ckeditor')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'tax-rate form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('price', 'Price without tax', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('price', null, array('class' => 'price form-control', 'data-sign' => '&euro;')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('price_inc_tax', 'Price with tax', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('price_inc_tax', null, array('class' => 'price_inc_tax form-control', 'data-sign' => '&euro;')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('weight', 'Weight', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('weight', null, array('class' => 'form-control', 'data-sign' => '&euro;')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('rank', 'Rank', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('rank', null, array('class' => 'form-control')) !!}
            </div>
        </div>

        @include('backend._fields.buttons', array('cancelRoute' => 'product.index'))

        {!! Form::close() !!}
    </div>
</div>
@stop



