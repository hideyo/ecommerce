@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productRelated' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">

		<ol class="breadcrumb">
		  <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
		  <li><a href="{{ URL::route('product.index') }}">Product</a></li>
		  <li><a href="{{ URL::route('product.edit', $product->id) }}">{{ $product->title }}</a></li>
		  <li class="active">related</li>
		</ol>

		<a href="{{ URL::route('product.related-product.create', $product->id) }}" class="btn btn-success pull-right">select related product<i class="entypo-plus"></i></a>

		<h2>Product <small>related products</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.related') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
            "ajax": "{{ URL::route('product.related-product.index', $product->id) }}",

                 columns: [
                        {data: 'id', name: 'id'},
                        {data: 'related', name: 'related'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>


	</div>
</div>
@stop