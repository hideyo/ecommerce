@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('client.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('client.create') }}">Create</a></li>
            <li class="active"><a href="{{ URL::route('client.export') }}">Export</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Clients</a></li>  
            <li class="active">export</li>
        </ol>

    
        <h2>Clients <small>export</small></h2>
        <hr/>
        @include('flash::message')

        
        {!! Form::open(array('route' => array('client.export'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">
        
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Export xls', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('client.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>   
@stop