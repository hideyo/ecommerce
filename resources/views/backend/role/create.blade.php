@extends('_layouts.default')

@section('main')

<h2>Role <small>create</small></h2>
<hr/>
@include('_partials.notifications')
@if (Session::get('error'))
    <div class="alert alert-error alert-danger">
        @if (is_array(Session::get('error')))
            {{ head(Session::get('error')) }}
        @endif
    </div>
@endif




<div class="panel panel-primary" data-collapsed="0">

    <div class="panel-body">

    {{ Form::open(array('route' => array('role.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) }}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">
        <div class="form-group">
            {{ Form::label('name', 'Name', array('class' => 'col-sm-3 control-label')) }}

            <div class="col-sm-5">
                {{ Form::text('name', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a title')) }}
            </div>
        </div>




        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
                <a href="{{ URL::route('user.index') }}" class="btn btn-large">Cancel</a>
            </div>
        </div>

    {{ Form::close() }}
</div>
</div>


@stop
