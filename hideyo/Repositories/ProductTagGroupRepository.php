<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductTagGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class ProductTagGroupRepository implements ProductTagGroupRepositoryInterface
{

    protected $model;

    public function __construct(ProductTagGroup $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $tagGroupId id attribute model    
     * @return array
     */
    private function rules($tagGroupId = false)
    {
        $rules = array(
            'tag' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'

        );
        
        if ($tagGroupId) {
            $rules['tag'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$tagGroupId.' = id';
        }

        return $rules;
    }

  
    public function create(array $attributes)
    {
        $attributes['shop_id'] = \auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = \auth('hideyobackend')->user()->id;
            
        $this->model->fill($attributes);
        $this->model->save();

        if (isset($attributes['products'])) {
            $this->model->relatedProducts()->sync($attributes['products']);
        }
   
        return $this->model;
    }

    public function updateById(array $attributes, $tagGroupId)
    {
        $this->model = $this->find($tagGroupId);
        $attributes['shop_id'] = \auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules($tagGroupId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = \auth('hideyobackend')->user()->id;
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

    public function destroy($tagGroupId)
    {
        $this->model = $this->find($tagGroupId);
        $this->model->save();

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', \auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->get();
    }

    function selectOneByShopIdAndId($shopId, $tagGroupId)
    {
        $result = $this->model->with(array('relatedPaymentMethods' => function ($query) {
            $query->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $tagGroupId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
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
        } else {
            return false;
        }
    }
    
    function selectOneById($tagGroupId)
    {
        $result = $this->model->with(array('relatedPaymentMethods'))->where('shop_id', '=', \auth('hideyobackend')->user()->selected_shop_id)->where('active', '=', 1)->where('id', '=', $tagGroupId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    public function find($tagGroupId)
    {
        return $this->model->find($tagGroupId);
    }

    public function getModel()
    {
        return $this->model;
    }
    
}
