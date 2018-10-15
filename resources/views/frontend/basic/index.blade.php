@extends('frontend._layouts.default')
@section('meta_title', $shop->meta_title)
@section('meta_description', $shop->meta_description)
@section('main')

<div class="homepage-highlights">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<a href="/pants" title="pants">
				<div class="highlight homepage-highlight-big">
					<h3>Pants</h3>
				</div>
			</a>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
			<a href="/dresses" title="dresses">
				<div class="highlight homepage-highlight-small">
					<h3>Dresses</h3>	
				</div>
			</a>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
			<a href="/hats" title="dresses">
				<div class="highlight homepage-highlight-small2">
					<h3>Hats</h3>
				</div>
			</a>
		</div>


	</div>
</div>

@if($populairProducts)

<div class="populair-products">	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		    <h2>Populair products</h2>
		</div>

	    @foreach ($populairProducts as $product)
	    @include('frontend.product_category._product-row-item')
	    @endforeach
	</div>
</div>

@endif

@stop