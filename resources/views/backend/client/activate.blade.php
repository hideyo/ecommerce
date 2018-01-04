@extends('backend._layouts.default')


@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.client-tabs', array('clientActivate' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Clients</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('client.index') }}" class="btn btn-danger pull-right" aria-label="Left Align"> back</a>

        <h2>Client <small>Activate</small></h2>
        <p>Activate this client:</p>
        <hr/>

        {!! Notification::showAll() !!}

        <div class="row">
            <div class="col-md-12">

					{!! Form::model($client, array('route' => array('client.activate', $client->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
					
                    <div class="form-group">
                        {!! Form::label('send_mail', 'Send e-mail notification', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-5">
                            {!! Form::select('send_mail', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

					<div class="form-group">   
						{!! Form::label('email', 'Email', array('class' => 'col-sm-3 control-label')) !!}

						<div class="col-sm-5">
						    {!! Form::email('email', null, array('disabled' => 'disabled', 'class' => 'form-control', 'required' => 'required')) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
						    {!! Form::submit('Activate', array('class' => 'btn btn-default')) !!}
						    <a href="{!! URL::route('client.index') !!}" class="btn btn-large">Cancel</a>
						</div>
					</div>


					{!! Form::close() !!}


                </div>
            </div>

        </div>

    </div>

</div>
@stop