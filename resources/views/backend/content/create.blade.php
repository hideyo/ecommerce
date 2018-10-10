@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('content.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('content.create') !!}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('content.index') !!}">Content</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Content <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('content.store'), 'files' => true, 'role' => 'form', 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('content_group_id', 'Group', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('content_group_id', [null => '--select--'] + $groups, null, array('class' => 'form-control')) !!}
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
                {!! Form::label('content', 'Content', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('content', null, array('class' => 'form-control ckeditor')) !!}
                </div>
            </div>
            
            @include('backend._fields.seo-fields')
            @include('backend._fields.buttons', array('cancelRoute' => 'content.index'))


        {!! Form::close() !!}
    </div>
</div>
@stop



