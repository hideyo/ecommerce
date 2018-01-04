<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\OrderPaymentLog;
 
class OrderPaymentLogRepository extends BaseRepository  implements OrderPaymentLogRepositoryInterface
{

    protected $model;

    public function __construct(OrderPaymentLog $model)
    {
        $this->model = $model;
    }
  
    public function create(array $attributes, $orderId)
    {
        $userId = auth('hideyobackend')->user()->id;
        $attributes['modified_by_user_id'] = $userId;
        $attributes['order_id'] = $orderId;
  
        $this->model->fill($attributes);
        $this->model->save();
        
        return $this->model;
    }


    public function createByUser(array $attributes, $orderId)
    {
        $attributes['order_id'] = $orderId;
  
        $this->model->fill($attributes);
        $this->model->save();
        
        return $this->model;
    }

    public function updateById(array $attributes, $orderId, $id)
    {
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->find($id);
        return $this->updateEntity($attributes);
    }

    public function destroy($id)
    {
        $this->model = $this->find($id);
        $filename = $this->model->path;

        if (\File::exists($filename)) {
            \File::delete($filename);
        }

        return $this->model->delete();
    }

    function selectAllByOrderId($orderId)
    {
         return $this->model->where('order_id', '=', $orderId)->get();
    }

}
