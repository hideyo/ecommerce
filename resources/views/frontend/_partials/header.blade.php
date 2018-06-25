<header><!-- set fixed position by adding class "navbar-fixed-top" -->

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" title="back to homepage">                   
                    <img src="/images/hideyo.png" />
                </a>

            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav ">
                      @foreach($frontendProductCategories as $productCategory)
                      <li><a href="{!! URL::route('product-category.item', $productCategory->slug) !!}" title="{!! $productCategory->title !!}">{!! $productCategory->title !!}</a></li>
                      @endforeach
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/account" title="account">Account</a></li>
                    @if (Auth::guard('web')->check())
                    <li class="logout">
                        <a href="/account/logout" title="logout">logout</a>
                    </li>
                    @endif

                    <li>
                        <a href="#" id="cart">
                            <i class="glyphicon glyphicon-shopping-cart cart-button"></i><span class="cart-count">{!! Cart::getContent()->count() !!}</span> items
                        </a>
                    </li> 
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="shopping-cart cart-dialog  " data-url="/cart/dialog">              
        @include('frontend.cart.basket-dialog')
    </div> <!--end shopping-cart -->

</header>