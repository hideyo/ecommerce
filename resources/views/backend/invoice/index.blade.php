@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('invoice.index') }}">Overview <span class="sr-only">(current)</span></a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('invoice.index') }}">Invoices</a></li>  
            <li class="active">overview</li>
        </ol>

        <h2>Invoices <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.order-id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.price') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "/admin/invoice",

                    columns: [
                        {data: 'id', name: 'id'}, 
                        {data: 'generated_custom_invoice_id', name: 'generated_custom_invoice_id'},
                        {data: 'order_id', name: 'order_id'},   
                        {data: 'price_with_tax', name: 'price_with_tax'},                        
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    aaSorting: [[0, 'desc']]

                });
            });
        </script>
     
    </div>
</div>   
@stop