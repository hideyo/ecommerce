@extends('frontend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="#">Cart</a></li>
        </ol>
    </div>
</div>

@notification()

@if (app('cart')->getContent()->count())

<div class="main-cart">
    <h1>Shoppingcart</h1>
    <div class="cart-details cart-details-mobile">

        <table class="table details">
            <thead>
                <tr>
                    <th class="show-for-medium image">&nbsp;</th>
                    <th class="title ">Product</th>         
                    <th class="amount">Amount</th>
                    <th class="price text-right">Total</th>
                     
                </tr>
            </thead>

            <tbody class="cart-details-container">

                @foreach (app('cart')->getContent()->sortBy('id') as $product)
                @include('frontend.cart._product-row-mobile')                      
                @endforeach

                <tfoot class="cart-reload" data-url="{!! URL::route('cart.total-reload') !!}" >
                    @include('frontend.cart._totals-mobile')
                </tfoot>

            </tbody>
        </table>

    </div>

</div>

@endif

@stop