@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('tax-rate.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('tax-rate.create') }}">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('tax-rate.index') }}">Tax rates</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Tax rate <small>edit</small></h2>
        <hr/>
        @include('flash::message')

        {!! Form::model($taxRate, array('method' => 'put', 'route' => array('tax-rate.update', $taxRate->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, 
                    array(
                        'class' => 'form-control', 
                        'data-error' => trans('validation.required', ['attribute' => 'title']), 
                        'required' => 'required'
                    )) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="form-group">   
                {!! Form::label('rate', 'Rate', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('rate', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            @include('backend._fields.buttons', array('cancelRoute' => 'tax-rate.index'))
        {!! Form::close() !!}
    </div>
</div>
@stop






