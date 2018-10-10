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
 
        if (isset($result->id)) {
            Notification::success('The product extra fields are updated.');
            return redirect()->route('product.product-extra-field-value.index', $productId);
        }
          
        return redirect()->back()->withInput();
    }

    public function edit($productId, $id)
    {
        $product = ProductService::find($productId);
        return view('backend.product-extra-field-value.edit')->with(array('productExtraFieldValue' => ProductExtraFieldValueService::find($id), 'product' => $product));
    }

    public function update(Request $request, $productId, $id)
    {
        $result  = ProductExtraFieldValueService::updateById($request->all(), $productId, $id);

        if (isset($result->id)) {
            return redirect()->back()->withInput()->withErrors($result->errors()->all());
        }
        
        Notification::success('The product image is updated.');
        return redirect()->route('product.{productId}.images.index', $productId);
    }

    public function destroy($productId, $id)
    {
        $result  = ProductExtraFieldValueService::destroy($id);

        if ($result) {
            Notification::success('The product image is deleted.');
            return redirect()->route('product.{productId}.images.index', $productId);
        }
    }
}
