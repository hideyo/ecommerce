@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('product-category.index') }}">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="{{ URL::route('product-category.tree') }}">Tree</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('product-category.index') }}">Product categories</a></li>  
            <li class="active">overview</li>
        </ol>

        <a href="{{ URL::route('product-category.create') }}" class="btn btn-success pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</a>

        <h2>Product categories <small>overview</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.active') }}}</th>
                    <th class="col-md-1">{{{ trans('table.image') }}}</th>
                    <th class="col-md-1">{{{ trans('table.products') }}}</th>
                    <th class="col-md-2">{{{ trans('table.parent') }}}</th>                    
                    <th class="col-md-2">{{{ trans('table.redirect') }}}</th>
                    <th class="col-md-3">{{{ trans('table.title') }}}</th>
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
                    "ajax": "{{ URL::route('product-category.index') }}",
                    columns: [
                        {data: 'active', name: 'active'},
                        {data: 'image', name: 'image', orderable: false, bVisible: true, bSearchable: false},
                        {data: 'products', name: 'products', orderable: false, bVisible: true, bSearchable: false},
                        {data: 'parent', orderable: false, name: 'parent', bVisible: true, bSearchable: false},
                        {data: 'redirect_product_category_id', name: 'redirect_product_category_id', bSearchable: false},                    
                        {data: 'title', name: 'title'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            });
        </script>

    </div>
</div>   
@stop