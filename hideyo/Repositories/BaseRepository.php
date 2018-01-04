<?php
namespace Hideyo\Repositories;
use Validator;
 
class BaseRepository 
{
	public function selectAll()
	{
	    return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
	}

    function selectAllByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->get();
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

    public function updateById(array $attributes, $id)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($id));
        if ($validator->fails()) {
            return $validator;
        }

        $this->model = $this->find($id);
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);   
    }    

    public function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }

    public function destroy($modelId)
    {
        $this->model = $this->find($modelId);
        $this->model->save();
        return $this->model->delete();
    }
}