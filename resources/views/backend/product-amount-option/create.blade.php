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
            <li><a href="{!! URL::route('product.{productId}.images.index', $product->id) !!}">product amount options</a></li>
                      <li class="active">create combination</li> 
        </ol>
<a href="{!! URL::route('product.{productId}.product-amount-option.index', $product->id) !!}" class="btn btn-green btn-icon icon-left pull-right">back to overview<i class="entypo-plus"></i></a>

<h2>Product amount options  <small>create</small></h2>
<hr/>
      {!! Notification::showAll() !!}
<div class="row rowTopPadding">
    <div class="col-md-12">
     
        <div class="panel panel-primary tab-content">

            <div class="panel-body">    

                    <div class="col-md-12">

					    {!! Form::open(array('route' => array('product.{productId}.product-amount-option.store', $product->id), 'method'=>'POST', 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
						    <input type="hidden" name="_token" value="{!! Session::token() !!}">
					
    				        <div class="form-group">
					            {!! Form::label('amount', 'Amount', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('amount', null, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

	        				<div class="form-group">
					            {!! Form::label('default_on', 'Standard to show', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::select('default_on', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
					            </div>
					        </div>

				            <div class="form-group">
				                {!! Form::label('discount_type', 'Discount type', array('class' => 'col-sm-3 control-label')) !!}
				                <div class="col-sm-5">
				                    {!! Form::select('discount_type', array('amount' => 'Amount','percent' => 'Percent'), null, array('class' => 'tax-rate form-control')) !!}
				                </div>
				            </div>


				            <div class="form-group">
				                {!! Form::label('discount_value', 'Discount value', array('class' => 'col-sm-3 control-label')) !!}
				                <div class="col-sm-5">
				                    {!! Form::text('discount_value', null, array('class' => 'form-control', 'data-validate' => 'number', 'data-sign' => '&euro;')) !!}
				                </div>
				            </div>

				            <div class="form-group">
				                {!! Form::label('discount_start_date', 'Discount start date', array('class' => 'col-sm-3 control-label')) !!}
				                <div class="col-sm-5">
				                    {!! Form::text('discount_start_date', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
				                </div>
				            </div>

				            <div class="form-group">
				                {!! Form::label('discount_end_date', 'Discount end date', array('class' => 'col-sm-3 control-label')) !!}
				                <div class="col-sm-5">
				                    {!! Form::text('discount_end_date', null, array('class' => 'datepicker form-control', 'data-sign' => '&euro;')) !!}
				                </div>
				            </div>

					        <div class="form-group">
					            <div class="col-sm-offset-3 col-sm-5">
					                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
					                <a href="{!! URL::route('product.{productId}.product-amount-option.index', $product->id) !!}" class="btn btn-large">Cancel</a>
					            </div>
					        </div>

					    	{!! Form::close() !!}
					       
					    	</script>
					</div>
			</div>

		</div>

	</div>
</div>      

@stop