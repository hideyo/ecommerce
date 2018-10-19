@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('attribute-group.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('attribute-group.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('attribute-group.index') }}">Attribute group </a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Attribute group <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('attribute-group.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
 
            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, 
                    array(
                        'class' => 'form-control', 
                        'data-error' => trans('validation.required', ['attribute' => 'title']), 
                        'required' => 'required'
                    )) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('filter', 'Filter', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('filter', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{{ URL::route('attribute-group.index') }}" class="btn btn-large">Cancel</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@stop
