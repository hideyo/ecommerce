<?php 
  
Route::get('/text/{slug}', array('as' => 'text', 'uses' => 'ContentController@getItem'));
Route::get('/text-overview/{slug}', 'ContentController@getOverview');

Route::get('/contact', array('as' => 'contact', 'uses' => 'BasicController@getContact'));
Route::put('/contact', array('as' => 'contact', 'uses' => 'BasicController@postContact'));

Route::get('/nieuws/{newsGroupSlug}/{slug}', array('as' => 'newsfrontend.item', 'uses' => 'NewsController@getItem'));
Route::get('/nieuws/{newsGroupSlug}', array('as' => 'news.group', 'uses' => 'NewsController@getByGroup'));
Route::get('/nieuws', array('as' => 'newsfrontend.index', 'uses' => 'NewsController@getIndex'));

Route::group(['prefix' => 'account'], function () {

	Route::get('/login', array('as' => 'account.login', 'uses' => 'AccountController@getLogin'));
	Route::post('/login', array('as' => 'account.login', 'uses' => 'AccountController@postLogin'));
	
	Route::get('/logout', 'AccountController@getLogout');

	Route::get('/reset-password/{code}/{email}', 'AccountController@getResetPassword');
	Route::post('/reset-password/{code}/{email}', 'AccountController@postResetPassword');

	Route::get('/confirm/{code}/{email}', 'AccountController@getConfirm');
	Route::get('/check-zipcode/{zipcode?}/{housenumber?}', 'AccountController@getZipcode');

	Route::get('/register', array('as' => 'account.register', 'uses' => 'AccountController@getRegister'));
	Route::post('/register', array('as' => 'account.register', 'uses' => 'AccountController@postRegister'));
	
	Route::get('/forgot-password', array('as' => 'account.forgot.password', 'uses' => 'AccountController@getForgotPassword'));
	Route::post('/forgot-password', array('as' => 'account.forgot.password', 'uses' => 'AccountController@postForgotPassword'));

	Route::get('/reset-account-settings/{code}/{email}', 'AccountController@getResetAccount');

	Route::group(['middleware' => 'auth'], function () {
	    Route::get('/', 'AccountController@getIndex');
	    Route::get('/edit-account', 'AccountController@getEditAccount');
	    Route::post('/edit-account', 'AccountController@postEditAccount');
	    Route::get('/edit-address/{type}', 'AccountController@getEditAddress');
	    Route::post('/edit-address/{type}', 'AccountController@postEditAddress');
	    Route::get('/download-order/{orderId}', 'AccountController@getDownloadOrder');
	});
});


Route::get('product/select-second-pulldown/{productId}/{leadingAttributeId}/{SecondAttributeId}', array('as' => 'product.select-second-pulldown', 'uses' => 'ProductController@getSelectLeadingPulldown'));
Route::get('product/select-leading-pulldown/{productId}/{attributeId}', array('as' => 'product.select-leading-pulldown', 'uses' => 'ProductController@getSelectLeadingPulldown'));

Route::group(['prefix' => 'cart'], function () {

	Route::get('/update-sending-method/{sendingMethodId}', array('as' => 'cart.update.sending.method', 'uses' => 'CartController@updateSendingMethod'));
	Route::get('/update-payment-method/{paymentMethodId}', array('as' => 'cart.update.payment.method', 'uses' => 'CartController@updatePaymentMethod'));

	Route::get('/checkout', array('as' => 'cart.checkout', 'uses' => 'CheckoutController@checkout'));
	Route::post('/checkout', array('as' => 'cart.checkout', 'uses' => 'CheckoutController@checkout'));

	Route::post('/checkout-register', array('as' => 'cart.checkout-register', 'uses' => 'CheckoutController@postCheckoutRegister'));
    Route::get('/edit-address/{type}', array('as' => 'cart.edit.address', 'uses' => 'CheckoutController@getEditAddress'));
    Route::post('/edit-address/{type}', array('as' => 'cart.edit.address', 'uses' => 'CheckoutController@postEditAddress'));	 

	Route::post('/complete', array('as' => 'cart.complete', 'uses' => 'CheckoutController@postComplete'));

	Route::post('/checkout-login', array('as' => 'cart.checkout.login', 'uses' => 'CheckoutController@postCheckoutLogin'));

	Route::get('/summary-reload', array('as' => 'cart.summary.reload', 'uses' => 'CartController@getSummaryReload'));
	    
	Route::get('/total-reload', array('as' => 'cart.total-reload', 'uses' => 'CartController@getTotalReload'));
	Route::get('/dialog', array('as' => 'cart.dialog', 'uses' => 'CartController@getBasketDialog'));
	Route::get('/update-amount-product/{productId}/{amount}', array('as' => 'cart.update.amount.product', 'uses' => 'CartController@updateAmountProduct'));
	Route::get('/delete-product/{productId}', array('as' => 'cart.delete.product', 'uses' => 'CartController@deleteProduct'));
	Route::get('/', array('as' => 'cart.index', 'uses' => 'CartController@getIndex'));

	//Cart
	Route::post('/post-product/{productId}/{productCombinationId?}', array('as' => 'cart.add.product', 'uses' => 'CartController@postProduct'));
	Route::get('/delete-product/{productId}', array('as' => 'cart.delete.product', 'uses' => 'CartController@deleteProduct'));

});

//product routes
Route::get('{productCategorySlug}/{productId}/{productSlug}/{leadingAttributeId?}/{combinations?}', array('as' => 'product.item', 'uses' => 'ProductController@getIndex'));

//productCategory routes
Route::get('/{slug}', array('as' => 'product-category.item', 'uses' => 'ProductCategoryController@getItem'));

//other
Route::get('/', array('as' => 'index', 'uses' => 'BasicController@index'));