<?php namespace App\Http\Controllers\Backend;

/**
 * AttributeGroupController
 *
 * This is the controller of the attributes groups used by products of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\AttributeGroupRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use DB;
use Auth;
use Datatables;
use Form;

class AttributeGroupController extends Controller
{
    public function __construct(
        AttributeGroupRepositoryInterface $attributeGroup)
    {
        $this->attributeGroup = $attributeGroup;
    }

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

            $query = $this->attributeGroup->getModel()
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),'id','title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
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

    /**
     * Show the form for creating a new resource.
     * @return view
     */
    public function create()
    {
        return view('backend.attribute-group.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $result  = $this->attributeGroup->create($request->all());

        if (isset($result->id)) {
            Notification::success('The extra field was inserted.');
            return redirect()->route('attribute-group.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }      

        return redirect()->back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $attributeGroupId
     * @return Redirect
     */
    public function edit($attributeGroupId)
    {
        return view('backend.attribute-group.edit')
        ->with(
            array(
                'attributeGroup' => $this->attributeGroup->find($attributeGroupId)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $attributeGroupId
     * @return Redirect
     */
    public function update(Request $request, $attributeGroupId)
    {
        $result  = $this->attributeGroup->updateById($request->all(), $attributeGroupId);

        if (isset($result->id)) {
            Notification::success('Attribute group was updated.');
            return redirect()->route('attribute-group.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
    
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storagep
     * @param  int  $attributeGroupId
     * @return Redirect
     */
    public function destroy($attributeGroupId)
    {
        $result  = $this->attributeGroup->destroy($attributeGroupId);

        if ($result) {
            Notification::success('Attribute group was deleted.');
            return redirect()->route('attribute-group.index');
        }
    }
}
