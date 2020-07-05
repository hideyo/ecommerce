@extends('_layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('inventory.index') }}">Overview <span class="sr-only">(current)</span></a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('inventory.index') }}">Inventory</a></li>  
            <li class="active">overview</li>
        </ol>

        <h2>Inventory <small>overview</small></h2>
        <hr/>
        @include('flash::message')

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.active') }}}</th>
                    <th class="col-md-1">{{{ trans('table.amount') }}}</th>
                    <th class="col-md-3">{{{ trans('table.title') }}}</th>
                    <th class="col-md-3">{{{ trans('table.combinations') }}}</th>
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
                "ajax": "/inventory",

                columns: [
                {data: 'active', name: 'active'},
                {data: 'amount', name: 'amount', bVisible: true, bSearchable: false},                     
                {data: 'title', name: 'title'},
                {data: 'combinations', name: 'combinations'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
                ]

            });
        });
        </script>
        
    </div>
</div>   
@stop