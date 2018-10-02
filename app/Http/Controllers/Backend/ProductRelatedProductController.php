<?php namespace App\Http\Controllers\Backend;


/**
 * ProductRelatedProductController
 *
 * This is the controller of the product related products of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;


use Hideyo\Ecommerce\Framework\Repositories\ProductRelatedProductRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductRepository;

use Illuminate\Http\Request;
use Notification;

class ProductRelatedProductController extends Controller
{
    public function __construct(Request $request, ProductRelatedProductRepository $productRelatedProduct, ProductRepository $product)
    {
        $this->productRelatedProduct    = $productRelatedProduct;
        $this->product                  = $product;
        $this->request                  = $request;
    }    

    public function index($productId)
    {
        $product = $this->product->find($productId);
        if ($this->request->wantsJson()) {

            $query = $this->productRelatedProduct->getModel()->where('product_id', '=', $productId);
            
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
        $product = $this->product->find($productId);
        $products = $this->product->selectAll()->pluck('title', 'id');

        return view('backend.product_related_product.create')->with(array('products' => $products, 'product' => $product));
    }
    
    public function store($productId)
    {
        $result  = $this->productRelatedProduct->create($this->request->all(), $productId);
        return redirect()->route('product.related-product.index', $productId);
    }

    public function edit($productRelatedProductId)
    {
        return view('backend.product_related_product.edit')->with(array('productRelatedProduct' => ProductImage::find($productRelatedProductId), 'categories' => $this->productRelatedProduct->selectAll()->pluck('title', 'id')));
    }

    public function update($productRelatedProductId)
    {
        $result  = $this->productRelatedProduct->updateById($this->generateInput(), $productRelatedProductId);

        if (!$result->id) {
            return redirect()->back()->withInput()->withErrors($result->errors()->all());
        } else {
            Notification::success('The related product is updated.');
            return redirect()->route('product_related_product.index');
        }
    }

    public function destroy($productId, $productRelatedProductId)
    {
        $result  = $this->productRelatedProduct->destroy($productRelatedProductId);

        if ($result) {
            Notification::success('The related product is deleted.');
            return redirect()->route('product.related-product.index', $productId);
        }
    }
}
