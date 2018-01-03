<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductAmountOption;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
 
class ProductAmountOptionRepository extends BaseRepository implements ProductAmountOptionRepositoryInterface
{

    protected $model;

    public function __construct(ProductAmountOption $model, ProductRepositoryInterface $product)
    {
        $this->model = $model;
        $this->product = $product;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $AmountOptionId id attribute model    
     * @return array
     */
    private function rules($AmountOptionId = false)
    {
        $rules = array(
            'amount' => 'required'

        );
        
        if ($AmountOptionId) {
            $rules['amount'] =   'required';
        }

        return $rules;
    }

    public function create(array $attributes, $productId)
    {
        $product = $this->product->find($productId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $attributes['product_id'] = $product->id;
        $validator = \Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            
        $this->model->fill($attributes);
        $this->model->save();
   
        return $this->model;
    }

    public function updateById(array $attributes, $productId, $AmountOptionId)
    {
        $this->model = $this->find($AmountOptionId);
        $attributes['product_id'] = $productId;
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules($AmountOptionId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }

    function selectOneById($AmountOptionId)
    {
        $result = $this->model->with(array('relatedPaymentMethods'))->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('active', '=', 1)->where('id', '=', $AmountOptionId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    function selectOneByShopIdAndId($shopId, $AmountOptionId)
    {
        $result = $this->model->with(array('relatedPaymentMethods' => function ($query) {
            $query->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $AmountOptionId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }  
}