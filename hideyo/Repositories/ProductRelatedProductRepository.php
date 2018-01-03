<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductRelatedProduct;
use Hideyo\Models\Product;
use Hideyo\Repositories\ProductRepositoryInterface;
use Auth;
 
class ProductRelatedProductRepository implements ProductRelatedProductRepositoryInterface
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

    private function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }

    public function destroy($relatedId)
    {
        $this->model = $this->find($relatedId);
        $filename = $this->model->path;

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectAllByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->get();
    }

    function selectAllByProductId($productId)
    {
         return $this->model->where('product_id', '=', $productId)->get();
    }
    
    public function find($relatedId)
    {
        return $this->model->find($relatedId);
    }

    public function getModel()
    {
        return $this->model;
    }   
}