<?php namespace App\Http\Controllers\Backend;


/**
 * ProductImageController
 *
 * This is the controller of the product images of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Illuminate\Http\Request;
use Notification;

class ProductImageController extends Controller
{
    public function index(Request $request, $productId)
    {
        $product = ProductService::find($productId);
        if ($request->wantsJson()) {
            $query = ProductService::getImageModel()->where('product_id', '=', $productId);
            
            $datatables = \DataTables::of($query)
            ->addColumn('thumb', function ($query) use ($productId) {
                return '<img src="/files/product/100x100/'.$query->product_id.'/'.$query->file.'"  />';
            })

            ->addColumn('action', function ($query) use ($productId) {
                $deleteLink = \Form::deleteajax(url()->route('product.images.destroy', array('productId' => $productId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('product.images.edit', array('productId' => $productId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->rawColumns(['thumb', 'action'])->make(true);
        }
        
        return view('backend.product_image.index')->with(array('product' => $product));
    }

    public function create($productId)
    {
        $product = ProductService::find($productId);
        $lists = $this->generateAttributeLists($product);
        return view('backend.product_image.create')->with(array('attributesList' => $lists['attributesList'], 'productAttributesList' => $lists['productAttributesList'], 'product' => $product));
    }

    public function store(Request $request, $productId)
    {
        $result  = ProductService::createImage($request->all(), $productId);
        return ProductService::notificationRedirect(array('product.images.index', $productId), $result, 'The product image was inserted.');
    }

    public function edit($productId, $productImageId)
    {
        $product = ProductService::find($productId);
        $productImage = ProductService::findImage($productImageId);
        $lists = $this->generateAttributeLists($product);
        $selectedProductAttributes = array();
        $selectedAttributes = array();

        if ($productImage->relatedProductAttributes->count()) {
            foreach ($productImage->relatedProductAttributes as $row) {
                $selectedProductAttributes[] =  $row->pivot->product_attribute_id;
            }
        }

        if ($productImage->relatedAttributes->count()) {
            foreach ($productImage->relatedAttributes as $row) {
                $selectedAttributes[] =  $row->pivot->attribute_id;
            }
        }

        return view('backend.product_image.edit')->with(array('selectedAttributes' => $selectedAttributes, 'selectedProductAttributes' => $selectedProductAttributes, 'attributesList' => $lists['attributesList'], 'productAttributesList' => $lists['productAttributesList'], 'productImage' => $productImage, 'product' => $product));
    }

    public function generateAttributeLists($product)
    {
        $productAttributes =         $product->attributes;
        $newProductAttributes = array();
        $attributesList = array();
        $productAttributesList = array();
        if ($product->attributes->count()) {
            foreach ($productAttributes as $row) {
                $combinations = $row->combinations;
                foreach ($combinations as $combination) {
                    $newProductAttributes[$row->id][$combination->attribute->attributeGroup->title]['id'] = $combination->attribute->id;
                    $newProductAttributes[$row->id][$combination->attribute->attributeGroup->title]['value'] = $combination->attribute->value;
                }
            }

            if ($newProductAttributes) {
                foreach ($newProductAttributes as $key => $productAttribute) {
                    $newArray = array();
                    foreach ($productAttribute as $keyNew => $valueNew) {
                         $newArray[] = $keyNew.': '.$valueNew['value'];
                         $attributesList[$valueNew['id']] = $valueNew['value'];
                    }
                    $productAttributesList[$key] = implode(', ', $newArray);
                }
            }
        }

        return array('productAttributesList' => $productAttributesList, 'attributesList' => $attributesList);
    }

    public function update(Request $request, $productId, $productImageId)
    {
        $result  = ProductService::updateImageById($request->all(), $productId, $productImageId);
        return ProductService::notificationRedirect(array('product.images.index', $productId), $result, 'The product image was update.');
    }

    public function destroy($productId, $productImageId)
    {
        $result  = ProductService::destroyImage($productImageId);

        if ($result) {
            Notification::success('The product image is deleted.');
            return redirect()->route('product.images.index', $productId);
        }
    }
}
