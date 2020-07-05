<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hideyo\Ecommerce\Framework\Services\News\NewsFacade as NewsService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      view()->share('footerNews', NewsService::selectByLimitAndOrderBy(config()->get('app.shop_id'), '5', 'desc'));

        view()->share('frontendProductCategories', ProductCategoryService::selectAllByShopIdAndRoot(config()->get('app.shop_id')));

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}


