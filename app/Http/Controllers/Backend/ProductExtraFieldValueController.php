<?php namespace App\Http\Controllers\Backend;


/**
 * ProductExtraFieldValueController
 *
 * This is the controller of the product extra field values of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductExtraFieldValueFacade as ProductExtraFieldValueService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\ExtraField\ExtraFieldFacade as ExtraFieldService;
use Illuminate\Http\Request;
use Notification;

class ProductExtraFieldValueController extends Controller
{
    public function index($productId)
    {
        $product = ProductService::find($productId);
        $extraFieldsData = ProductExtraFieldValueService::selectAllByProductId($productId);
        $newExtraFieldsData = array();
        if ($extraFieldsData->count()) {
            foreach ($extraFieldsData as $row) {
                $newExtraFieldsData[$row->extra_field_id] = array(
                    'value' => $row->value,
                    'extra_field_default_value_id' => $row->extra_field_default_value_id
                );
            }
        }
   
        return view('backend.product-extra-field-value.index')->with(
            array(
                'extraFields' =>  ExtraFieldService::selectAllByAllProductsAndProductCategoryId($product->product_category_id),
                'product' => ProductService::find($productId),
                'populateData' => $newExtraFieldsData
            )
        );
    }

    public function store($productId, Request $request)
    {
        $result  = ProductExtraFieldValueService::create($request->all(), $productId);
         return ProductExtraFieldValueService::notificationRedirect(array('product.product-extra-field-value.index', $productId), $result, 'The product extra fields are updated.');
    }
}
