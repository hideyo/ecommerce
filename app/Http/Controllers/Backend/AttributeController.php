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
use Notification;
use Datatables;
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

            $query = AttributeService::getModel()
            ->select(['id','value'])
            ->where('attribute_group_id', '=', $attributeGroupId);
            
            $datatables = Datatables::of($query)
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

        if (isset($result->id)) {
            Notification::success('The extra field was inserted.');
            return redirect()->route('attribute.index', $attributeGroupId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @param  int  $attributeId
     * @return Redirect
     */
    public function edit($attributeGroupId, $attributeId)
    {
        return view('backend.attribute.edit')->with(
            array('attributeGroupId' => $attributeGroupId, 'attribute' => AttributeService::find($attributeId))
        );
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

        if (isset($result->id)) {
            Notification::success('Attribute was updated.');
            return redirect()->route('attribute.index', $attributeGroupId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @param  int  $attributeId
     * @return Redirect
     */
    public function destroy($attributeGroupId, $attributeId)
    {
        $result  = Attribute::destroy($attributeId);

        if ($result) {
            Notification::success('Atrribute was deleted.');
            return redirect()->route('attribute.index', $attributeGroupId);
        }
    }
}