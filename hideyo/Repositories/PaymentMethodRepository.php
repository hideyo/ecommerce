<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\PaymentMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Auth;
 
class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{

    protected $model;

    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $paymentMethodId id attribute model    
     * @return array
     */
    private function rules($paymentMethodId = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'price'  => 'numeric|required'
        );
        
        if ($paymentMethodId) {
            $rules['title'] =   $rules['title'].','.$paymentMethodId.' = id';
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

    public function updateById(array $attributes, $paymentMethodId)
    {
        $this->model = $this->find($paymentMethodId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($paymentMethodId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;


        return $this->updateEntity($attributes);
    }

    private function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }

    public function destroy($paymentMethodId)
    {
        $this->model = $this->find($paymentMethodId);
        $this->model->save();

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->get();
    }

    function selectOneByShopIdAndId($shopId, $paymentMethodId)
    {
        $result = $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $paymentMethodId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    function selectOneById($paymentMethodId)
    {
        $result = $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('active', '=', 1)->where('id', '=', $paymentMethodId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }
    
    public function find($paymentMethodId)
    {
        return $this->model->find($paymentMethodId);
    }

    public function getModel() {
        return $this->model;
    }
}
