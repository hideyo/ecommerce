<?php namespace Hideyo\Middleware;

use Closure;
use Hideyo\Repositories\ShopRepositoryInterface;

class DetectDomain
{
    /**
     * Create a new filter instance.
     *
     * @return void
     */
    public function __construct(
        ShopRepositoryInterface $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config()->get('app.url') != $request->root()) {
            $root = $request->root();
            config()->set('app.url', $root);
        }

        $shop = $this->shop->checkByUrl(config()->get('app.url'));

        if(!$shop) {
            abort(404, "shop cannot be found");
        }

        config()->set('app.shop_id', $shop->id);
        view()->share('shop', $shop);

        return $next($request);
    }
}