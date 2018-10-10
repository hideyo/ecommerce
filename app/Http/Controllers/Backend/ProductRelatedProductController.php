<?php namespace App\Http\Controllers\Backend;


/**
 * ProductRelatedProductController
 *
 * This is the controller of the product related products of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductRelatedProductFacade as ProductRelatedProductService;

use Illuminate\Http\Request;
use Notification;

class ProductRelatedProductController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }    

    public function index($productId)
    {
        $product = ProductService::find($productId);
        if ($this->request->wantsJson()) {

            $query = ProductRelatedProductService::getModel()->where('product_id', '=', $productId);
            
            $datatables = \Datatables::of($query)
                ->addColumn('related', function ($query) use ($productId) {
                    return $query->RelatedProduct->title;
                })
                ->addColumn('product', function ($query) use ($productId) {
                    return $query->Product->title;
                })
                ->addColumn('action', function ($query) use ($productId) {
                    $deleteLink = \Form::deleteajax(url()->route('product.related-product.destroy', array('productId' => $productId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                    
                    return $deleteLink;
                });

                return $datatables->make(true);
        }
        
        return view('backend.product_related_product.index')->with(array('product' => $product));
    }

    public function create($productId)
    {
        $product = ProductService::find($productId);
        $products = ProductService::selectAll()->pluck('title', 'id');

        return view('backend.product_related_product.create')->with(array('products' => $products, 'product' => $product));
    }
    
    public function store($productId)
    {
        $result  = ProductRelatedProductService::create($this->request->all(), $productId);
        return redirect()->route('product.related-product.index', $productId);
    }

    public function destroy($productId, $productRelatedProductId)
    {
        $result  = ProductRelatedProductService::destroy($productRelatedProductId);

        if ($result) {
            Notification::success('The related product is deleted.');
            return redirect()->route('product.related-product.index', $productId);
        }
    }
}
