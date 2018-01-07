<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\GeneralSettingRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class GeneralSettingController extends Controller
{

    public function __construct(Request $request, GeneralSettingRepositoryInterface $generalSetting)
    {
        $this->request = $request;
        $this->generalSetting = $generalSetting;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->generalSetting->getModel()->select(
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
        
        return view('backend.general-setting.index')->with('generalSetting', $this->generalSetting->selectAll());
    }

    public function create()
    {
        return view('backend.general-setting.create')->with(array());
    }

    public function store()
    {
        $result  = $this->generalSetting->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The general setting was inserted.');
            return redirect()->route('general-setting.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function edit($generalSettingId)
    {
        return view('backend.general-setting.edit')->with(array('generalSetting' => $this->generalSetting->find($generalSettingId)));
    }

    public function update($generalSettingId)
    {
        $result  = $this->generalSetting->updateById($this->request->all(), $generalSettingId);

        if (isset($result->id)) {
            Notification::success('The general setting was updated.');
            return redirect()->route('general-setting.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function destroy($generalSettingId)
    {
        $result  = $this->generalSetting->destroy($generalSettingId);
        if ($result) {
            Notification::error('The general setting was deleted.');
            return redirect()->route('general-setting.index');
        }
    }
}
