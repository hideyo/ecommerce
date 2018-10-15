<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\Exception\ExceptionFacade as ExceptionService;

use Illuminate\Http\Request;
use Notification;

class ErrorController extends Controller
{

    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = ExceptionService::getModel();
            
            $datatables = \DataTables::of($query)->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax('/admin/general-setting/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/general-setting/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        } else {
            return view('backend.error.index')->with('error', ExceptionService::selectAll());
        }
    }
}
