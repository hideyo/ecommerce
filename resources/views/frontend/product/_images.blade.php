@if ($productImages)
<div class="photos photo-container">
    <div class="row">
        @foreach ($productImages as $key => $image)
        @if ($key === 0)
 
        <div class="col-sm-12 col-md-12 col-lg-12 large-photo">
            <a href="/files/product/800x800/{!! $image['product_id'] !!}/{!! $image['file'] !!}" data-toggle="lightbox"  data-gallery="example-gallery">
                <img src="/files/product/800x800/{!! $image['product_id'] !!}/{!! $image['file'] !!}" class="img-responsive img-fluid main-photo" alt="{!! $image['file'] !!}" />
            </a>
        </div>             

        @else
        <div class="col-sm-12 col-md-12 col-lg-12 small-photo">
            <a href="/files/product/800x800/{!! $image['product_id'] !!}/{!! $image['file'] !!}" data-toggle="lightbox"  data-gallery="example-gallery">
                <img src="/files/product/100x100/{!! $image['product_id'] !!}/{!! $image['file'] !!}" class="img-fluid" alt="{!! $image['file'] !!}" />
            </a>
        </div>

        @endif
        @endforeach
    </div>
</div>
@else
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <img src="{!! URL::asset('images/product-thumb2.jpg') !!}" alt="no image" class="img-responsive">
    </div>
</div>
@endif