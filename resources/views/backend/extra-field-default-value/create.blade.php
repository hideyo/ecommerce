@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('extra-field.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{!! URL::route('extra-field.edit', $extraField->id) !!}">Edit</a></li>
            <li class="active"><a href="{!! URL::route('extra-field.values.index', $extraField->id) !!}">Values</a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="{{ URL::route('extra-field.index') }}">Extra field </a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Extra field default value <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

    {!! Form::open(array('route' => array('extra-field.values.store', $extraField->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">
 
        <div class="form-group">   
            {!! Form::label('value', 'Value', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('value', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a value')) !!}
            </div>
        </div>





        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('extra-field.values.index', $extraField->id) !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

    {!! Form::close() !!}
    </div>
</div>
@stop
