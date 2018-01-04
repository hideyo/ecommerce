@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.content-group-tabs', array('contentGroupEditSeo' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('content-group.index') !!}">Content</a></li>
            <li><a href="{!! URL::route('content-group.edit', $contentGroup->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('content-group.edit', $contentGroup->id) !!}">{!! $contentGroup->title !!}</a></li>
            <li class="active">seo</li>
        </ol>

        <h2>Content <small>edit seo</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($contentGroup, array('method' => 'put', 'route' => array('content-group.update', $contentGroup->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">     
        {!! Form::hidden('seo', 1) !!}                      
            
        <div class="form-group">
            {!! Form::label('meta_title', 'Meta title', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('meta_title', null, array('class' => 'form-control', 'data-validate' => 'required,minlength[4],maxlength[60]', 'data-message-required' => 'This field is required')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('meta_description', 'Meta description', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('meta_description', null, array('class' => 'form-control', 'data-validate' => 'required,minlength[4],maxlength[160]', 'data-message-required' => 'This field is required.')) !!}
            </div>
        </div> 

        <div class="form-group">
            {!! Form::label('meta_keywords', 'Meta keywords', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::text('meta_keywords', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This field is required.')) !!}
            </div>
        </div> 

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('content-group.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

</div>


@stop
