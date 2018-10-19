@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('payment-method.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('payment-method.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('payment-method.index') }}">Payments methods</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('payment-method.create') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Payments methods <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.id') }}}</th>
                    <th class="col-md-2">{{{ trans('table.title') }}}</th>
                    <th class="col-md-2">{{{ trans('table.orderconfirmed') }}}</th>
                    <th class="col-md-2">{{{ trans('table.paymentcompleted') }}}</th>
                    <th class="col-md-2">{{{ trans('table.paymentfailed') }}}</th>
                    <th class="col-md-2">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('payment-method.index') }}",

                 columns: [
                        {data: 'id', name: 'id'},
                        {data: 'title', name: 'title'},
                        {data: 'orderconfirmed', name: 'orderconfirmed', orderable: false, searchable: false},
                        {data: 'paymentcompleted', name: 'paymentcompleted', orderable: false, searchable: false},
                        {data: 'paymentfailed', name: 'paymentfailed', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
     
    </div>
</div>   
@stop