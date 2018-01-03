<?php
namespace Hideyo\Repositories;

 
class BaseRepository 
{
	public function selectAll()
	{
	    return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
	}

    public function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->get();
    }

    public function getModel() {
        return $this->model;
    }

    public function find($modelId)
    {
        return $this->model->find($modelId);
    }

    public function destroy($modelId)
    {
        $this->model = $this->find($modelId);
        $this->model->save();
        return $this->model->delete();
    } 

}