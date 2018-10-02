<?php namespace App\Http\Controllers\Backend;


/**
 * ProductExtraFieldValueController
 *
 * This is the controller of the product extra field values of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;


use Hideyo\Ecommerce\Framework\Repositories\ProductExtraFieldValueRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductRepository;
use Hideyo\Ecommerce\Framework\Repositories\ExtraFieldRepository;

use Illuminate\Http\Request;
use Notification;

class ProductExtraFieldValueController extends Controller
{
    public function __construct(
        ProductExtraFieldValueRepository $productExtraFieldValue,
        ProductRepository $product,
        ExtraFieldRepository $extraField
    ) {
        $this->productExtraFieldValue = $productExtraFieldValue;
        $this->product = $product;
        $this->extraField = $extraField;
    }

    public function index($productId)
    {
        $product = $this->product->find($productId);
        $extraFieldsData = $this->productExtraFieldValue->selectAllByProductId($productId);
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
                'extraFields' =>  $this->extraField->selectAllByAllProductsAndProductCategoryId($product->product_category_id),
                'product' => $this->product->find($productId),
                'populateData' => $newExtraFieldsData
            )
        );
    }

    public function store($productId, Request $request)
    {
        $result  = $this->productExtraFieldValue->create($request->all(), $productId);
 
        if (isset($result->id)) {
            Notification::success('The product extra fields are updated.');
            return redirect()->route('product.extra-field-value.index', $productId);
        }
          
        return redirect()->back()->withInput();
    }

    public function edit($productId, $id)
    {
        $product = $this->product->find($productId);
        return view('backend.product-extra-field-value.edit')->with(array('productExtraFieldValue' => $this->productExtraFieldValue->find($id), 'product' => $product));
    }

    public function update(Request $request, $productId, $id)
    {
        $result  = $this->productExtraFieldValue->updateById($request->all(), $productId, $id);

        if (isset($result->id)) {
            return redirect()->back()->withInput()->withErrors($result->errors()->all());
        }
        
        Notification::success('The product image is updated.');
        return redirect()->route('product.{productId}.images.index', $productId);
    }

    public function destroy($productId, $id)
    {
        $result  = $this->productExtraFieldValue->destroy($id);

        if ($result) {
            Notification::success('The product image is deleted.');
            return redirect()->route('product.{productId}.images.index', $productId);
        }
    }
}
