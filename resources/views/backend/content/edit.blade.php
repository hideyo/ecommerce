@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.content-tabs', array('contentEdit' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/admin"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('content.index') !!}">Content</a></li>
            <li><a href="{!! URL::route('content.edit', $content->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('content.edit', $content->id) !!}">{!! $content->title !!}</a></li>
            <li class="active">general</li>
        </ol>

        <h2>Content <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}


        {!! Form::model($content, array('method' => 'put', 'route' => array('content.update', $content->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('content_group_id', 'Group', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('content_group_id', [null => '--select--'] + $groups, null, array('class' => 'form-control')) !!}
                </div>
            </div>



            <div class="form-group has-feedback">
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
                    {!! Form::textarea('content', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            @include('backend._fields.seo-fields')
            @include('backend._fields.buttons', array('cancelRoute' => 'content.index'))           
    </div>
</div>
@stop
