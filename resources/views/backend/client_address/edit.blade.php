@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li ><a href="{{ URL::route('client.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li>
                <a href="{{ URL::route('client.edit', $client->id) }}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Edit</span>
                </a>
            </li>

            <li class="active">
                <a href="{!! URL::route('client.addresses.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Adressess</span>
                </a>
            </li>

            <li>
                <a href="{!! URL::route('client.order.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Orders</span>
                </a>
            </li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/admin"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Client</a></li>
            <li><a href="{{ URL::route('client.edit', $client->id) }}">{!! $client->email !!}</a></li>
            <li class="active"><a href="{{ URL::route('client.addresses.index', $client->id) }}">addresses</a></li>
                <li class="active">edit</li>

        </ol>
          <h2>Client <small>addresses</small></h2>
        <hr/>
        {!! Notification::showAll() !!}     
        <div class="row">
            <div class="col-md-12">


				{!! Form::model($clientAddress, array('method' => 'put', 'route' => array('client.addresses.update', $client->id, $clientAddress->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}


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
                            {!! Form::label('phone', 'Phone', array('class' => 'col-sm-3 control-label')) !!}
                            <div class="col-sm-5">
                                {!! Form::text('phone', null, array('class' => 'form-control', 'data-message-required' => 'This is custom message for required field.')) !!}
                            </div>
                        </div>


				        <div class="form-group">
				            <div class="col-sm-offset-3 col-sm-5">
				                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
				                <a href="{!! URL::route('client.addresses.store', $client->id) !!}" class="btn btn-large">Cancel</a>
				            </div>
				        </div>

				    {!! Form::close() !!}
			</div>

	</div>
</div>
@stop