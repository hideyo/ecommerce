<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\OrderStatusEmailTemplate;

class OrderStatusEmailTemplateRepository extends BaseRepository implements OrderStatusEmailTemplateRepositoryInterface
{

    protected $model;

    public function __construct(OrderStatusEmailTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */
    private function rules($id = false, $attributes = false)
    {
        $rules = array(
            'title' => 'required|unique_with:order_status_email_template, shop_id',
            'subject' => 'required',
            'content' => 'required'
        );
        
        if ($id) {
            $rules['title'] =   'required|unique_with:order_status_email_template, shop_id,'.$id;
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
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules($id, $attributes));

        if ($validator->fails()) {
            return $validator;
        }

       
        $this->model = $this->find($id);
        return $this->updateEntity($attributes);
    }

    public function selectBySendingMethodIdAndPaymentMethodId($paymentMethodId, $sendingMethodId)
    {

        $result = $this->model->with(array('sendingPaymentMethodRelated' => function ($query) use ($paymentMethodId, $sendingMethodId) {
            $query->with(array('sendingMethod' => function ($query) use ($sendingMethodId) {
                $query->where('id', '=', $sendingMethodId);
            }, 'paymentMethod' => function ($query) use ($paymentMethodId) {
                $query->where('id', '=', $paymentMethodId);
            }));
        } ))
        ->get();
        if ($result->count()) {
            if ($result->first()->sendingPaymentMethodRelated->sendingMethod and $result->first()->sendingPaymentMethodRelated->paymentMethod) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}