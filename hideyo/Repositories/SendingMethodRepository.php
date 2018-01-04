<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\SendingMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
 
class SendingMethodRepository extends BaseRepository implements SendingMethodRepositoryInterface 
{

    protected $model;

    public function __construct(SendingMethod $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $sendingMethodId id attribute model    
     * @return array
     */
    private function rules($sendingMethodId = false)
    {
        $rules = array(
            'active' => 'required|integer',
            'title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'price'  => 'numeric|required',
            'minimal_weight'  => 'numeric|nullable',
            'maximal_weight'  => 'numeric|nullable'
        );
        
        if($sendingMethodId) {
            $rules['title'] =   $rules['title'].','.$sendingMethodId.' = id';
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

        if (isset($attributes['payment_methods'])) {
            $this->model->relatedPaymentMethods()->sync($attributes['payment_methods']);
        }
   
        return $this->model;
    }

    public function updateById(array $attributes, $sendingMethodId)
    {
        $this->model = $this->find($sendingMethodId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($sendingMethodId));
       
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
            if (isset($attributes['payment_methods'])) {
                $this->model->relatedPaymentMethods()->sync($attributes['payment_methods']);
            }

            $this->model->save();
        }

        return $this->model;
    }

    function selectOneByShopIdAndId($shopId, $sendingMethodId)
    {
        $result = $this->model->with(array('relatedPaymentMethods' => function ($query) {
            $query->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $sendingMethodId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    } 
}
