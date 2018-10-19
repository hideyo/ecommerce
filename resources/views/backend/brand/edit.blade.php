@extends('backend._layouts.default')


@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.brand-tabs', array('brandEdit' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('brand.index') }}">Brands</a></li>  
            <li class="active">edit</li>
        </ol>

        <h2>Brands <small>edit</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($brand, array('method' => 'put', 'route' => array('brand.update', $brand->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            

            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">   
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('short_description', 'Short Description', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::textarea('short_description', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::textarea('description', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>            


            <div class="form-group">
                {!! Form::label('rank', 'Rank', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('rank', null, array('class' => 'form-control')) !!}
                </div>
            </div>
            
            @include('backend._fields.seo-fields')
            @include('backend._fields.buttons', array('cancelRoute' => 'brand.index'))
            
        {!! Form::close() !!}
    </div>
</div>
@stop






