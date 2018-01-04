<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Attribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
 
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;

    public function __construct(Attribute $model)
    {
        $this->model = $model;
    }
  
    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */
    public function rules($id = false)
    {
        if ($id) {
            return [
                'value' => 'required|unique_with:'.$this->model->getTable().',attribute_group_id,'.$id,
            ];
        } else {
            return [
                'value' => 'required|unique_with:'.$this->model->getTable().',attribute_group_id'
            ];
        }
    }

    public function create(array $attributes, $attributeGroupId)
    {
        $attributes['attribute_group_id'] = $attributeGroupId;
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model->fill($attributes);
        $this->model->save();
        return $this->model;
    }

    public function updateById(array $attributes, $attributeGroupId, $id)
    {
        $attributes['attribute_group_id'] = $attributeGroupId;
        $validator = Validator::make($attributes, $this->rules($id));

        if ($validator->fails()) {
            return $validator;
        }

        $this->model = $this->find($id);
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }
}