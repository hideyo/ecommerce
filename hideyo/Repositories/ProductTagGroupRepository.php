<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\ProductTagGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class ProductTagGroupRepository extends BaseRepository implements ProductTagGroupRepositoryInterface
{

    protected $model;

    public function __construct(ProductTagGroup $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $tagGroupId id attribute model    
     * @return array
     */
    private function rules($tagGroupId = false)
    {
        $rules = array(
            'tag' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'

        );
        
        if ($tagGroupId) {
            $rules['tag'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$tagGroupId.' = id';
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

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            
        $this->model->fill($attributes);
        $this->model->save();

        if (isset($attributes['products'])) {
            $this->model->relatedProducts()->sync($attributes['products']);
        }
   
        return $this->model;
    }

    public function updateById(array $attributes, $tagGroupId)
    {
        $this->model = $this->find($tagGroupId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = \Validator::make($attributes, $this->rules($tagGroupId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }

    function selectAllByTagAndShopId($shopId, $tag)
    {
        $result = $this->model->with(array('relatedProducts' => function ($query) {
            $query->with(array('productCategory', 'productImages' => function ($query) {
                $query->orderBy('rank', 'asc');
            }))->where('active', '=', 1);
        }))->where('shop_id', '=', $shopId)->where('tag', '=', $tag)->get();
        if ($result->count()) {
            return $result->first()->relatedProducts;
        } else {
            return false;
        }
    } 
}