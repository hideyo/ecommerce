<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductRelatedProduct;
use Hideyo\Models\Product;
use Hideyo\Repositories\ProductRepositoryInterface;
use Auth;
 
class ProductRelatedProductRepository  extends BaseRepository implements ProductRelatedProductRepositoryInterface
{

    protected $model;

    public function __construct(ProductRelatedProduct $model, ProductRepositoryInterface $product)
    {
        $this->model = $model;
        $this->product = $product;
    }
  
    public function create(array $attributes, $productParentId)
    {
        $parentProduct = $this->product->find($productParentId);
   
        if (isset($attributes['products'])) {
            $parentProduct->relatedProducts()->attach($attributes['products']);
        }

        return $parentProduct->save();
    }

    public function destroy($relatedId)
    {
        $this->model = $this->find($relatedId);
        $filename = $this->model->path;

        return $this->model->delete();
    }

    function selectAllByProductId($productId)
    {
         return $this->model->where('product_id', '=', $productId)->get();
    }
    
 
}