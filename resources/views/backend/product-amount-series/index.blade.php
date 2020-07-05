@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productAmountSeries' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		<ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Product</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">{!! $product->title !!}</a></li>
            <li class="active">amount series</li>
		</ol>

		<h2>Product <small>amount series</small></h2>
        <hr/>
        @include('flash::message')


        <h3>Create Quick</h3>

                        {!! Form::open(array('route' => array('product.product-amount-series.store', $product->id), 'method'=>'POST', 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
                            <input type="hidden" name="_token" value="{!! Session::token() !!}">

                            <input type="hidden" name="active" value="1">

                    
                            <div class="form-group">
                                {!! Form::label('series_value', 'Series value', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('series_value', array('3' => '3', '6' => '6', '12' => '12', '18' => '18', '21' => '21'), null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('series_start', 'Series start', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('series_start', array('3' => '3', '6' => '6', '12' => '12', '18' => '18', '21' => '21'), null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('series_max', 'Series max', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('series_max', 200, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-5">
                                    {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                                    <a href="{!! URL::route('product.product-amount-series.index', $product->id) !!}" class="btn btn-large">Cancel</a>
                                </div>
                            </div>

                            {!! Form::close() !!}

        <hr/>
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="col-md-2">{{{ trans('table.active') }}}</th>
                    <th class="col-md-2">{{{ trans('table.series_value') }}}</th>
                    <th class="col-md-3">{{{ trans('table.series_start') }}}</th>
                    <th class="col-md-3">{{{ trans('table.series_max') }}}</th>
                    <th class="col-md-3">{{{ trans('table.actions') }}}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                oTable = $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                   "ajax": "{{ URL::route('product.product-amount-series.index', $product->id) }}",

                 columns: [
                        {data: 'active', name: 'active'},
                        {data: 'series_value', name: 'series_value'},
                        {data: 'series_start', name: 'series_start'},
                        {data: 'series_max', name: 'series_max'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]

                });
            });
        </script>
	</div>
</div>
@stop