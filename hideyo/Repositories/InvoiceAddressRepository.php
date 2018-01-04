<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\InvoiceAddress;
 
class InvoiceAddressRepository extends BaseRepository implements InvoiceAddressRepositoryInterface
{

    protected $model;

    public function __construct(InvoiceAddress $model)
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

    public function updateById(array $attributes, $orderId, $id)
    {
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->find($id);
        return $this->updateEntity($attributes);
    }

    function selectAllByInvoiceId($orderId)
    {
         return $this->model->where('order_id', '=', $orderId)->get();
    }  
}