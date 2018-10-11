<?php namespace App\Http\Controllers\Backend;

/**
 * HtmlBlockController
 *
 * This is the controller of the htmlBlocks of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\HtmlBlock\HtmlBlockFacade as HtmlBlockService;


class HtmlBlockController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = HtmlBlockService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($query)

            ->addColumn('active', function ($query) {
                if ($query->active) {
                    return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-ok icon-green"></span></a>';
                }
                return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-remove icon-red"></span></a>';
            })
            ->addColumn('image', function ($query) {
                if ($query->image_file_name) {
                    return '<img src="'.config('hideyo.public_path').'/html_block/'.$query->id.'/'.$query->image_file_name.'" width="200px" />';
                }
            })
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('html-block.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $copy = '<a href="/admin/html-block/'.$query->id.'/copy" class="btn btn-default btn-sm btn-info"><i class="entypo-pencil"></i>Copy</a>';
                $links = '<a href="'.url()->route('html-block.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a> '.$copy.' '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.html-block.index')->with('htmlBlock', HtmlBlockService::selectAll());
    }

    public function create()
    {
        return view('backend.html-block.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = HtmlBlockService::create($request->all());
        return HtmlBlockService::notificationRedirect('html-block.index', $result, 'The html block was inserted.');     
    }

    public function changeActive($htmlBlockId)
    {
        $result = HtmlBlockService::changeActive($htmlBlockId);
        return response()->json($result);
    }

    public function edit($htmlBlockId)
    {
        return view('backend.html-block.edit')->with(array('htmlBlock' => HtmlBlockService::find($htmlBlockId)));
    }

    public function update(Request $request, $htmlBlockId)
    {
        $result  = HtmlBlockService::updateById($request->all(), $htmlBlockId);
            return HtmlBlockService::notificationRedirect('html-block.index', $result, 'The html block was updated.');     
    }

    public function copy($htmlBlockId)
    {
        $htmlBlock = HtmlBlockService::find($htmlBlockId);

        return view('backend.html-block.copy')->with(
            array(
            'htmlBlock' => $htmlBlock
            )
        );
    }
    
    public function storeCopy(Request $request, $htmlBlockId)
    {
        $htmlBlock = HtmlBlockService::find($htmlBlockId);

        if($htmlBlock) {
            $result  = HtmlBlockService::createCopy($request->all(), $htmlBlockId);
            return HtmlBlockService::notificationRedirect('html-block.index', $result, 'The html block was inserted.');      
        }

        return redirect()->back()->withInput();
    }

    public function destroy($htmlBlockId)
    {
        $result  = HtmlBlockService::destroy($htmlBlockId);

        if ($result) {
            Notification::success('The html block was deleted.');
            return redirect()->route('html-block.index');
        }
    }
}
