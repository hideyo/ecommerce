<?php namespace App\Http\Controllers\Backend;

/**
 * AttributeController
 *
 * This is the controller of the attributes of a attribute group
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\AttributeRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\AttributeGroupRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class AttributeController extends Controller
{
    public function __construct(
        AttributeRepositoryInterface $attribute, 
        AttributeGroupRepositoryInterface $attributeGroup)
    {
        $this->attributeGroup = $attributeGroup;
        $this->attribute = $attribute;
    }

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

            $query = $this->attribute->getModel()
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
            ->with('attributeGroup', $this->attributeGroup->find($attributeGroupId));
    }

    /**
     * Show the form for creating a new resource.
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return view
     */
    public function create($attributeGroupId)
    {
        return view('backend.attribute.create')->with(array('attributeGroup' =>  $this->attributeGroup->find($attributeGroupId)));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $attributeGroupId for relation with attributeGroup
     * @return Redirect
     */
    public function store(Request $request, $attributeGroupId)
    {
        $result  = $this->attribute->create($request->all(), $attributeGroupId);

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
            array('attributeGroupId' => $attributeGroupId, 'attribute' => $this->attribute->find($attributeId))
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
        $result  = $this->attribute->updateById($request->all(), $attributeGroupId, $attributeId);

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
        $result  = $this->attribute->destroy($attributeId);

        if ($result) {
            Notification::success('Atrribute was deleted.');
            return redirect()->route('attribute.index', $attributeGroupId);
        }
    }
}