<?php namespace App\Http\Controllers\Backend;

/**
 * RedirectController
 *
 * This is the controller of the redirects of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Redirect\RedirectFacade as RedirectService;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;
use Illuminate\Http\Request;
use Excel;
use DataTables;

class RedirectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = RedirectService::selectAll();
            $datatables = DataTables::of($query)

            ->addColumn('url', function ($query) {
                return '<a href="'.$query->url.'" target="_blank">'.$query->url.'</a>';
            })

            ->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax(url()->route('redirect.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('redirect.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.redirect.index')->with('redirect', RedirectService::selectAll());
    }

    public function create()
    {
        $shops = ShopService::selectAll()->pluck('title', 'id')->toArray();
        return view('backend.redirect.create')->with(array('shops' => $shops));
    }

    public function store()
    {
        $result  = RedirectService::create($request->all());
        return RedirectService::notificationRedirect('redirect.index', $result, 'The redirect was inserted.');
    }

    public function edit($redirectId)
    {
                $shops = ShopService::selectAll()->pluck('title', 'id');
        return view('backend.redirect.edit')->with(array(
            'redirect' => RedirectService::find($redirectId),
            'shops' => $shops
        ));
    }

    public function getImport()
    {
        return view('backend.redirect.import')->with(array());
    }

    public function postImport()
    {
        $file = $request->file('file');
        Excel::load($file, function ($reader) {

            $results = $reader->get();

            if ($results->count()) {
                $result = RedirectService::importCsv($results, auth('hideyobackend')->user()->selected_shop_id);

                flash('The redirects are imported.');
       
                return redirect()->route('redirect.index');
            } else {
                flash('The redirects imported are failed.');
                return redirect()->route('redirect.import');
            }
        });
    }

    public function getExport()
    {
        $result  =  RedirectService::selectAll();

        Excel::create('redirects', function ($excel) use ($result) {

            $excel->sheet('Redirects', function ($sheet) use ($result) {
                $newArray = array();
                foreach ($result as $row) {
                    $newArray[$row->id] = array(
                        'active' => $row->active,
                        'url' => $row->url,
                        'redirect_url' => $row->redirect_url
                    );
                }

                $sheet->fromArray($newArray);
            });
        })->download('xls');
    }

    public function update($redirectId)
    {
        $result  = RedirectService::updateById($request->all(), $redirectId);
        return RedirectService::notificationRedirect('redirect.index', $result, 'The redirect was updated.');
    }

    public function destroy($redirectId)
    {
        $result  = RedirectService::destroy($redirectId);

        if ($result) {
            flash('Redirect item is deleted.');
            return redirect()->route('redirect.index');
        }
    }
}
