@extends('frontend._layouts.default')

@if($product['meta_title'])
@section('meta_title', $product['meta_title'])
@else
@section('meta_title', $product->title)
@endif

@if($product['meta_description'])
@section('meta_description', $product['meta_description'])
@else
@section('meta_description', $product->short_description)
@endif

@section('meta_keywords', $product['meta_keywords'])

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ol class="breadcrumb">
            <li class="show-for-medium"><a href="/">Home</a></li>
            @if($product->productCategory->ancestors()->count())
            @foreach ($product->productCategory->ancestors()->get() as $anchestor)
            <li><a href="{!! URL::route('product-category.item', $anchestor->slug) !!}">{{ $anchestor->title }}</a></li>
            @endforeach          
            @endif
            <li><a href="{!! URL::route('product-category.item', $product->productCategory->slug) !!}">{{ $product->productCategory->title }}</a></li>
            <li><a href="#">{{ $product->title }}</a></li>
        </ol>
    </div>
</div>


<div class="row product product-container">

    <div class="col-sm-5 col-md-5 col-lg-5">

        @include('frontend.product._images')
    </div>

    <div class="col-sm-6 col-md-6 col-lg-6 col-lg-offset-1">
        <h1>{!! $product->title !!}</h1>
        <hr/>
      

        <h3 class="price">
            @if($priceDetails['discount_price_inc'])                        
            &euro; {!! $priceDetails['discount_price_inc_number_format'] !!}
            <span>&euro; {!! $priceDetails['original_price_inc_tax_number_format'] !!}</span>
            @else 
            &euro; {!! $priceDetails['original_price_inc_tax_number_format'] !!}
            @endif         
        </h3>
        
        <div class="description">
            <p>{!! $product->short_description !!}</p>
        </div>
        
        <div class="order-block">
            <hr/>
            @if($priceDetails['amount'] <= 0)
                <div class="button-group">
                    <button type="button" class="button btn add-to-cart-button" disabled="disabled">
                    Uitverkocht
                    </button>
                </div>
            @else
            {!! Form::open(array('route' => array('cart.add.product', $product['id']), 'class' => 'add-product')) !!}
                <input type="hidden" name="product_id" value="{!! $product['id'] !!}"> 
                @if($product->amountSeries()->where('active', '=', '1')->count())
                <input type="hidden" name="product_amount_series" value="1"> 
                {!! Form::select('amount', $product->amountSeries()->where('active', '=', '1')->first()->range(), null, array('class' => 'form-control')) !!}
                @else
                <input type="hidden" class="form-control"  name="amount" value="1" size="2" maxlength="2" >
                
                @endif
                <button type="button" class="btn btn-success add-to-cart-button btn-blue btn-long">
                    <span class="icon icon-cart"></span> Add to cart
                </button>
                @endif
            </form>

        </div>

        <hr/>
        <div class="description">
            {!! $product->description !!}
        </div>

    </div>
</div>


@include('frontend.product.related-products')

@stop