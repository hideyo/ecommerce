<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\FaqItem;
use Hideyo\Models\FaqItemGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class FaqItemRepository extends BaseRepository implements FaqItemRepositoryInterface
{

    protected $model;

    public function __construct(FaqItem $model, FaqItemGroup $modelFaqItemGroup)
    {
        $this->model = $model;
        $this->modelFaqItemGroup = $modelFaqItemGroup;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $faqItemId id attribute model    
     * @return array
     */
    private function rules($faqItemId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
            );
        } else {
            $rules = array(
                'question'                 => 'required|between:4,65|unique:'.$this->model->getTable().''
            );
            
            if ($faqItemId) {
                $rules['question'] =   'required|between:4,65|unique:'.$this->model->getTable().',question,'.$faqItemId;
            }
        }

        return $rules;
    }

    public function selectAllGroups()
    {
        return $this->modelFaqItemGroup->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    function selectOneById($faqItemId)
    {
        $result = $this->model->with(array('relatedPaymentMethods'))->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('active', '=', 1)->where('id', '=', $faqItemId)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->get();
    }
        
}
