@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('user.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('user.index') !!}">Users</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>User <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

           {!! Form::model($user, array('method' => 'put', 'route' => array('user.update', $user->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
                     <input type="hidden" name="_token" value="{!! Session::token() !!}">
                     <div class="form-group">
                        {!! Form::label('username', 'Username', array('class' => 'col-sm-3 control-label')) !!}

                        <div class="col-sm-5">
                            {!! Form::text('username', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('email', 'E-mail', array('class' => 'col-sm-3 control-label')) !!}

                        <div class="col-sm-5">
                            {!! Form::text('email', null, array('class' => 'form-control', 'data-validate' => 'email,required', 'data-message-required' => 'This is custom message for required field.')) !!}
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
                        {!! Form::label('confirmed', 'Confirmed', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-5">
                       {!! Form::select('confirmed', array(0 => 'No', 1 => 'Yes'), null, array('class' => 'form-control')) !!}
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
