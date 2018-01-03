<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductWaitingList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
 
class ProductWaitingListRepository implements ProductWaitingListRepositoryInterface
{

    protected $model;

    public function __construct(ProductWaitingList $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $waitingListId id attribute model    
     * @return array
     */
    private function rules($waitingListId = false)
    {
        $rules = array(
            'tag' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'

        );
        
        if ($waitingListId) {
            $rules['tag'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$waitingListId.' = id';
        }

        return $rules;
    }

    public function insertEmail(array $attributes)
    {
        $result = $this->model->where('email', '=', $attributes['email'])->where('product_id', '=', $attributes['product_id']);
        
        unset($attributes['product_attribute_id']);
        
        if ($attributes['product_attribute_id'] and !empty($attributes['product_attribute_id'])) {
            $result->where('product_attribute_id', '=', $attributes['product_attribute_id']);
        }

        if ($result->count()) {
            return false;
        }
 
        $this->model->fill($attributes);
        $this->model->save();
        return $this->model;
    }

    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            
        $this->model->fill($attributes);
        $this->model->save();

        if (isset($attributes['products'])) {
            $this->model->relatedProducts()->sync($attributes['products']);
        }
   
        return $this->model;
    }

    public function updateById(array $attributes, $waitingListId)
    {
        $this->model = $this->find($waitingListId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($waitingListId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }

    private function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
     

            $this->model->save();
        
            if (isset($attributes['products'])) {
                $this->model->relatedProducts()->sync($attributes['products']);
            }
        }

        return $this->model;
    }

    public function destroy($waitingListId)
    {
        $this->model = $this->find($waitingListId);
        $this->model->save();

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->get();
    }

    function selectOneById($waitingListId)
    {
        $result = $this->model->with(array('relatedPaymentMethods'))->where('active', '=', 1)->where('id', '=', $waitingListId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->get();
    }

    function selectAllByTagAndShopId($shopId, $tag)
    {
        $result = $this->model->with(array('relatedProducts' => function ($query) {
            $query->with(array('productCategory', 'productImages' => function ($query) {
                $query->orderBy('rank', 'asc');
            }))->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('tag', '=', $tag)->get();
        if ($result->count()) {
            return $result->first()->relatedProducts;
        }
        
        return false;
    }

    function selectOneByShopIdAndId($shopId, $waitingListId)
    {
        $result = $this->model->with(array('relatedPaymentMethods' => function ($query) {
            $query->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $waitingListId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }
    
    public function find($waitingListId)
    {
        return $this->model->find($waitingListId);
    }
    
    public function getModel()
    {
        return $this->model;
    }

}
