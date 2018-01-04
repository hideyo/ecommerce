@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('extra-field.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('extra-field.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="{{ URL::route('extra-field.index') }}">Extra field </a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Extra field <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('extra-field.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">
        
        <div class="form-group">   
            {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a title')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('all_products', 'All products', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('all_products', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('categories', 'Categories', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('categories[]', $productCategories, null, array('multiple' => 'multiple', 'class' => 'select2 form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('filterable', 'Filter', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('filterable', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{{ URL::route('extra-field.index') }}" class="btn btn-large">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@stop
