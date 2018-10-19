@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('faq.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('faq.create') !!}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="{!! URL::route('faq.index') !!}">Faq item</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Faq item <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('faq.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('faq_item_group_id', 'Group', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('faq_item_group_id', $groups, null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('question', 'Question', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('question', null, 
                    array(
                        'class' => 'form-control', 
                        'minlength' => 4, 
                        'maxlength' => 65, 
                        'data-error' => trans('validation.between.numeric', ['attribute' => 'question', 'min' => 4, 'max' => 65]), 
                        'required' => 'required'
                    )) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('answer', 'Answer', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('answer', null, array('class' => 'form-control ckeditor')) !!}
                </div>
            </div>

            @include('backend._fields.buttons', array('cancelRoute' => 'faq.index')) 


        {!! Form::close() !!}
    </div>
</div>
@stop



