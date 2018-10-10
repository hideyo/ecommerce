<?php namespace App\Http\Controllers\Backend;
/**
 * ExtraFieldDefaultValueController
 *
 * This is the controller of the product weight types of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\ExtraField\ExtraFieldFacade as ExtraFieldService;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class ExtraFieldDefaultValueController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index($extraFieldId)
    {
        if ($this->request->wantsJson()) {

            $query = ExtraFieldService::getValueModel()->select(
                [
                
                'id',
                'value']
            )->where('extra_field_id', '=', $extraFieldId);
            
            $datatables = Datatables::of($query)->addColumn('action', function ($query) use ($extraFieldId) {
                $deleteLink = Form::deleteajax(url()->route('extra-field.values.destroy', array('ExtraFieldId' => $extraFieldId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = ' <a href="'.url()->route('extra-field.values.edit', array('ExtraFieldId' => $extraFieldId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a> 
                '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.extra-field-default-value.index')->with('extraField', ExtraFieldService::find($extraFieldId));
    }

    public function create($extraFieldId)
    {
        return view('backend.extra-field-default-value.create')->with(array('extraField' =>  ExtraFieldService::find($extraFieldId)));
    }

    public function store($extraFieldId)
    {
        $result  = ExtraFieldService::createValue($this->request->all(), $extraFieldId);
        return ExtraFieldService::notificationRedirect(array('extra-field.values.index', $extraFieldId), $result, 'The extra field was inserted.');
    }

    public function edit($extraFieldId, $id)
    {
        return view('backend.extra-field-default-value.edit')->with(array('extraFieldDefaultValue' => ExtraFieldService::findValue($id)));
    }

    public function update($extraFieldId, $id)
    {
        $result  = ExtraFieldService::updateValueById($this->request->all(), $extraFieldId, $id);
        return ExtraFieldService::notificationRedirect(array('extra-field.values.index', $extraFieldId), $result, 'The extra field was updated.');
    }

    public function destroy($extraFieldId, $id)
    {
        $result  = ExtraFieldService::destroyValue($id);

        if ($result) {
            Notification::success('Extra field was deleted.');
            return redirect()->route('extra-field.values.index', $extraFieldId);
        }
    }
}
