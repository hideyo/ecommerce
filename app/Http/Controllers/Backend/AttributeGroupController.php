<?php namespace App\Http\Controllers\Backend;

/**
 * AttributeGroupController
 *
 * This is the controller of the attributes groups used by products of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Attribute\AttributeFacade as AttributeService;

class AttributeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return View
     * @return datatables
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = AttributeService::getGroupModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = DataTables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('attribute-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '
                    <a href="'.url()->route('attribute.index', $query->id).'" class="btn btn-sm btn-info"><i class="entypo-pencil"></i>'.$query->attributes->count().' Attributes</a>
                    <a href="'.url()->route('attribute-group.edit', $query->id).'" class="btn btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a> 
                '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
            
        return view('backend.attribute-group.index');
    }

    public function create()
    {
        return view('backend.attribute-group.create');
    }

    public function store(Request $request)
    {
        $result  = AttributeService::createGroup($request->all());
        return AttributeService::notificationRedirect('attribute-group.index', $result, 'The attribute group was inserted.');
    }

    public function edit($attributeGroupId)
    {
        return view('backend.attribute-group.edit')->with(array('attributeGroup' => AttributeService::findGroup($attributeGroupId)));
    }

    public function update(Request $request, $attributeGroupId)
    {
        $result  = AttributeService::updateGroupById($request->all(), $attributeGroupId);
        return AttributeService::notificationRedirect('attribute-group.index', $result, 'The attribute group was updated.');
    }

    public function destroy($attributeGroupId)
    {
        $result  = AttributeService::destroyGroup($attributeGroupId);

        if ($result) {
            flash('Attribute group was deleted.');
            return redirect()->route('attribute-group.index');
        }
    }
}
