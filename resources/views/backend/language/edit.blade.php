@extends('_layouts.default')

@section('main')

<ol class="breadcrumb">
    <li><a href="{{ URL::route('index') }}"><i class="entypo-folder"></i>Dashboard</a></li>
    <li><a href="{{ URL::route('language.index') }}">Tax rate</a></li>
    <li><a href="#">{{ $language->title }}</a></li>
    <li class="active">edit</li>
</ol>

<a href="{{ URL::route('language.index') }}" class="btn btn-green btn-icon icon-left pull-right">Back to overview<i class="entypo-back"></i></a>

<h2>Language <small>edit</small></h2>
<hr/>
@include('_partials.notifications')

<div class="panel panel-primary" data-collapsed="0">

    <div class="panel-body">

        {{ Form::model($language, array('method' => 'put', 'route' => array('language.update', $language->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) }}
     	<div class="form-group">   
            {{ Form::label('language', 'Language', array('class' => 'col-sm-3 control-label')) }}

            <div class="col-sm-5">
                {{ Form::text('language', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
                <a href="{{ URL::route('language.index') }}" class="btn btn-large">Cancel</a>
            </div>
        </div>
        {{ Form::close() }}

    </div>

</div>
@stop
