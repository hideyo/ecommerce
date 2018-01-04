<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Coupon;
use Hideyo\Models\CouponGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Validator;
 
class CouponRepository extends BaseRepository  implements CouponRepositoryInterface
{

    protected $model;

    public function __construct(Coupon $model, CouponGroup $couponGroup)
    {
        $this->model = $model;
        $this->modelGroup = $couponGroup;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $couponId id attribute model    
     * @return array
     */
    private function rules($couponId = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'code' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'product_id' => 'integer',
            'product_category_id' => 'integer'
        );
        
        if ($couponId) {
            $rules['title'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$couponId.' = id';
            $rules['code'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$couponId.' = id';
        }

        return $rules;
    }

    private function rulesGroup($groupId = false, $attributes = false)
    {

        $rules = array(
            'title'                 => 'required|between:4,65|unique_with:'.$this->modelGroup->getTable().', shop_id'
        );
        
        if ($groupId) {
            $rules['title'] =   'required|between:4,65|unique_with:'.$this->modelGroup->getTable().', shop_id, '.$groupId.' = id';
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
        
        if (isset($attributes['product_categories'])) {
            $this->model->productCategories()->sync($attributes['product_categories']);
        }

        if (isset($attributes['products'])) {
            $this->model->products()->sync($attributes['products']);
        }

        if (isset($attributes['sending_methods'])) {
            $this->model->sendingMethods()->sync($attributes['sending_methods']);
        }

        if (isset($attributes['payment_methods'])) {
            $this->model->paymentMethods()->sync($attributes['payment_methods']);
        }

        return $this->model;
    }

  
    public function createGroup(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rulesGroup());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            
        $this->modelGroup->fill($attributes);
        $this->modelGroup->save();
   
        return $this->modelGroup;
    }


    public function updateById(array $attributes, $couponId)
    {
        $this->model = $this->find($couponId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;

        $validator = Validator::make($attributes, $this->rules($couponId));

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;

        return $this->updateEntity($attributes);
    }

    public function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);

            $this->model->productCategories()->sync(array());
            $this->model->products()->sync(array());
            $this->model->sendingMethods()->sync(array());
            $this->model->paymentMethods()->sync(array());

            if (isset($attributes['product_categories'])) {
                $this->model->productCategories()->sync($attributes['product_categories']);
            }

            if (isset($attributes['products'])) {
                $this->model->products()->sync($attributes['products']);
            }

            if (isset($attributes['sending_methods'])) {
                $this->model->sendingMethods()->sync($attributes['sending_methods']);
            }

            if (isset($attributes['payment_methods'])) {
                $this->model->paymentMethods()->sync($attributes['payment_methods']);
            }

            $this->model->save();
        }

        return $this->model;
    }

    public function updateGroupById(array $attributes, $groupId)
    {
        $validator = Validator::make($attributes, $this->rulesGroup($groupId, $attributes));

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->modelGroup = $this->findGroup($groupId);
        return $this->updateGroupEntity($attributes);
    }

    private function updateGroupEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelGroup->fill($attributes);
            $this->modelGroup->save();
        }

        return $this->modelGroup;
    }

    public function destroyGroup($groupId)
    {
        $this->modelGroup = $this->findGroup($groupId);
        $this->modelGroup->save();

        return $this->modelGroup->delete();
    }

    public function selectAllGroups()
    {
        return $this->modelGroup->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectOneByShopIdAndCode($shopId, $code)
    {
        $dt = Carbon::now('Europe/Amsterdam');
        $result = $this->model
        ->where('shop_id', '=', $shopId)
        ->where('active', '=', 1)
        ->where('code', '=', $code)
        // ->where('published_at', '<=', $dt->toDateString('Y-m-d'))
        // ->where('unpublished_at', '>=', $dt->toDateString('Y-m-d'))
        ->with(array('products', 'productCategories', 'sendingMethods', 'paymentMethods'))
        ->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    public function findGroup($groupId)
    {
        return $this->modelGroup->find($groupId);
    }
    
    public function getGroupModel()
    {
        return $this->modelGroup;
    }  
}