@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('sending-method.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('sending-method.index') }}">Sending methods</a></li> 
            <li><a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}">{!! $sendingMethod->title !!}</a></li>  
            <li class="active">create country price</li>
        </ol>

        <h2>Sending method country price <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::open(array('route' => array('sending-method.import', $sendingMethod->id), 'files' => true, 'class' => 'form-horizontal form-groups-bordered', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'form-control')) !!}
                </div>
            </div>

                <div class="form-group">
                    {!! Form::label('file', 'File (csv)', array('class' => 'col-sm-3 control-label')) !!}

                    <div class="col-sm-5">
                        {!! Form::file('file', null, array('class' => 'form-control', 'data-message-required' => 'This field is required.')) !!}
                    </div>
                </div>


            </div>



            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Import', array('class' => 'btn btn-default')) !!}
                    <a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}" class="btn btn-large">Cancel</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@stop