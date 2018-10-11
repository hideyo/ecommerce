<?php namespace App\Http\Controllers\Backend;

/**
 * NewsGroupController
 *
 * This is the controller of the newss of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;

use Hideyo\Ecommerce\Framework\Services\News\NewsFacade as NewsService;

class NewsGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = NewsService::getGroupModel()->select(
                [
                'id',
                'title']
            )->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = \Datatables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax(url()->route('news-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('news-group.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.news_group.index')->with('newsGroup', NewsService::selectAll());
    }

    public function create()
    {
        return view('backend.news_group.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = NewsService::createGroup($request->all());
        return NewsService::notificationRedirect('news-group.index', $result, 'The news group was inserted.');
    }

    public function edit($newsGroupId)
    {
        return view('backend.news_group.edit')->with(array('newsGroup' => NewsService::findGroup($newsGroupId)));
    }

    public function update(Request $request, $newsGroupId)
    {
        $result  = NewsService::updateGroupById($request->all(), $newsGroupId);
        return NewsService::notificationRedirect('news-group.index', $result, 'The news group was updated.');
    }

    public function destroy($newsGroupId)
    {
        $result  = NewsService::destroyGroup($newsGroupId);

        if ($result) {
            Notification::success('The news group was deleted.');
            return redirect()->route('news-group.index');
        }
    }
}
