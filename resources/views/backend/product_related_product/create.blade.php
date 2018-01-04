@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productRelated' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  

           

	    {!! Form::open(array('route' => array('product.related-product.store', $product->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}


	        <div class="form-group">
	            {!! Form::label('products', 'Products', array('class' => 'col-sm-3 control-label')) !!}
	            <div class="col-sm-5">
	                {!! Form::multiselect2('products[]', $products->toArray()) !!}
	            </div>
	        </div>

	        <div class="form-group">
	            <div class="col-sm-offset-3 col-sm-5">
	                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
	                <a href="{!! URL::route('product.related-product.store', $product->id) !!}" class="btn btn-large">Cancel</a>
	            </div>
	        </div>

	    {!! Form::close() !!}

	</div>
</div>


@stop