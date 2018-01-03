<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\AttributeGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
 
class AttributeGroupRepository extends BaseRepository implements AttributeGroupRepositoryInterface
{
    protected $model;

    public function __construct(AttributeGroup $model)
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
            'title' => 'required|between:1,65|unique_with:'.$this->model->getTable().', shop_id'
        );
        
        if ($id) {
            $rules['title'] =   'required|between:1,65|unique_with:'.$this->model->getTable().', shop_id, '.$id.' = id';
        }

        return $rules;
    }

    /**
     * Validate and fill the model with attributes and save in the database.
     *
     * @return model
     */
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
        return $this->model;
    }

    public function updateById(array $attributes, $id)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($id));
        if ($validator->fails()) {
            return $validator;
        }

        $this->model = $this->find($id);
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);   
    }

    public function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }
}