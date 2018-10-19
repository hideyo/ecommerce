@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-category-tabs', array('productCategoryImages' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('product-category.index') !!}">Product categories</a></li>  
            <li><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">{!! $productCategory->title !!}</a></li>
            <li class="active">images</li>           
        </ol>

        <a href="{{ URL::route('product-category.images.create', $productCategory->id) }}" class="btn btn-success pull-right">upload image<i class="entypo-plus"></i></a>

        <h2>Productcategory <small>images</small></h2>
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
                   "ajax": "{{ URL::route('product-category.images.index', $productCategory->id) }}",

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