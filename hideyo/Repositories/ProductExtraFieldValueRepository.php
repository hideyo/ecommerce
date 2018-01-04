<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductExtraFieldValue;
use Hideyo\Repositories\ProductExtraFieldValueRepositoryInterface;
 
class ProductExtraFieldValueRepository extends BaseRepository implements ProductExtraFieldValueRepositoryInterface
{

    protected $model;

    public function __construct(ProductExtraFieldValue $model, ProductRepositoryInterface $product)
    {
        $this->model = $model;
        $this->product = $product;
    }
  
    public function create(array $attributes, $productId)
    {

        $product = $this->product->find($productId);
        $remove  = $this->model->where('product_id', '=', $productId)->delete();
                
        $result = false;
        if (isset($attributes['rows'])) {
            foreach ($attributes['rows'] as $row) {
                $data = array();

                $check  = $this->model->where('extra_field_id', '=', $row['extra_field_id'])->where('product_id', '=', $productId)->first();
                $data['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
                if (!empty($row['extra_field_default_value_id']) or !empty($row['value'])) {
                    if ($check) {
                        $data['modified_by_user_id'] = auth('hideyobackend')->user()->id;
                        $data['extra_field_id'] = $row['extra_field_id'];
                        $data['product_id'] = $product->id;
                        if (isset($row['extra_field_default_value_id']) and $row['extra_field_default_value_id']) {
                            $data['extra_field_default_value_id'] = $row['extra_field_default_value_id'];
                        } else {
                            $data['extra_field_default_value_id'] = null;
                        }
               
                        $data['value'] = $row['value'];

                        $result = new ProductExtraFieldValue;
                        $result = $result->find($check->id);
                        $result->fill($data);
                        $result->save();
                    } else {
                        $data['modified_by_user_id'] = auth('hideyobackend')->user()->id;
                        $data['extra_field_id'] = $row['extra_field_id'];
                        $data['product_id'] = $product->id;
                        if (isset($row['extra_field_default_value_id']) and $row['extra_field_default_value_id']) {
                            $data['extra_field_default_value_id'] = $row['extra_field_default_value_id'];
                        }
                        $data['value'] = $row['value'];

                        $result = new ProductExtraFieldValue;
                        $result->fill($data);
                        $result->save();
                    }
                }
            }
        }

        return $result;
    }
 
    public function selectAllByProductId($productId)
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('product_id', '=', $productId)->get();
    }

    public function selectByProductIdAndExtraFieldId($productId, $extraFieldId)
    {

        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('product_id', '=', $productId)->where('extra_field_id', '=', $extraFieldId)->get();
    }

    function selectOneByShopIdAndSlug($shopId, $slug)
    {
           return $this->model->with(array('productCategory', 'productImages'))->where('shop_id', '=', $shopId)->where('slug', '=', $slug)->get()->first();
    }

    function selectOneByShopIdAndId($shopId, $id)
    {
           return $this->model->with(array('productCategory', 'productImages'))->where('shop_id', '=', $shopId)->where('id', '=', $id)->get()->first();
    }

    function selectAllByProductCategoryId($productCategoryId, $shopId)
    {

         return $this->model->
         whereHas('product', function ($query) use ($productCategoryId, $shopId) {
            $query->where('product_category_id', '=', $productCategoryId);
            $query->where('active', '=', 1);
            $query->where('shop_id', '=', $shopId);
         })->with(
             array(
                'extraFieldDefaultValue',
                'extraField' => function ($q) {
                }
                )
         )->get();
    }
}