<?php

    Route::get('/', array('as' => 'index', 'uses' => 'DashboardController@index'));
   
    Route::resource('dashboard', 'DashboardController');

    Route::get('dashboard/stats/revenue-by-year/{year}', array('as' => 'dashboard.stats', 'uses' => 'DashboardController@getStatsRevenueByYear'));
    Route::get('dashboard/stats/order-average-by-year/{year}', array('as' => 'dashboard.stats.average', 'uses' => 'DashboardController@getStatsOrderAverageByYear'));
    Route::get('dashboard/stats/browser-by-year/{year}', array('as' => 'dashboard.stats.browser', 'uses' => 'DashboardController@getStatsBrowserByYear'));
    Route::get('dashboard/stats/totals-by-year/{year}', array('as' => 'dashboard.stats.total', 'uses' => 'DashboardController@getStatsTotalsByYear'));
    Route::get('dashboard/stats/payment-method-by-year/{year}', array('as' => 'dashboard.stats.payment.method', 'uses' => 'DashboardController@getStatsPaymentMethodByYear'));
    Route::get('dashboard/stats', array('as' => 'dashboard.stats', 'uses' => 'DashboardController@showStats'));
    
    Route::resource('shop', 'ShopController');

    Route::post('client/export', array('as' => 'client.export', 'uses' => 'ClientController@postExport'));
    Route::get('client/export', array('as' => 'client.export', 'uses' => 'ClientController@getExport'));

    Route::get('client/{clientId}/activate', array('as' => 'client.activate', 'uses' => 'ClientController@getActivate'));    
    Route::post('client/{clientId}/activate', array('as' => 'client.activate', 'uses' => 'ClientController@postActivate'));
    
    Route::get('client/{clientId}/de-activate', array('as' => 'client.deactivate', 'uses' => 'ClientController@getDeActivate'));
    Route::post('client/{clientId}/de-activate', array('as' => 'client.de-activate', 'uses' => 'ClientController@postDeActivate'));

    Route::resource('client/{clientId}/order', 'ClientOrderController', array('as' => 'client'));

    Route::resource('client/{clientId}/addresses', 'ClientAddressController', array('as' => 'client'));

    Route::resource('client', 'ClientController');

    Route::get('redirect/export', array('as' => 'redirect.export', 'uses' => 'RedirectController@getExport'));
    Route::get('redirect/import', array('as' => 'redirect.import', 'uses' => 'RedirectController@getImport'));
    Route::post('redirect/import', array('as' => 'redirect.import', 'uses' => 'RedirectController@postImport'));

    Route::get('order/print/products', array('as' => 'order.print.products', 'uses' => 'OrderController@getPrintOrders'));
    
    Route::get('order/print', array('as' => 'order.print', 'uses' => 'OrderController@getPrint'));
    Route::post('order/print', array('as' => 'order.print', 'uses' => 'OrderController@postPrint'));
  
    Route::post('order/print/download', array('as' => 'order.download.print', 'uses' => 'OrderController@postDownloadPrint'));
  
    Route::resource('order', 'OrderController');

    Route::resource('order-status', 'OrderStatusController');

    Route::get('order-status-email-template/show-template/{id}', array('as' => 'order.status.email.template.ajax.show', 'uses' => 'OrderStatusEmailTemplateController@showAjaxTemplate'));

    Route::resource('order-status-email-template', 'OrderStatusEmailTemplateController');

    Route::resource('redirect', 'RedirectController');

    Route::resource('tax-rate', 'TaxRateController');

    Route::resource('general-setting', 'GeneralSettingController');

    Route::resource('sending-method', 'SendingMethodController');

    Route::resource('payment-method', 'PaymentMethodController');

    Route::resource('sending-payment-method-related', 'SendingPaymentMethodRelatedController');

    Route::resource('error', 'ErrorController');

    Route::resource('content/{contentId}/images', 'ContentImageController', array('as' => 'content'));

    Route::get('content/edit/{contentId}/seo', array('as' => 'content.edit_seo', 'uses' => 'ContentController@editSeo'));

    Route::resource('content', 'ContentController');

    Route::resource('content-group', 'ContentGroupController');

    Route::get('content-group/edit/{contentGroupId}/seo', array('as' => 'content-group.edit_seo', 'uses' => 'ContentGroupController@editSeo'));

    Route::get('news/refactor-images', array('as' => 'news.refactor.images', 'uses' => 'NewsController@refactorAllImages'));
 
    Route::get('news/re-directory-images', array('as' => 'news.re.directory.images', 'uses' => 'NewsController@reDirectoryAllImages'));
 

    Route::resource('news/{newsId}/images', 'NewsImageController', ['as' => 'news-images']);

    Route::get('news/edit/{newsId}/seo', array('as' => 'news.edit_seo', 'uses' => 'NewsController@editSeo'));

    Route::resource('news', 'NewsController');

    Route::resource('news-group', 'NewsGroupController');

    Route::get('news-group/edit/{newsGroupId}/seo', array('as' => 'news-group.edit_seo', 'uses' => 'NewsGroupController@editSeo'));

    Route::resource('faq', 'FaqItemController');

    Route::resource('html-block/{htmlBlockId}/copy', 'HtmlBlockController@copy');
    Route::get('html-block/change-active/{htmlBlockId}', array('as' => 'html.block.change-active', 'uses' => 'HtmlBlockController@changeActive'));
 
    Route::post('html-block/{htmlBlockId}/copy', array('as' => 'html.block.store.copy', 'uses' => 'HtmlBlockController@storeCopy'));
 
    Route::resource('html-block', 'HtmlBlockController');

    Route::resource('coupon-group', 'CouponGroupController');

    Route::resource('coupon', 'CouponController');

    Route::post('order/update-status/{orderId}', array('as' => 'order.update-status', 'uses' => 'OrderController@updateStatus'));
 
    Route::get('order/update-sending-method/{sendingMethodId}', array('as' => 'order.update.sending.method', 'uses' => 'OrderController@updateSendingMethod'));
    Route::get('order/update-payment-method/{paymentMethodId}', array('as' => 'order.update.payment.method', 'uses' => 'OrderController@updatePaymentMethod'));

    Route::resource('order/{orderId}/download', 'OrderController@download');
    Route::resource('order/{orderId}/download-label', 'OrderController@downloadLabel');

    Route::get('order/update-billing-address/{addressId}', array('as' => 'order.update.billing.address', 'uses' => 'OrderController@updateClientBillAddress'));
    Route::get('order/update-delivery-address/{addressId}', array('as' => 'order.update.delivery.address', 'uses' => 'OrderController@updateClientDeliveryAddress'));

    Route::post('order/add-client', array('as' => 'order.add-client', 'uses' => 'OrderController@addClient'));

    Route::post('order/add-product', array('as' => 'order.add-product', 'uses' => 'OrderController@addProduct'));

    Route::get('order/update-amount-product/{productId}/{amount}', array('as' => 'order.update.amount.product', 'uses' => 'OrderController@updateAmountProduct'));
 
    Route::get('order/change-product-combination/{productId}/{newProductId}', array('as' => 'order.change.product.combination', 'uses' => 'OrderController@changeProductCombination'));

    Route::get('order/delete-product/{productId}', array('as' => 'order.delete-product', 'uses' => 'OrderController@deleteProduct'));
    Route::resource('invoice', 'InvoiceController');
    Route::resource('invoice/{invoiceId}/download', 'InvoiceController@download');

    Route::resource('attribute-group/{attributeGroupId}/attribute', 'AttributeController');

    Route::resource('attribute-group', 'AttributeGroupController');

    Route::resource('extra-field/{extraFieldId}/values', 'ExtraFieldDefaultValueController', ['as' => 'extra-field']);
    
    Route::resource('extra-field', 'ExtraFieldController');

    Route::get('product/refactor-images', array('as' => 'product.refactor-images', 'uses' => 'ProductController@refactorAllImages'));
    Route::get('product/re-directory-images', array('as' => 'product.re-directory-images', 'uses' => 'ProductController@reDirectoryAllImages'));

    Route::post('product/export', array('as' => 'product.export', 'uses' => 'ProductController@postExport', 'as' => 'product.export'));

    Route::get('product/export', array('as' => 'product.export', 'uses' => 'ProductController@getExport', 'as' => 'product.export'));

    Route::get('product/rank', array('as' => 'product.rank', 'uses' => 'ProductController@getRank', 'as' => 'product.rank'));

    Route::resource('product', 'ProductController');

    Route::get('product/edit/{productId}/price', array('as' => 'product.edit_price', 'uses' => 'ProductController@editPrice'));
    Route::get('product/change-active/{productId}', array('as' => 'product.change-active', 'uses' => 'ProductController@changeActive', 'as' => 'product.change-active'));
    Route::get('product/change-amount/{productId}/{amount?}', array('as' => 'product.change-amount', 'uses' => 'ProductController@changeAmount', 'as' => 'product.change-amount'));
    Route::get('product/change-rank/{productId}/{rank?}', array('as' => 'product.change-rank', 'uses' => 'ProductController@changeRank', 'as' => 'product.change-rank'));
  
    Route::get('product/edit/{productId}/seo', array('as' => 'product.edit_seo', 'uses' => 'ProductController@editSeo'));
    Route::resource('product/{productId}/images', 'ProductImageController', ['as' => 'product']);
    Route::resource('product/{productId}/product-amount-option', 'ProductAmountOptionController', ['as' => 'product']);
    Route::resource('product/{productId}/product-amount-series', 'ProductAmountSeriesController', ['as' => 'product']);


    Route::resource('product/{productId}/product-combination', 'ProductCombinationController');

    Route::get('product/{productId}/product-combination/change-amount-attribute/{id}/{amount?}', array('as' => 'product', 'uses' => 'ProductCombinationController@changeAmount'));
 
    Route::post('product/{productId}/copy', array('as' => 'product.store-copy', 'uses' => 'ProductController@storeCopy'));
    
    Route::resource('product/{productId}/product-extra-field-value', 'ProductExtraFieldValueController', ['as' => 'product']);

    Route::resource('product/{productId}/related-product', 'ProductRelatedProductController', ['as' => 'product']);

    Route::get('product/{productId}/copy', array('as' => 'product.copy', 'uses' => 'ProductController@copy', 'as' => 'product.copy'));


    Route::get('product-category/refactor-images', array('as' => 'product-category.refactor-images', 'uses' => 'ProductCategoryController@refactorAllImages'));
    Route::get('product-category/re-directory-images', array('as' => 'product-category.re-directory-images', 'uses' => 'ProductCategoryController@reDirectoryAllImages'));

    Route::resource('brand/{brandId}/images', 'BrandImageController', ['as' => 'brand']);
 
    Route::get('brand/edit/{brandId}/seo', array('as' => 'brand.edit_seo', 'uses' => 'BrandController@editSeo'));
 
    Route::resource('brand', 'BrandController');

    Route::get('product-category/change-active/{productCategoryId}', array('as' => 'product-category.change-active', 'uses' => 'ProductCategoryController@changeActive'));

    Route::get('product_category/get_ajax_categories', array('as' => 'product-category.ajax_categories', 'uses' => 'ProductCategoryController@ajaxCategories'));
    Route::get('product_category/get_ajax_category/{id}', array('as' => 'product-category.ajax_category', 'uses' => 'ProductCategoryController@ajaxCategory'));
 
    Route::get('product_category/edit/{productCategoryId}/hightlight', array('as' => 'product-category.edit.hightlight', 'uses' => 'ProductCategoryController@editHighlight'));

    Route::resource('product-category/{productCategoryId}/images', 'ProductCategoryImageController', ['as' => 'product-category']);


    Route::get('product_category/edit/{productCategoryId}/seo', array('as' => 'product-category.edit_seo', 'uses' => 'ProductCategoryController@editSeo'));

    Route::get('product_category/ajax-root-tree', array('as' => 'product-category.ajax-root-tree', 'uses' => 'ProductCategoryController@ajaxRootTree'));
    Route::get('product_category/ajax-children-tree', array('as' => 'product-category.ajax-children-tree', 'uses' => 'ProductCategoryController@ajaxChildrenTree'));
    Route::get('product_category/ajax-move-node', array('as' => 'product-category.ajax-move-node', 'uses' => 'ProductCategoryController@ajaxMoveNode'));

    Route::get('product_category/tree', array('as' => 'product-category.tree', 'uses' => 'ProductCategoryController@tree'));

    Route::resource('product-category', 'ProductCategoryController');

    Route::resource('product-tag-group', 'ProductTagGroupController');

    Route::resource('user', 'UserController');

    Route::get('profile/shop/change/{shopId}', array('as' => 'change.language.profile', 'uses' => 'UserController@changeShopProfile'));
    Route::get('profile', array('as' => 'edit.profile', 'uses' => 'UserController@editProfile'));
    Route::post('profile', array('as' => 'update.profile', 'uses' => 'UserController@updateProfile'));
    Route::post('profile_language', array('as' => 'update.language', 'uses' => 'UserController@updateLanguage'));


