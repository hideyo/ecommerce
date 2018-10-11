@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('sending-method.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('sending-method.create') }}">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('sending-method.index') }}">Sending methods</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Sending method country price <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($sendingMethodCountry, array('method' => 'put', 'route' => array('sending-method.country-prices.update', $sendingMethod->id, $sendingMethodCountry->id), 'files' => true, 'class' => 'form-horizontal form-groups-bordered', 'data-toggle' => 'validator')) !!}


            <div class="form-group">   
                {!! Form::label('name', 'Name', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('price', 'Price', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('price', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('no_price_from', 'No Price from', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('no_price_from', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('minimal_weight', 'Minimal weight', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('minimal_weight', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('maximal_weight', 'Maximal weight', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('maximal_weight', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>



                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                        <a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}" class="btn btn-large">Cancel</a>
                    </div>
                </div>


            {!! Form::close() !!}
    </div>
</div>
@stop


