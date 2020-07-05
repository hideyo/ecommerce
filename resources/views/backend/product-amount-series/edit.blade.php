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
			<li><a href="#">amount series</a></li>
			<li class="active">create product amount series</li> 
		</ol>
		<a href="{!! URL::route('product.product-amount-series.index', $product->id) !!}" class="btn btn-green btn-icon icon-left pull-right">back to overview<i class="entypo-plus"></i></a>

		<h2>Product amount series  <small>edit</small></h2>
		<hr/>
		@include('flash::message')
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-primary tab-content">

					<div class="panel-body">    

						<div class="col-md-12">

							{!! Form::model($productAmountSeries, array('method' => 'put', 'route' => array('product.product-amount-series.update', $product->id, $productAmountSeries->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

							<input type="hidden" name="_token" value="{!! Session::token() !!}">

				            <div class="form-group">
				                {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
				                <div class="col-sm-5">
				                    {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
				                </div>
				            </div>
					
    				        <div class="form-group">
					            {!! Form::label('series_value', 'Series value', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('series_value', null, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

    				        <div class="form-group">
					            {!! Form::label('series_start', 'Series start', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('series_start', null, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

    				        <div class="form-group">
					            {!! Form::label('series_max', 'Series max', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('series_max', null, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-5">
									{!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
									<a href="{!! URL::route('product.product-amount-series.index', $product->id) !!}" class="btn btn-large">Cancel</a>
								</div>
							</div>

							{!! Form::close() !!}

						</div>
					</div>

				</div>

			</div>
		</div>      

		@stop