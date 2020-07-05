@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('html-block.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{{ URL::route('html-block.create') }}">Edit</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('html-block.index') !!}">Html block</a></li>
            <li><a href="{!! URL::route('html-block.edit', $htmlBlock->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('html-block.edit', $htmlBlock->id) !!}">{!! $htmlBlock->title !!}</a></li>
            <li class="active">general</li>
        </ol>

        <h2>Html block <small>edit</small></h2>
        <hr/>
        @include('flash::message')


        {!! Form::model($htmlBlock, array('method' => 'put', 'route' => array('html-block.update', $htmlBlock->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
            <input type="hidden" name="_token" value="{!! Session::token() !!}">

            <div class="form-group">
                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>



            <div class="form-group">
                {!! Form::label('short_title', 'Short title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('short_title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('button_title', 'Button title', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('button_title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('url', 'Url', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('url', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('position', 'Position', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::select('position', array(
                    'homepage-highlight-1' => 'homepage-highlight-1', 
                    'homepage-highlight-2' => 'homepage-highlight-2', 
                    'homepage-highlight-3' => 'homepage-highlight-3', 
                    'homepage-highlight-4' => 'homepage-highlight-4', 
                    'homepage-highlight-5' => 'homepage-highlight-5', 
                    'homepage-highlight-mobile-1' => 'homepage-highlight-mobile-1', 
                    'homepage-highlight-mobile-2' => 'homepage-highlight-mobile-2', 
                    'homepage-highlight-mobile-3' => 'homepage-highlight-mobile-3',
                    'homepage-category-1' => 'homepage-category-1',
                    'homepage-category-2' => 'homepage-category-2',
                    'homepage-category-3' => 'homepage-category-3',
                    'homepage-category-4' => 'homepage-category-4',
                    'homepage-category-5' => 'homepage-category-5',
                    'homepage-category-6' => 'homepage-category-6',
                    'homepage-category-mobile-1' => 'homepage-category-mobile-1',
                    'homepage-category-mobile-2' => 'homepage-category-mobile-2',
                    'homepage-category-mobile-3' => 'homepage-category-mobile-3',
                    'homepage-category-mobile-4' => 'homepage-category-mobile-4',
                    'homepage-category-mobile-5' => 'homepage-category-mobile-5',
                    'homepage-category-mobile-6' => 'homepage-category-mobile-6',
                    'foodelicious-box-left' => 'foodelicious-box-left',
                    'foodelicious-box-right' => 'foodelicious-box-right',
                    'footer-2' => 'footer-2',
                    'footer-3' => 'footer-3',
                    'footer-5' => 'footer-5',
                    'faq-intro' => 'faq-intro',
                    'brand-intro' => 'brand-intro',
                    'contact-sidebar-1' => 'contact-sidebar-1',
                    'contact-sidebar-2' => 'contact-sidebar-2',
                    'new-products-text' => 'new-products-text',
                    'sale-products-text' => 'sale-products-text',
                    'empty-cart-text' => 'empty-cart-text'

                    ), null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('content', 'Content', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('content', null, array('class' => 'form-control ckeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>




            <div class="form-group">
                {!! Form::label('image', 'Image', array('class' => 'col-sm-3 control-label')) !!}

                <div class="col-sm-5">
                    {!! Form::file('image', null, array('class' => 'form-control', 'data-message-required' => 'This field is required.')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('thumbnail_height', 'Thumbnail height', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('thumbnail_height', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('thumbnail_width', 'Thumbnail width', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('thumbnail_width', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('template', 'Template', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('template', null, array('class' => 'form-control', 'id' => 'codeeditor', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                </div>
            </div>



            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                    <a href="{!! URL::route('html-block.index') !!}" class="btn btn-large">Cancel</a>
                </div>
            </div>            
    </div>
</div>
@stop
