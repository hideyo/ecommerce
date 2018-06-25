@if ($product->relatedProductsActive->count())
<div class="row related-products">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<h3>Related products</h3>
	</div>
	@foreach($product->relatedProductsActive as $product)
    @include('frontend.product_category._product-row-item')
	@endforeach
</div>
@endif