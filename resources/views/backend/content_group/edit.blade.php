@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.content-group-tabs', array('contentGroupEdit' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/admin"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('content-group.index') !!}">ContentGroup</a></li>
            <li><a href="{!! URL::route('content-group.edit', $contentGroup->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('content-group.edit', $contentGroup->id) !!}">{!! $contentGroup->title !!}</a></li>
            <li class="active">general</li>
        </ol>

        <h2>ContentGroup <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}


        {!! Form::model($contentGroup, array('method' => 'put', 'route' => array('content-group.update', $contentGroup->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            @include('backend._fields.buttons', array('cancelRoute' => 'content-group.index'))   

        {!! Form::close() !!}        
    </div>
</div>
@stop
