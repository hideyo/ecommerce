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
            <li><a href="{!! URL::route('product.images.index', $product->id) !!}">combinations</a></li>
                      <li class="active">create combination</li> 
        </ol>
<a href="{!! URL::route('product-combination.index', $product->id) !!}" class="btn btn-danger btn-icon icon-left pull-right">back to overview<i class="entypo-plus"></i></a>

<h2>Product combinations  <small>create</small></h2>
<hr/>
      @include('flash::message')
<div class="row">
    <div class="col-md-12">
     
        <div class="panel panel-primary tab-content">

            <div class="panel-body">    

                    <div class="col-md-12">

					    {!! Form::open(array('route' => array('product-combination.store', $product->id), 'method'=>'POST', 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
						    <input type="hidden" name="_token" value="{!! Session::token() !!}">
						    <input type="hidden" name="default_attribute_group_id" class="default_attribute_group_id" value="{!! key($attributeGroups->toArray()) !!}">
					        <div class="form-group">
					            {!! Form::label('attribute_group_id', 'Attribute group', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::select('attribute_group_id', $attributeGroups, null, array('class' => 'attribute_group_id form-control')) !!}
					            </div>
					        </div>

        					<div class="form-group">
					            {!! Form::label('attribute_id', 'Attribute', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-4">
					                {!! Form::select('attribute_id', array(), null, array('class' => 'attribute_id form-control')) !!}
					            </div>
					            <div class="col-sm-2">
					                {!! Form::button('toevoegen',  array('class' => 'add_attribute_id btn btn-success form-control')) !!}
					            </div>
					        </div>                    

        					<div class="form-group">
					            {!! Form::label('attribute_id', 'Combination', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-4">
					                {!! Form::select('selected_attribute_ids[]', array(), null, array('multiple' => 'multiple',  'class' => 'selected_attribute_ids form-control')) !!}
					            </div>
					            <div class="col-sm-2">
					                {!! Form::button('verwijder',  array('class' => 'remove_attribute_id btn btn-danger form-control')) !!}
					            </div>
					        </div>  

					        <div class="form-group">
					            {!! Form::label('reference_code', 'Reference code', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('reference_code', null, array('class' => 'form-control')) !!}
					            </div>
					        </div>

					        <div class="form-group">
					            {!! Form::label('tax_rate_id', 'Tax rate', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::select('tax_rate_id', $taxRates, null, array('class' => 'tax-rate form-control')) !!}
					            </div>
					        </div>

					        <div class="form-group">
					            {!! Form::label('price', 'Price without tax', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('price', null, array('class' => 'price form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

					        <div class="form-group">
					            {!! Form::label('price_inc_tax', 'Price with tax', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('price_inc_tax', null, array('class' => 'price_inc_tax form-control', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

					        <div class="form-group">
					            {!! Form::label('amount', 'Amount', array('class' => 'col-sm-3 control-label')) !!}
					            <div class="col-sm-5">
					                {!! Form::text('amount', null, array('class' => 'form-control', 'required' => 'required', 'data-sign' => '&euro;')) !!}
					            </div>
					        </div>

	        				<div class="form-group">
					            {!! Form::label('default_on', 'Standard combination to show', array('class' => 'col-sm-3 control-label')) !!}
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
            				
            				@include('backend._fields.buttons', array('cancelRoute' => 'product-combination.index', 'cancelRouteParameters' => $product->id))


					    	{!! Form::close() !!}
					        <script type="text/javascript">
					            $(document).ready(function() {
									$select = $('.attribute_id');
									var hasBeenClicked = false;
					
									$( ".attribute_group_id" ).on( "change", function() {
								    hasBeenClicked = true;
								    $.getJSON( "{{ URL::route('product-combination.create', $product->id) }}", { attribute_group_id: this.value } )
								        .done(function( data ) {

											//clear the current content of the select
											$select.html('');
											//iterate over the data and append a select option
											$select.append('<option value="">---</option>');
											$.each(data, function(key, val){
												$select.append('<option value="' + val.id + '">' + val.value + '</option>');
											})
										});
									});

								    if (!hasBeenClicked) {

 										var defaultValue = $( ".default_attribute_group_id" ).val();
	
		    							$.getJSON( "{{ URL::route('product-combination.create', $product->id) }}", { attribute_group_id: defaultValue } )
								        .done(function( data ) {
											//clear the current content of the select
											$select.html('');
											//iterate over the data and append a select option
											$select.append('<option value="">---</option>');
											$.each(data, function(key, val){
												$select.append('<option value="' + val.id + '">' + val.value + '</option>');
											})
										});
								    }

									$( ".add_attribute_id" ).on( "click", function() {
										var text = $( ".attribute_id  option:selected" ).text();
										var value = $( ".attribute_id  option:selected" ).val();
										var groupId = $( ".attribute_group_id  option:selected" ).val();
										if(value) {
											$selected = $('.selected_attribute_ids');
											if ( $(".selected_attribute_ids option[value=" + value  + "]").length == 0 ){
												if ( $(".selected_attribute_ids option[id=" + groupId  + "]").length == 0 ){
													$selected.append('<option id="' + groupId + '" value="' + value + '" selected="selected">' + text + '</option>');
													                  $('.selected_attribute_ids option').prop('selected', true);
												}
											}	
										}							
	    							});

									$( ".remove_attribute_id" ).on( "click", function() {
										var selected = $( ".selected_attribute_ids  option:selected" ).val();
										if(selected) {
											$('.selected_attribute_ids option[value="' + selected + '"]').remove();
											                  $('.selected_attribute_ids option').prop('selected', true);
										}
									});
					   			});
					    	</script>
					</div>
			</div>

		</div>

	</div>
</div>      

@stop