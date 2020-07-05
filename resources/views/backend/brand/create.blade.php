@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('brand.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('brand.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('brand.index') }}">Brands</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Brands <small>create</small></h2>
        <hr/>
        @include('flash::message')

    	{!! Form::open(array('route' => array('brand.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
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
	                {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
	            </div>
	        </div>

            <div class="form-group">
                {!! Form::label('short_description', 'Short Description', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::textarea('short_description', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::textarea('description', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('rank', 'Rank', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('rank', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            @include('backend._fields.seo-fields')
            @include('backend._fields.buttons', array('cancelRoute' => 'brand.index'))

    	{!! Form::close() !!}
    </div>
</div>
@stop
