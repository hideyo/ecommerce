<?php namespace App\Http\Controllers\Backend;

/**
 * AttributeController
 *
 * This is the controller of the attributes of a attribute group
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Attribute\AttributeFacade as AttributeService;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return View
     * @return datatables
     */
    public function index(Request $request, $attributeGroupId)
    {
        if ($request->wantsJson()) {
            $query = AttributeService::getModel()->where('attribute_group_id', '=', $attributeGroupId);
            
            $datatables = DataTables::of($query)
            ->addColumn('action', function ($query) use ($attributeGroupId) {
                $deleteLink = Form::deleteajax(url()->route('attribute.destroy', array('attributeGroupId' => $attributeGroupId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = ' <a href="'.url()->route('attribute.edit', array('attributeGroupId' => $attributeGroupId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>'.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.attribute.index')
            ->with('attributeGroup', AttributeService::findGroup($attributeGroupId));
    }

    /**
     * Show the form for creating a new resource.
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return view
     */
    public function create($attributeGroupId)
    {
        return view('backend.attribute.create')->with(array('attributeGroup' =>  AttributeService::findGroup($attributeGroupId)));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return Redirect
     */
    public function store(Request $request, $attributeGroupId)
    {
        $result  = AttributeService::create($request->all(), $attributeGroupId);
        return AttributeService::notificationRedirect(array('attribute.index', $attributeGroupId), $result, 'The attribute was inserted.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @param  int  $attributeId
     * @return Redirect
     */
    public function edit($attributeGroupId, $attributeId)
    {
        return view('backend.attribute.edit')->with(array('attributeGroupId' => $attributeGroupId, 'attribute' => AttributeService::find($attributeId)));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @param  int  $attributeId
     * @return Redirect
     */
    public function update(Request $request, $attributeGroupId, $attributeId)
    {
        $result  = AttributeService::updateById($request->all(), $attributeGroupId, $attributeId);
        return AttributeService::notificationRedirect(array('attribute.index', $attributeGroupId), $result, 'The attribute was updated.');
    }

    /**
     * Remove the specified resource from storage
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @param  int  $attributeId
     * @return Redirect
     */
    public function destroy($attributeGroupId, $attributeId)
    {
        $result  = AttributeService::destroy($attributeId);

        if ($result) {
            flash('Atrribute was deleted.');
            return redirect()->route('attribute.index', $attributeGroupId);
        }
    }
}