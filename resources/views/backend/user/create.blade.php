@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('user.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('user.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('user.index') }}">Users</a></li>  
            <li class="active">create</li>
        </ol>

        <a href="{{ URL::route('user.index') }}" class="btn btn-danger pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-return" aria-hidden="true"></span> Back</a>

        <h2>Users <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('user.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        	    <input type="hidden" name="_token" value="{!! Session::token() !!}">
            <div class="form-group">
                {!! Form::label('username', 'Username', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('username', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('email', 'E-mail', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('email', null, array('class' => 'form-control', 'data-validate' => 'email,required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('password', 'Wachtwoord', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::password('password', array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('password_confirmation', 'Wachtwoord', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::password('password_confirmation', array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('selected_shop_id', 'Selected shop', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                {!! Form::select('selected_shop_id', $shops, null, array('class' => 'form-control')) !!}
                </div>
            </div>



            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('user.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop
