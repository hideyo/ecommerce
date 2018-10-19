@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('news.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('news.create') !!}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('news.index') !!}">News</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>News <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('news.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('news_group_id', 'Group', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('news_group_id', [null => '--select--'] + $groups, null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('short_description', 'Short description', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('short_description', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>





            <div class="form-group">
                {!! Form::label('news', 'Content', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('content', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('published_at', 'Published at', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('published_at', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('news.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div> 


        {!! Form::close() !!}
    </div>
</div>
@stop



