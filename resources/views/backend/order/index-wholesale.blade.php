@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('order.index') }}">Overview <span class="sr-only">(current)</span></a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('order.index') }}">Orders</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('order.print') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Print</a>

        <h2>Order <small>overview</small></h2>
        <hr/>
        @include('flash::message')
        @if($revenueThisMonth)
        <div class="row">
            <div class="col-md-12">
                <p>Revenue this month: <strong>&euro; {!! number_format($revenueThisMonth[0]->price_with_tax, 2, '.', '') !!}</strong></p>
            </div>
        </div>
        <hr/>
        @endif

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>

                    <th class="col-md-1">{{{ trans('table.id') }}}</th>
                    <th class="col-md-1">{{{ trans('table.created-at') }}}</th>
                    <th class="col-md-2">{{{ trans('table.client') }}}</th>
                    <th class="col-md-1">{{{ trans('table.company') }}}</th>
                    <th class="col-md-2">{{{ trans('table.status') }}}</th>
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
                    "stateSave": true,
                    "ajax": "/admin/order",

                    columns: [
               
                        {data: 'generated_custom_order_id', name: 'generated_custom_order_id'},
                        {data: 'created_at', name: 'created_at', bSearchable: false},
                        {data: 'client', name: 'client', orderable: false},
                        {data: 'company', name: 'company', orderable: false},
                        {data: 'status', name: 'status', bVisible: true, bSearchable: false, orderable: false}, 

                        {data: 'price_with_tax', name: 'price_with_tax', bSearchable: false, orderable: false},                        
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    aaSorting: [[1, 'desc']]

                });
            });
        </script>
   </div>
</div>
@stop