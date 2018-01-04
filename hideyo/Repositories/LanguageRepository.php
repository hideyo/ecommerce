<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Language;
 
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
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
}