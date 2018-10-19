@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('redirect.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('redirect.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('redirect.index') !!}">redirect</a></li>  
            <li class="active">edit</li>
        </ol>

        <a href="{!! URL::route('redirect.index') !!}" class="btn btn-danger pull-right">Back to overview<i class="entypo-back"></i></a>

        <h2>Redirect <small>edit</small></h2>
        <br/>
        {!! Notification::showAll() !!}

        {!! Form::model($redirect, array('method' => 'put', 'route' => array('redirect.update', $redirect->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
               {!! Form::select('active', array(0 => 'No', 1 => 'Yes'), null, array('class' => 'form-control')) !!}
                </div>
            </div>

  
            <div class="form-group">   
                {!! Form::label('url', 'Url', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('url', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
                </div>
            </div>


            <div class="form-group">   
                {!! Form::label('redirect_url', 'Redirect url', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('redirect_url', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
                </div>
            </div>


            
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('redirect.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>
@stop