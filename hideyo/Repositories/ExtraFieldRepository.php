<?php
namespace Hideyo\Repositories;

use Hideyo\Models\ExtraField;
use Hideyo\Models\ExtraFieldDefaultValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;

class ExtraFieldRepository extends BaseRepository  implements ExtraFieldRepositoryInterface
{

    protected $model;

    public function __construct(
        ExtraField $model, 
        ExtraFieldDefaultValue $modelValue)
    {
        $this->model = $model;
        $this->modelValue = $modelValue;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $extraFieldId id attribute model    
     * @return array
     */
    private function rules($extraFieldId = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
        );
        
        if ($extraFieldId) {
            $rules['title'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$extraFieldId.' = id';
        }

        return $rules;
    }

    private function rulesValue($defaultValueId = false)
    {
        if ($defaultValueId) {
            return [
                'value' => 'required|unique_with:'.$this->modelValue->getTable().',extra_field_id,'.$defaultValueId,
            ];
        } else {
            return [
                'value' => 'required|unique_with:'.$this->modelValue->getTable().',extra_field_id'
            ];
        }
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

        if (isset($attributes['categories'])) {
            $this->model->categories()->sync($attributes['categories']);
        }
        
        return $this->model;
    }

    public function createValue(array $attributes, $extraFieldId)
    {
        $attributes['extra_field_id'] = $extraFieldId;
        $validator = Validator::make($attributes, $this->rulesValue());

        if ($validator->fails()) {
            return $validator;
        } else {
            $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            $this->modelValue->fill($attributes);
            $this->modelValue->save();
            return $this->modelValue;
        }
    }


    public function updateById(array $attributes, $extraFieldId)
    {
        $this->model = $this->find($extraFieldId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($extraFieldId));

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
            
            $this->model->categories()->sync(array());

            if (isset($attributes['categories'])) {
                $this->model->categories()->sync($attributes['categories']);
            }

            $this->model->save();
        }

        return $this->model;
    }


    public function updateValueById(array $attributes, $extraFieldId, $defaultValueId)
    {
        $attributes['extra_field_id'] = $extraFieldId;
        $validator = Validator::make($attributes, $this->rulesValue($defaultValueId));

        if ($validator->fails()) {
            return $validator;
        }

        $this->modelValue = $this->findValue($defaultValueId);
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateValueEntity($attributes);
    }

    public function updateValueEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelValue->fill($attributes);
            $this->modelValue->save();
        }

        return $this->modelValue;
    }

    public function destroyValue($defaultValueId)
    {
        $this->modelValue = $this->findValue($defaultValueId);
        $this->modelValue->save();

        return $this->modelValue->delete();
    }

    public function findValue($defaultValueId)
    {
        return $this->modelValue->find($defaultValueId);
    }

    public function getValueModel()
    {
        return $this->modelValue;
    }

    public function selectAllByAllProductsAndProductCategoryId($productCategoryId)
    {
        return $this->model->select('extra_field.*')
        ->leftJoin('product_category_related_extra_field', 'extra_field.id', '=', 'product_category_related_extra_field.extra_field_id')
        
        ->where(function ($query) use ($productCategoryId) {

            $query->where('all_products', '=', 1)
            ->orWhereHas('categories', function ($query) use ($productCategoryId) {

                $query->where('product_category_id', '=', $productCategoryId);
            });
        })

        ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }    
}