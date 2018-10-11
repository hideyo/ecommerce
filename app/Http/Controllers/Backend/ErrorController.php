<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Repositories\ExceptionRepository;

use Illuminate\Http\Request;
use Notification;

class ErrorController extends Controller
{

    public function __construct(ExceptionRepository $error)
    {
        $this->error = $error;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = $this->error->getModel();
            
            $datatables = \Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax('/admin/general-setting/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/general-setting/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        } else {
            return view('backend.error.index')->with('error', $this->error->selectAll());
        }
    }
}
