<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Language;
 
class LanguageRepository implements LanguageRepositoryInterface
{

    protected $model;

    public function __construct(Language $model)
    {
        $this->model = $model;
    }
  
    public function create(array $attributes)
    {
        $attributes['shop_id'] = \auth()->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = \auth()->user()->id;

        $this->model->fill($attributes);
        $this->model->save();
        
        return $this->model;
    }

    public function updateById(array $attributes, $id)
    {
        $this->model = $this->find($id);
        $attributes['shop_id'] = \auth()->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = \auth()->user()->id;

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

    public function destroy($id)
    {
        $this->model = $this->find($id);
        $this->model->save();

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', \auth()->user()->selected_shop_id)->get();
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
