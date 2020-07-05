@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.coupon-group-tabs', array('couponGroupEdit' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('coupon-group.index') !!}">couponGroup</a></li>
            <li><a href="{!! URL::route('coupon-group.edit', $couponGroup->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('coupon-group.edit', $couponGroup->id) !!}">{!! $couponGroup->title !!}</a></li>
            <li class="active">general</li>
        </ol>

        <h2>couponGroup <small>edit</small></h2>
        <hr/>
        @include('flash::message')


        {!! Form::model($couponGroup, array('method' => 'put', 'route' => array('coupon-group.update', $couponGroup->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('coupon-group.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>            
    </div>
</div>
@stop
