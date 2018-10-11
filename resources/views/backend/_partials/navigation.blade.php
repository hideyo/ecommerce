<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/admin">Backend</a>

			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{!! $this_user->shop->title !!} <span class="caret"></span></a>
					<ul class="dropdown-menu">
						@foreach($available_shops as $shop)
						<li><a href="{{ URL::route('change.language.profile', array('shopId' => $shop->id)) }}">{!! $shop->title !!}</a></li>
						@endforeach
					</ul>
				</li>
			</ul>
			
		</div>

		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{{ URL::route('shop.index') }}">Shops</a></li>
				<li><a href="{{ URL::route('client.index') }}">Clients</a></li>     

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Orders <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ URL::route('order.index') }}">Overview</a></li>
						<li><a href="{{ URL::route('order-status.index') }}">Statuses</a></li>
						<li><a href="{{ URL::route('order-status-email-template.index') }}">Status email templates</a></li>
					</ul>
				</li>                        
		
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Coupons <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ URL::route('coupon.index') }}">Coupons</a></li>
						<li><a href="{{ URL::route('coupon-group.index') }}">Groups</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catalog <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ URL::route('product.index') }}">Products</a></li>
						<li><a href="{{ URL::route('product-category.index') }}">Categories</a></li>
						<li><a href="{{ URL::route('brand.index') }}">Brands</a></li>
						<li><a href="{{ URL::route('product-tag-group.index') }}">Product tag groups</a></li>
						<li><a href="{{ URL::route('extra-field.index') }}">Extra fields</a></li>
						<li><a href="{{ URL::route('attribute-group.index') }}">Attribute groups</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Content <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ URL::route('news.index') }}">News</a></li>
						<li><a href="{{ URL::route('news-group.index') }}">News group</a></li>
						<li><a href="{{ URL::route('content.index') }}">Static content</a></li>
						<li><a href="{{ URL::route('content-group.index') }}">Static content groups</a></li>
						<li><a href="{{ URL::route('html-block.index') }}">HTML blocks</a></li>
						<li><a href="{{ URL::route('faq.index') }}">Faq items</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ URL::route('tax-rate.index') }}">Tax rates</a></li>
						<li><a href="{{ URL::route('sending-method.index') }}">Sending methods</a></li>
						<li><a href="{{ URL::route('payment-method.index') }}">Payment methods</a></li>
						<li><a href="{{ URL::route('user.index') }}">Users</a></li>
						<li><a href="{{ URL::route('general-setting.index') }}">General settings</a></li>
						<li><a href="{{ URL::route('redirect.index') }}">Redirects</a></li>
					</ul>
				</li>

				<li><a href="{{ URL::route('security.logout') }}" title="logout"><span class="glyphicon glyphicon-log-out "></span></a></li>
			</ul>

		</div>
	</div>
</nav>