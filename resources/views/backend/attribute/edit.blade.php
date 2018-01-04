@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('attribute-group.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{!! URL::route('attribute-group.edit', $attribute->attribute_group_id) !!}">Edit</a></li>
            <li class="active"><a href="{!! URL::route('attribute.index', $attribute->attribute_group_id) !!}">Attributes</a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('attribute-group.index') }}">Product weight types</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Attribute  <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($attribute, array('method' => 'put', 'route' => array('attribute.update', $attribute->attribute_group_id, $attribute->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

            <div class="form-group">   
                {!! Form::label('value', 'Value', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('value', null, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('attribute.index', $attribute->attribute_group_id) !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>
@stop







