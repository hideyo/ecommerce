@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('client.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('client.create') }}">Create</a></li>
            <li><a href="{{ URL::route('client.export') }}">Export</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('client.index') }}">Clients</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('client.create') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Clients <small>overview</small></h2>
        <hr/>
        @include('flash::message')

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.id') }}}</th>
                    <th class="col-md-1">{{{ trans('table.active') }}}</th>
                    <th class="col-md-1">{{{ trans('table.confirmed') }}}</th>
                    <th class="col-md-1">{{{ trans('table.company') }}}</th>
                    <th class="col-md-1">{{{ trans('table.email') }}}</th>
                    <th class="col-md-1">{{{ trans('table.vat-number') }}}</th>
                    <th class="col-md-1">{{{ trans('table.iban-number') }}}</th>
                    <th class="col-md-2">{{{ trans('table.chamber-of-commerce-number') }}}</th>
                    <th class="col-md-3">{{{ trans('table.last_login') }}}</th>
                    <th class="col-md-4">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "/admin/client",

                 columns: [
                 {data: 'id', name: 'id'},
                        {data: 'active', name: 'active'},
                        {data: 'confirmed', name: 'confirmed'},
                        {data: 'company', name: 'company'},
                        {data: 'email', name: 'email'},
                        {data: 'vat_number', name: 'vat_number'},
                        {data: 'iban_number', name: 'iban_number'},
                        {data: 'chamber_of_commerce_number', name: 'chamber_of_commerce_number'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                aaSorting: [[0, 'desc']]

                });
            });
        </script>
   
@stop