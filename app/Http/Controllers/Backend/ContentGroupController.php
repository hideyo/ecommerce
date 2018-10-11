<?php namespace App\Http\Controllers\Backend;

/**
 * ContentGroupController
 *
 * This is the controller of the content groups of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use DataTables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Content\ContentFacade as ContentService;

class ContentGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = ContentService::getGroupModel()
            ->select(['id', 'title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = DataTables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('content-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('content-group.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.content_group.index')->with('contentGroup', ContentService::selectAll());
    }

    public function create()
    {
        return view('backend.content_group.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = ContentService::createGroup($request->all());
        return ContentService::notificationRedirect('content-group.index', $result, 'The content group was inserted.');
    }

    public function edit($contentGroupId)
    {
        return view('backend.content_group.edit')->with(array('contentGroup' => ContentService::findGroup($contentGroupId)));
    }

    public function update(Request $request, $contentGroupId)
    {
        $result  = ContentService::updateGroupById($request->all(), $contentGroupId);
        return ContentService::notificationRedirect('content-group.index', $result, 'The content group was updated.');
    }

    /**
     * Remove the specified resource from storage
     * @param  int  $contentGroupId
     * @return Redirect
     */
    public function destroy($contentGroupId)
    {
        $result  = ContentService::destroyGroup($contentGroupId);

        if ($result) {
            Notification::success('The content was deleted.');
            return redirect()->route('content-group.index');
        }
    }
}
