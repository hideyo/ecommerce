@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('extra-field.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{!! URL::route('extra-field.edit', $extraFieldDefaultValue->extra_field_id) !!}">Edit</a></li>
            <li class="active"><a href="{!! URL::route('extra-field.values.index', $extraFieldDefaultValue->extra_field_id) !!}">Values</a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="{{ URL::route('extra-field.index') }}">Product weight types</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Extra field default value  <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($extraFieldDefaultValue, array('method' => 'put', 'route' => array('extra-field.values.update', $extraFieldDefaultValue->extra_field_id, $extraFieldDefaultValue->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}


                <div class="form-group">   
                    {!! Form::label('value', 'Value', array('class' => 'col-sm-3 control-label')) !!}

                    <div class="col-sm-5">
                        {!! Form::text('value', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                        <a href="{!! URL::route('extra-field.values.index', $extraFieldDefaultValue->extra_field_id) !!}" class="btn btn-large">Cancel</a>
                    </div>
                </div>


            {!! Form::close() !!}
    </div>
</div>
@stop







