@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{{ URL::route('sending-method.index') }}">Back</a></li>
            
            <li class="active"><a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}">Overview</a></li>
            <li><a href="{{ URL::route('sending-method.country-prices.create', $sendingMethod->id) }}">Create</a></li>
            <li><a href="{{ URL::route('sending-method.import', $sendingMethod->id) }}">Import</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('sending-method.index') }}">Sending methods</a></li> 
            <li><a href="{{ URL::route('sending-method.country-prices.index', $sendingMethod->id) }}">{!! $sendingMethod->title !!}</a></li>  
            <li>Country prices</li>
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('sending-method.country-prices.create', $sendingMethod->id) }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Sending method country prices <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.name') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "/admin/sending-method/{!! $sendingMethod->id !!}/country-prices",

                 columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
     
    </div>
</div>   
@stop