<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\OrderStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class OrderStatusRepository extends BaseRepository implements OrderStatusRepositoryInterface
{

    protected $model;

    public function __construct(OrderStatus $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */  
    private function rules($id = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique_with:order_status, shop_id'

        );
        
        if ($id) {
            $rules['title'] =   'required|between:4,65|unique_with:order_status, shop_id, '.$id.' = id';
        }

        return $rules;
    }

    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $this->model->fill($attributes);
        $this->model->save();
        return $this->model;
    }

    public function updateById(array $attributes, $id)
    {
        $validator = \Validator::make($attributes, $this->rules($id));

        if ($validator->fails()) {
            return $validator;
        }
        
        $this->model = $this->find($id);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        return $this->updateEntity($attributes);
    }    
}