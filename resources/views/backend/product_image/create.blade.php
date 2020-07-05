@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productImage' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Product</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">{!! $product->title !!}</a></li>
            <li><a href="{!! URL::route('product.images.index', $product->id) !!}">images</a></li>
                      <li class="active">upload image</li> 
        </ol>
<a href="{!! URL::route('product.images.index', $product->id) !!}" class="btn btn-green btn-icon icon-left pull-right">back to images<i class="entypo-plus"></i></a>

<h2>Product  <small>images - upload</small></h2>
<hr/>
      @include('flash::message')
<div class="row">
    <div class="col-md-12">
     
        <div class="panel panel-primary tab-content">

            <div class="panel-body">    

                    <div class="col-md-12">


					    {!! Form::open(array('route' => array('product.images.store', $product->id), 'method'=>'POST', 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
						    <input type="hidden" name="_token" value="{!! Session::token() !!}">

					        <div class="form-group">
					            {!! Form::label('file', 'Files (multiple)', array('class' => 'col-sm-3 control-label')) !!}

					            <div class="col-sm-5">
					                {!! Form::file('files[]', array('multiple'=>true), array('class' => 'form-control')) !!}
					            </div>
					        </div>

                            <div class="form-group">
                                {!! Form::label('tag', 'Tag', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('tag', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('rank', 'Rank', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('rank', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>     

                            <div class="form-group">
                                {!! Form::label('productAttributes', 'Product attributes', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::multiselect2('productAttributes[]', $productAttributesList) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('attributes', 'Attributes', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5">
                                    {!! Form::multiselect2('attributes[]', $attributesList) !!}
                                </div>
                            </div>
                                                

					        <div class="form-group">
					            <div class="col-sm-offset-3 col-sm-5">
					                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
					                <a href="{!! URL::route('product.images.store', $product->id) !!}" class="btn btn-large">Cancel</a>
					            </div>
					        </div>

					    {!! Form::close() !!}

					</div>
			</div>

		</div>

	</div>
</div>      

@stop