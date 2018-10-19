@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('error.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('error.create') }}">Create</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('error.index') }}">Errors</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('error.create') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Errors <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr> 
                    <th class="col-md-3">file</th>
                    <th class="col-md-3">message</th>
                    <th class="col-md-3">line</th>
                    <th class="col-md-3">url</th>
                    <th class="col-md-3">status code</th>
                    <th class="col-md-3">method</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "/admin/error",

                 columns: [
                        {data: 'file', name: 'file'},
                        {data: 'message', name: 'message'},
                        {data: 'line', name: 'line'},
                        {data: 'url', name: 'url'},
                        {data: 'status_code', name: 'status_code'},
                        {data: 'method', name: 'method'}
                    ]

                });
            });
        </script>
     
    </div>
</div>   
@stop
