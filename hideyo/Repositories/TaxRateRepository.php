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
    public function rules($taxRateId = false)
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
}