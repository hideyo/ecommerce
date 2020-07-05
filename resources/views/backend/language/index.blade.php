@extends('_layouts.default')

@section('main')

<ol class="breadcrumb">
	<li><a href="{{ URL::route('index') }}"><i class="entypo-folder"></i>Dashboard</a></li>
	<li><a href="{{ URL::route('language.index') }}">Language</a></li>  
	<li class="active">overview</li>
</ol>

<a href="{{ URL::route('language.create') }}" class="btn btn-green btn-icon icon-left pull-right">Create tax<i class="entypo-plus"></i></a>

<h2>Language <small>overview</small></h2>
<br/>
@include('flash::message')

{{ Datatable::table()
->addColumn('id','language', 'actions')       // these are the column headings to be shown
->setUrl(route('language.index'))   // this is the route where data will be retrieved

->render() }}
   
@stop