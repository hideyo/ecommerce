@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('news-group.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('news-group.create') !!}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('news-group.index') !!}">Newsgroup</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Newsgroup <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('news-group.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
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
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            @include('backend._fields.buttons', array('cancelRoute' => 'news-group.index'))


        {!! Form::close() !!}
    </div>
</div>
@stop



