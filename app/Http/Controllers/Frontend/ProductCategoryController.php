<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductCombinationFacade as ProductCombinationService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{    
    public function getItem(Request $request, $slug)
    {
        $category = ProductCategoryService::selectOneByShopIdAndSlug(config()->get('app.shop_id'), $slug);

        if ($category) {

            if ($category->refProductCategory) {
                return redirect()->to($category->refProductCategory->slug);
            }

            if ($category->ancestors()->count()) {
                //$request->session()->put('category_id', $category->ancestors()->first()->id);
            }

            $products = "";
            if ($category->products()->count()) {
                $products = ProductService::selectAllByShopIdAndProductCategoryId(config()->get('app.shop_id'), $category['id']);
            }

            if ($category->isLeaf()) {
                if ($category->isChild()) {
                    $childrenProductCategories = ProductCategoryService::selectCategoriesByParentId(config()->get('app.shop_id'), $category->parent_id);
                } else {
                    $childrenProductCategories = ProductCategoryService::selectAllByShopIdAndRoot(config()->get('app.shop_id'));
                }

                $attributes = ProductCombinationService::selectAllByProductCategoryId($category->id, config()->get('app.shop_id'));

                return view('frontend.product_category.products')->with(
                    array(
                        'childrenProductCategories' => $childrenProductCategories,
                        'category' => $category,
                        'products' => $products,
                    )
                );
            }

            $childrenProductCategories = ProductCategoryService::selectCategoriesByParentId(config()->get('app.shop_id'), $category->id);
            
            return view('frontend.product_category.categories')->with(
                array(
                    'category' => $category,
                    'childrenProductCategories' => $childrenProductCategories
                )
            );
        }
    
        abort(404);    
    }
}