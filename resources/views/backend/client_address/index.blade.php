@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li ><a href="{{ URL::route('client.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li>
                <a href="{{ URL::route('client.edit', $client->id) }}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Edit</span>
                </a>
            </li>

            <li class="active">
                <a href="{!! URL::route('client.addresses.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Adressess</span>
                </a>
            </li>

            <li>
                <a href="{!! URL::route('client.order.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Orders</span>
                </a>
            </li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/admin"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Client</a></li>
            <li><a href="{{ URL::route('client.edit', $client->id) }}">{!! $client->email !!}</a></li>
            <li class="active"><a href="{{ URL::route('client.addresses.index', $client->id) }}">addresses</a></li>

        </ol>

        <a href="{{ URL::route('client.addresses.create', $client->id) }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>


          <h2>Client <small>addresses</small></h2>
        <hr/>
        {!! Notification::showAll() !!}    
<div class="row">
    <div class="col-md-12">

     

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-2">{{{ trans('table.firstname') }}}</th>
                    <th class="col-md-2">{{{ trans('table.lastname') }}}</th>
                    <th class="col-md-2">{{{ trans('table.street') }}}</th>
                    <th class="col-md-2">{{{ trans('table.housenumber') }}}</th>
                    <th class="col-md-2">{{{ trans('table.delivery-address') }}}</th>
                    <th class="col-md-3">{{{ trans('table.bill-address') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('client.addresses.index', $client->id) }}",

                 columns: [
                        {data: 'firstname', name: 'firstname'},
                        {data: 'lastname', name: 'lastname'},
                        {data: 'street', name: 'street'},
                        {data: 'housenumber', name: 'housenumber'},
                        {data: 'delivery', name: 'delivery', orderable: false, searchable: false},
                        {data: 'bill', name: 'bill', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>


		
	</div>
</div>
@stop