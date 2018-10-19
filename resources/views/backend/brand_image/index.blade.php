@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.brand-tabs', array('brandImages' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('brand.index') !!}">Brand</a></li>  
            <li><a href="{!! URL::route('brand.edit', $brand->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('brand.edit', $brand->id) !!}">{!! $brand->title !!}</a></li>
            <li class="active">images</li>           
        </ol>

        <a href="{{ URL::route('brand.images.create', $brand->id) }}" class="btn btn-success pull-right">upload image<i class="entypo-plus"></i></a>

        <h2>Brand <small>images</small></h2>
        <hr/>
        {!! Notification::showAll() !!}
			
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.file') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('brand.images.index', $brand->id) }}",

                 columns: [
              {data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                        {data: 'file', name: 'file'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
    </div>
</div>
@stop