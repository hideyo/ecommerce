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

            <li>
                <a href="{!! URL::route('client.addresses.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Adressess</span>
                </a>
            </li>

            <li class="active">
                <a href="{!! URL::route('client.order.index', $client->id) !!}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Orders ({!! $client->Orders()->count() !!})</span>
                </a>
            </li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/admin"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Client</a></li>
            <li><a href="{{ URL::route('client.edit', $client->id) }}">{!! $client->email !!}</a></li>
            <li class="active"><a href="{{ URL::route('client.addresses.index', $client->id) }}">orders</a></li>

        </ol>
          <h2>Client <small>orders</small></h2>
        <hr/>
        @include('flash::message')    
<div class="row">
    <div class="col-md-12">

     

 <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>

                    <th class="col-md-1">{{{ trans('table.id') }}}</th>
                    <th class="col-md-2">{{{ trans('table.created-at') }}}</th>
                    <th class="col-md-1">{{{ trans('table.client') }}}</th>
                    <th class="col-md-2">{{{ trans('table.status') }}}</th>
                    <th class="col-md-1">{{{ trans('table.payment-method') }}}</th>
                    <th class="col-md-1">{{{ trans('table.sending-method') }}}</th>
                    <th class="col-md-1">{{{ trans('table.price') }}}</th>
                    <th class="col-md-2">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                     "ajax": "{{ URL::route('client.order.index', $client->id) }}",


                    columns: [
               
                        {data: 'generated_custom_order_id', name: 'generated_custom_order_id'},
                        {data: 'created_at', name: 'created_at', bSearchable: false},
                        {data: 'client', name: 'client', bSearchable: false},
                        {data: 'status', name: 'status', bVisible: true, bSearchable: false},
                        {data: 'paymentMethod', name: 'paymentMethod', bSearchable: false},
                        {data: 'sendingMethod', name: 'sendingMethod', bSearchable: false},
                        {data: 'price_with_tax', name: 'price_with_tax', bSearchable: false},                        
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    aaSorting: [[0, 'desc']]

                });
            });
        </script>





		
	</div>
</div>
@stop