<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\GeneralSetting\GeneralSettingFacade as GeneralSettingService;

class GeneralSettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = GeneralSettingService::getModel()->select(
                [
                
                'id',
                'name', 'value']
            )->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('general-setting.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'));
                $links = '<a href="'.url()->route('general-setting.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.general-setting.index')->with('generalSetting', GeneralSettingService::selectAll());
    }

    public function create()
    {
        return view('backend.general-setting.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = GeneralSettingService::create($request->all());
        return GeneralSettingService::notificationRedirect('general-setting.index', $result, 'The general setting was inserted.');
    }

    public function edit($generalSettingId)
    {
        return view('backend.general-setting.edit')->with(array('generalSetting' => GeneralSettingService::find($generalSettingId)));
    }

    public function update(Request $request, $generalSettingId)
    {
        $result  = GeneralSettingService::updateById($request->all(), $generalSettingId);
        return GeneralSettingService::notificationRedirect('general-setting.index', $result, 'The general setting was updated.');
    }

    public function destroy($generalSettingId)
    {
        $result  = GeneralSettingService::destroy($generalSettingId);
        if ($result) {
            Notification::error('The general setting was deleted.');
            return redirect()->route('general-setting.index');
        }
    }
}
