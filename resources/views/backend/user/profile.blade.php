@extends('_layouts.default')

@section('main')

<h2>Profile <small>edit</small></h2>
<hr/>
@include('_partials.notifications')

<div class="panel panel-primary" data-collapsed="0">

    <div class="panel-body">

    {{ Form::model($user, array('method' => 'post', 'route' => array('update.profile'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) }}

        <div class="form-group">
            {{ Form::label('username', 'Username', array('class' => 'col-sm-3 control-label')) }}

            <div class="col-sm-5">
                {{ Form::text('username', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a title')) }}
            </div>
        </div>


        <div class="form-group">
            {{ Form::label('email', 'E-mail', array('class' => 'col-sm-3 control-label')) }}

            <div class="col-sm-5">
                {{ Form::text('email', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a title')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('language_id', 'Language', array('class' => 'col-sm-3 control-label')) }}
            <div class="col-sm-5">
           {{ Form::select('language_id', $languages, null, array('class' => 'form-control')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('selected_shop_id', 'Selected shop', array('class' => 'col-sm-3 control-label')) }}
            <div class="col-sm-5">
           {{ Form::select('selected_shop_id', $shops, null, array('class' => 'form-control')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('avatar', 'Avatar (250x250)', array('class' => 'col-sm-3 control-label')) }}
            <div class="col-sm-5">
           {{ Form::file('avatar', '', array('class' => 'form-control')) }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
                <a href="{{ URL::route('edit.profile') }}" class="btn btn-large">Cancel</a>
            </div>
        </div>

    {{ Form::close() }}
</div>
</div>


@stop
