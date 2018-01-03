<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\GeneralSetting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
 
class GeneralSettingRepository extends BaseRepository implements GeneralSettingRepositoryInterface
{

    protected $model;

    public function __construct(GeneralSetting $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $settingId id attribute model    
     * @return array
     */
    private function rules($settingId = false)
    {
        $rules = array(
            'name' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'value' => 'required'
        );
        
        if ($settingId) {
            $rules['name'] =   $rules['name'].','.$settingId.' = id';
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

    public function updateById(array $attributes, $settingId)
    {
        $this->model = $this->find($settingId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($settingId));

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