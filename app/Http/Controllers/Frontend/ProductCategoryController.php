<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductCombinationRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductExtraFieldValueRepository;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct(ProductCombinationRepository $productAttribute, ProductExtraFieldValueRepository $productExtraFieldValue)
    {
        $this->productAttribute = $productAttribute;
        $this->productExtraFieldValue = $productExtraFieldValue; 
    }
    
    //to-do transfer logic to repo
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

                $attributes = $this->productAttribute->selectAllByProductCategoryId($category->id, config()->get('app.shop_id'));
                $extraFields = $this->productExtraFieldValue->selectAllByProductCategoryId($category->id, config()->get('app.shop_id'));
              
                $filterCombinations = array();

                if ($attributes->count()) {
                    foreach ($attributes as $row) {
                        foreach ($row->combinations as $combination) {
                            if ($combination->attribute->attributeGroup->filter) {
                                $filterCombinations[$combination->attribute->attributeGroup->title]['filter_type'] = $combination->attribute->attributeGroup->filter_type;
                                $filterCombinations[$combination->attribute->attributeGroup->title]['options'][$combination->attribute->id] = $combination->attribute->value;
                                ksort($filterCombinations[$combination->attribute->attributeGroup->title]['options']);
                            }
                        }
                    }
                }

                $extraFilterFields = array();

                if ($extraFields->count()) {
                    foreach ($extraFields as $row) {
                        if ($row->extraField->filterable) {
                            if ($row->value) {
                                $extraFilterFields[$row->extraField->title]['options'][$row->value] = $row->value;
                            } else {
                                $extraFilterFields[$row->extraField->title]['options'][$row->extraFieldDefaultValue->id] = $row->extraFieldDefaultValue->value;
                            }
                        }
                    }
                }

                return view('frontend.product_category.products')->with(
                    array(
                        'childrenProductCategories' => $childrenProductCategories,
                        'filterCombinations' => $filterCombinations,
                        'extraFilterFields' => $extraFilterFields,
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
