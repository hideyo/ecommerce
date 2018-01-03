<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductWaitingList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
 
class ProductWaitingListRepository  extends BaseRepository implements ProductWaitingListRepositoryInterface
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

    public function selectAll()
    {
        return $this->model->get();
    }    
}