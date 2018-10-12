<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductCombinationFacade as ProductCombinationService;

class ProductController extends Controller
{
    public function getIndex(Request $request, $categorySlug, $productId, $productSlug, $productAttributeId = false)
    {     
        $product = ProductService::selectOneByShopIdAndId(config()->get('app.shop_id'), $productId, $request->get('combination_id'));
        
        if ($product) {
            if ($product->slug != $productSlug or $product->productCategory->slug != $categorySlug) {
                return redirect()->route('product.item', array('productCategorySlug' => $product->productCategory->slug, 'productId' => $product->id, 'slug' => $product->slug));
            }

            if ($product->ProductCategory and $product->ProductCategory->parent()->count()) {
                $productCategories = ProductCategoryService::selectCategoriesByParentId(config()->get('app.shop_id'), $product->ProductCategory->parent()->first()->id, 'widescreen');
            } else {
                $productCategories = ProductCategoryService::selectRootCategories(false, array('from_stock'));
            }

            if ($product->attributes->count() AND $product->attributeGroup AND $product->attributes->first()->combinations->count()) {
                
                if ($product->attributeGroup) {
                    $attributeLeadingGroup = $product->attributeGroup;
                } else {
                    $attributeLeadingGroup = $product->attributes->first()->combinations->first()->attribute->attributeGroup;
                }

                $pullDowns = ProductCombinationService::generatePulldowns($product, $productAttributeId, $attributeLeadingGroup);        
                $newPullDowns = $pullDowns['newPullDowns'];   

                $productAttribute = $pullDowns['productAttribute']; 
                $productImages = ProductService::ajaxProductImages($product, $productAttribute->combinations->pluck('attribute_id')->toArray(), $productAttribute->id);       
                                
                $template = 'frontend.product.combinations';

                $leadingAttributeId = key(reset($newPullDowns));
                if($productAttributeId) { 
                    $leadingAttributeId = $productAttributeId;
                } else {
                    $productAttributeId = $pullDowns['productAttribute']->first()->id;
                }                 
                    
                return view($template)->with(
                    array(                     
                        'productImages' => $productImages,    
                        'productAttributeId' => $productAttributeId,
                        'leadAttributeId' => $leadingAttributeId,
                        'firstPulldown' => key($newPullDowns),
                        'newPullDowns' => $newPullDowns,
                        'priceDetails' => $productAttribute->getPriceDetails(),
                        'childrenProductCategories' => $productCategories,                        
                        'product' => $product        
                    )
                );
            }

            $productImages = $product->productImages;
  
            if (isset($product['ancestors'])) {
                $request->session()->put('category_id', $product['ancestors'][0]['id']);
            }

            $template = 'frontend.product.index';

            return view($template)->with(
                array(
                    'priceDetails' => $product->getPriceDetails(),
                    'childrenProductCategories' => $productCategories,
                    'product' => $product,
                    'productImages' => $productImages        
                )
            );            
        }
        
        abort(404);
    }  

    public function getSelectLeadingPulldown($productId, $leadingAttributeId, $secondAttributeId = false)
    {
        $product = ProductService::selectOneByShopIdAndId(config()->get('app.shop_id'), $productId, $leadingAttributeId);
     
        if ($product) {
            if ($product->attributes->count()) {      
                $pullDowns = ProductCombinationService::generatePulldowns($product, $leadingAttributeId, $product->attributeGroup, $secondAttributeId);
                $newPullDowns = $pullDowns['newPullDowns'];
                $productAttribute = $pullDowns['productAttribute'];                
                $productImages = ProductService::ajaxProductImages($product, $productAttribute->combinations->pluck('attribute_id')->toArray(), $productAttribute->id);

                return view('frontend.product.ajax')->with(array(
                    'newPullDowns' => $newPullDowns,
                    'productImages' => $productImages,            
                    'leadAttributeId' => $leadingAttributeId,
                    'productAttributeId' => $productAttribute->id,
                    'firstPulldown' => key($newPullDowns),
                    'secondAttributeId' => $secondAttributeId,
                    'priceDetails' => $productAttribute->getPriceDetails(),
                    'product' => $product
                ));  
            }
        }
    }
}