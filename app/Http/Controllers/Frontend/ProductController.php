<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Models\ProductAttributeCombination;
use Hideyo\Models\ProductAttribute;
use Hideyo\Ecommerce\Framework\Repositories\ProductRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\ProductCombinationRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use BrowserDetect;

class ProductController extends Controller
{
    public function __construct(ProductRepositoryInterface $product, ProductCombinationRepositoryInterface $productCombination, ProductCategoryRepositoryInterface $productCategory)
    {
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->productCombination = $productCombination;
    }

    public function getIndex(Request $request, $categorySlug, $productId, $productSlug, $productAttributeId = false)
    {     
        $product = $this->product->selectOneByShopIdAndId(config()->get('app.shop_id'), $productId, $request->get('combination_id'));
        
        if ($product) {

            if ($product->slug != $productSlug or $product->productCategory->slug != $categorySlug) {
                return redirect()->route('product.item', array('productCategorySlug' => $product->productCategory->slug, 'productId' => $product->id, 'slug' => $product->slug));
            }

            if ($product->ProductCategory and $product->ProductCategory->parent()->count()) {
                $productCategories = $this->productCategory->selectCategoriesByParentId(config()->get('app.shop_id'), $product->ProductCategory->parent()->first()->id, 'widescreen');
            } else {
                $productCategories = $this->productCategory->selectRootCategories(false, array('from_stock'));
            }

            if ($product->attributes->count() AND $product->attributeGroup AND $product->attributes->first()->combinations->count()) {
                
                if ($product->attributeGroup) {
                    $attributeLeadingGroup = $product->attributeGroup;
                } else {
                    $attributeLeadingGroup = $product->attributes->first()->combinations->first()->attribute->attributeGroup;
                }

                $pullDowns = $this->productCombination->generatePulldowns($product, $productAttributeId, $attributeLeadingGroup);        
                $newPullDowns = $pullDowns['newPullDowns'];   

                $productAttribute = $pullDowns['productAttribute']; 
                $productImages = $this->product->ajaxProductImages($product, $productAttribute->combinations->pluck('attribute_id')->toArray(), $productAttribute->id);       
                                
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
        $product = $this->product->selectOneByIdAndAttributeId($productId, $leadingAttributeId);
     
        if ($product) {
            if ($product->attributes->count()) {      

                $pullDowns = $this->productCombination->generatePulldowns($product, $leadingAttributeId, $product->attributeGroup, $secondAttributeId);
                $newPullDowns = $pullDowns['newPullDowns'];
                $productAttribute = $pullDowns['productAttribute'];                
                $productImages = $this->product->ajaxProductImages($product, $productAttribute->combinations->pluck('attribute_id')->toArray(), $productAttribute->id);
                
                $typeTemplate = "";

                return view('frontend.product.ajax'.$typeTemplate)->with(array(
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