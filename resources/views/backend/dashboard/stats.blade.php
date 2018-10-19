@extends('backend._layouts.default')

@section('main')



<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard <span class="sr-only">(current)</span></a></li>
            <li  class="active"><a href="/admin/dashboard/stats"><i class="entypo-folder"></i>Stats</a></li>


        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href=""><i class="entypo-folder"></i>Stats</a></li>
        </ol>
        {!! Notification::showAll() !!}
    </div>
</div>
@stop