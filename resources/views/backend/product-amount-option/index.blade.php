@extends('backend._layouts.default')

@section('main')

<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productAmountOption' => true))
    </div>
    <div class="col-sm-9 col-md-10 main">

		<ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Product</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">{!! $product->title !!}</a></li>
            <li class="active">amount options</li>
		</ol>

		<a href="{{ URL::route('product.{productId}.product-amount-option.create', $product->id) }}" class="btn btn-green btn-success pull-right">create amount option<i class="entypo-plus"></i></a>

		<h2>Product <small>amount options</small></h2>
        <hr/>
        {!! Notification::showAll() !!}


        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">{{{ trans('table.id') }}}</th>
                    <th class="col-md-3">{{{ trans('table.amount') }}}</th>
                    <th class="col-md-3">{{{ trans('table.default_on') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('product.{productId}.product-amount-option.index', $product->id) }}",

                 columns: [
                        {data: 'id', name: 'id'},
                        {data: 'amount', name: 'amount'},
                        {data: 'default_on', name: 'default_on'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
	</div>
</div>
@stop