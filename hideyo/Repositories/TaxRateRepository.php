<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\TaxRate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Auth;
 
class TaxRateRepository extends BaseRepository implements TaxRateRepositoryInterface
{

    protected $model;

    public function __construct(TaxRate $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $taxRateId id attribute model    
     * @return array
     */
    private function rules($taxRateId = false)
    {
        $rules = array(
            'title' => 'required|between:2,65|unique_with:'.$this->model->getTable().', shop_id',
            'rate'  => 'numeric|required'
        );
        
        if($taxRateId) {
            $rules['title'] =   $rules['title'].','.$taxRateId.' = id';
        }

        return $rules;
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

    public function updateById(array $attributes, $taxRateId)
    {
        $this->model = $this->find($taxRateId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($taxRateId));

        if ($validator->fails()) {
            return $validator;
        }
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        return $this->updateEntity($attributes);
    }
}