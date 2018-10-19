@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.news-tabs', array('newsEditSeo' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('news.index') !!}">News</a></li>
            <li><a href="{!! URL::route('news.edit', $news->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('news.edit', $news->id) !!}">{!! $news->title !!}</a></li>
            <li class="active">seo</li>
        </ol>

        <h2>News <small>edit seo</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        {!! Form::model($news, array('method' => 'put', 'route' => array('news.update', $news->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">     
        {!! Form::hidden('seo', 1) !!}                      
            
        @include('backend._fields.seo-fields')

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('news.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

</div>


@stop
