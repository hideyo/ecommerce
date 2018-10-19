@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('extra-field.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{!! URL::route('extra-field.edit', $extraField->id) !!}">Edit</a></li>
            <li class="active"><a href="{!! URL::route('extra-field.values.index', $extraField->id) !!}">Values</a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('extra-field.index') !!}">Extra fields</a></li>  
            <li><a href="{!! URL::route('extra-field.edit', $extraField->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('extra-field.values.index', $extraField->id) !!}">{!! $extraField->title !!}</a></li>
            <li class="active">values</li>  
        </ol>

        <a href="{!! URL::route('extra-field.values.create', $extraField->id) !!}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Extra fields values <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.value') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ URL::route('extra-field.values.index', $extraField->id ) }}",

           

                 columns: [
                        {data: 'id', name: 'id'},
                        {data: 'value', name: 'value'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
     
    </div>
</div>   
@stop