<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ClientAddress;
 
class ClientAddressRepository implements ClientAddressRepositoryInterface
{

    protected $model;

    public function __construct(ClientAddress $model)
    {
        $this->model = $model;
    }
  
    public function create(array $attributes, $clientId)
    {
        $userId = \auth('hideyobackend')->user()->id;
        $attributes['modified_by_user_id'] = $userId;
        $attributes['client_id'] = $clientId;
  
        $this->model->fill($attributes);
        $this->model->save();
        
        return $this->model;
    }

    public function createByClient(array $attributes, $clientId)
    {
        $attributes['client_id'] = $clientId;
  
        $this->model->fill($attributes);
        $this->model->save();
        
        return $this->model;
    }

    public function updateById(array $attributes, $clientId, $id)
    {
        $attributes['modified_by_user_id'] = \auth('hideyobackend')->user()->id;
        $this->model = $this->find($id);
        return $this->updateEntity($attributes);
    }

    public function updateByIdAndShopId($shopId, array $attributes, $clientId, $id)
    {
        $this->model = $this->find($id);
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

    public function destroy($id)
    {
        $this->model = $this->find($id);
        $filename = $this->model->path;

        if (\File::exists($filename)) {
            \File::delete($filename);
        }

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', \auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectAllByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->get();
    }

    function selectAllByClientId($clientId)
    {
         return $this->model
         ->where('client_id', '=', $clientId)
         ->get();
    }

    function selectOneByClientIdAndId($clientId, $id)
    {
        $result = $this->model
        ->where('client_id', '=', $clientId)
        ->where('id', '=', $id)
        ->get()
        ->first();

        return $result;
    }
    
    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getModel()
    {
        return $this->model;
    }

}
