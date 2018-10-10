<?php namespace App\Http\Controllers\Backend;

/**
 * ExtraFieldController
 *
 * This is the controller of the product weight types of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\ExtraField\ExtraFieldFacade as ExtraFieldService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;

use Request;
use Notification;
use Datatables;
use Form;

class ExtraFieldController extends Controller
{
    public function index()
    {
        if (Request::wantsJson()) {

            $query = ExtraFieldService::getModel()
            ->select(['id', 'all_products','title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($query)

            ->addColumn('category', function ($query) {
                if ($query->categories) {
                    $output = array();
                    foreach ($query->categories as $categorie) {
                        $output[] = $categorie->title;
                    }

                    return implode(' | ', $output);
                }
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('extra-field.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('extra-field.values.index', $query->id).'" class="btn btn-default btn-sm btn-info"><i class="entypo-pencil"></i>'.$query->values->count().' values</a>
                 <a href="'.url()->route('extra-field.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a> 
                '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.extra-field.index')->with('extraField', ExtraFieldService::selectAll());
    }

    public function create()
    {
        return view('backend.extra-field.create')->with(array('productCategories' => ProductCategoryService::selectAll()->pluck('title', 'id')));
    }

    public function store()
    {
        $result  = ExtraFieldService::create(Request::all());
        return ExtraFieldService::notificationRedirect('extra-field.index', $result, 'The extra field was inserted.');
    }

    public function edit($id)
    {
        return view('backend.extra-field.edit')->with(array('extraField' => ExtraFieldService::find($id), 'productCategories' => ProductCategoryService::selectAll()->pluck('title', 'id')));
    }

    public function update($id)
    {
        $result  = ExtraFieldService::updateById(Request::all(), $id);
        return ExtraFieldService::notificationRedirect('extra-field.index', $result, 'The extra field was updated.');
    }

    public function destroy($id)
    {
        $result  = ExtraFieldService::destroy($id);

        if ($result) {
            Notification::success('Extra field was deleted.');
            return redirect()->route('extra-field.index');
        }
    }
}
