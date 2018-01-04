<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\OrderStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class OrderStatusRepository extends BaseRepository implements OrderStatusRepositoryInterface
{

    protected $model;

    public function __construct(OrderStatus $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */  
    public function rules($id = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique_with:order_status, shop_id'

        );
        
        if ($id) {
            $rules['title'] =   'required|between:4,65|unique_with:order_status, shop_id, '.$id.' = id';
        }

        return $rules;
    }   
}