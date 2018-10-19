@extends('backend._layouts.default')


@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('client.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('client.create') }}">Create</a></li>
            <li><a href="{{ URL::route('client.export') }}">Export</a></li>
            
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Clients</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{!! URL::route('client.index') !!}" class="btn btn-danger btn-icon icon-left pull-right">Back to overview<i class="entypo-back"></i></a>

        <h2>Client <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

 

        {!! Form::open(array('route' => array('client.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
    	 
     
            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
                </div>
            </div>

         	<div class="form-group">   
                {!! Form::label('email', 'Email', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::email('email', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('password', 'Wachtwoord', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::password('password', array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('password_confirmation', 'Herhaal wachtwoord', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::password('password_confirmation', array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>    


            <div class="form-group">
                {!! Form::label('company', 'Company', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('company', null, array('class' => 'form-control', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('gender', 'Gender', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('gender', array('male' => 'male', 'female' => 'female'), null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('initials', 'Initials', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('initials', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('firstname', 'Firstname', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('firstname', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('lastname', 'Lastname', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('lastname', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('street', 'Street', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('street', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('housenumber', 'Housenumber', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('housenumber', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('housenumber_suffix', 'Housenumber suffix', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('housenumber_suffix', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('zipcode', 'Zipcode', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('zipcode', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('city', 'City', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('city', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>   

            <div class="form-group">
                {!! Form::label('country', 'Country', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('country', array('NL' => 'Netherlands', 'BE' => 'Belgium', 'DE' => 'Germany', 'GB' => 'United Kingdom'), null, array('class' => 'form-control')) !!}
                </div>
            </div>   



            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('client.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>


        {!! Form::close() !!}
    </div>
</div>


@stop
