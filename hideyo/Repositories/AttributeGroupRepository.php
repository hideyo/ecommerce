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
    public function rules($id = false)
    {
        $rules = array(
            'title' => 'required|between:1,65|unique_with:'.$this->model->getTable().', shop_id'
        );
        
        if ($id) {
            $rules['title'] =   'required|between:1,65|unique_with:'.$this->model->getTable().', shop_id, '.$id.' = id';
        }

        return $rules;
    }
}