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
    public function rules($settingId = false)
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

    public function selectOneByShopIdAndName($shopId, $name)
    {     
        $result = $this->model
        ->where('shop_id', '=', $shopId)->where('name', '=', $name)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }
}