<?php namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;

/**
 * ContentController
 *
 * This is the controller of the contents of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Ecommerce\Framework\Services\Content\ContentFacade as ContentService;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $content = ContentService::getModel()->select(
                [
                
                ContentService::getModel()->getTable().'.id',
                ContentService::getModel()->getTable().'.title', ContentService::getModel()->getTable().'.content_group_id', ContentService::getGroupModel()->getTable().'.title as contenttitle']
            )->where(ContentService::getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)


            ->with(array('contentGroup'))        ->leftJoin(ContentService::getGroupModel()->getTable(), ContentService::getGroupModel()->getTable().'.id', '=', ContentService::getModel()->getTable().'.content_group_id');
            
            $datatables = Datatables::of($content)

            ->filterColumn('title', function ($query, $keyword) {
                $query->whereRaw("content.title like ?", ["%{$keyword}%"]);
            })
            ->addColumn('contentgroup', function ($content) {
                return $content->contenttitle;
            })
            ->addColumn('action', function ($content) {
                $deleteLink = Form::deleteajax(url()->route('content.destroy', $content->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('content.edit', $content->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.content.index')->with('content', ContentService::selectAll());
    }

    public function create()
    {
        return view('backend.content.create')->with(array('groups' => ContentService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function store(Request $request)
    {
        $result  = ContentService::create($request->all());
        return ContentService::notificationRedirect('content.index', $result, 'The content was inserted.');
    }

    public function edit($contentId)
    {
        return view('backend.content.edit')->with(array('content' => ContentService::find($contentId), 'groups' => ContentService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function update(Request $request, $contentId)
    {
        $result  = ContentService::updateById($request->all(), $contentId);
        return ContentService::notificationRedirect('content.index', $result, 'The content was updated.');
    }

    public function destroy(Request $request, $contentId)
    {
        $result  = ContentService::destroy($contentId);

        if ($result) {
            Notification::success('The content was deleted.');
            return redirect()->route('content.index');
        }
    }
}
