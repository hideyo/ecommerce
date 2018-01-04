<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Auth;
 
class ExceptionRepository extends BaseRepository  implements ExceptionRepositoryInterface
{

    protected $model;

    public function __construct(Exception $model)
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
            'name' => 'required|between:4,65|unique_with:general_setting, shop_id'

        );
        
        if ($id) {
            $rules['name'] =   'required|between:4,65|unique_with:general_setting, shop_id, '.$id.' = id';
        }

        return $rules;
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
        
        return $this->model;
    }

    public function updateById(array $attributes, $id)
    {
        $this->model = $this->find($id);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($id));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }

    function selectOneByShopIdAndName($shopId, $name)
    {
        
        $result = $this->model
        ->where('shop_id', '=', $shopId)->where('name', '=', $name)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }  
}