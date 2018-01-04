@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productCombination' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		<ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Product</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">{!! $product->title !!}</a></li>
            <li class="active">combinations</li>
		</ol>

		<a href="{{ URL::route('product-combination.create', $product->id) }}" class="btn btn-green btn-success pull-right">create combination<i class="entypo-plus"></i></a>

		<h2>Product <small>combinations</small></h2>
        <hr/>
        {!! Notification::showAll() !!}


        {!! Form::model($product, array('method' => 'put', 'route' => array('product.update', $product->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">     
        {!! Form::hidden('product-combination', 1) !!}                      
            
        <div class="form-group">
            {!! Form::label('leading_atrribute_group_id', 'Leading attribute group', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                    {!! Form::select('leading_atrribute_group_id', array('' => 'geen') + $attributeGroups->toArray(), null, array('class' => 'attribute_group_id form-control')) !!}
                                
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
            </div>
        </div>

        {!! Form::close() !!}
        <hr/>

        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-1">{{{ trans('table.amount') }}}</th>
                    <th class="col-md-3">{{{ trans('table.price') }}}</th>
                    <th class="col-md-1">{{{ trans('table.reference-code') }}}</th>
                    <th class="col-md-3">{{{ trans('table.combinations') }}}</th>
                    <th class="col-md-2">{{{ trans('table.default-on') }}}</th>
                    <th class="col-md-2">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('product-combination.index', $product->id) }}",

                 columns: [

                        {data: 'amount', name: 'amount'},
                        {data: 'price', name: 'price', bSearchable: false},
                        {data: 'reference_code', name: 'reference_code'},
                        {data: 'combinations', name: 'combinations'},
                        {data: 'default_on', name: 'default_on'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
	</div>
</div>
@stop