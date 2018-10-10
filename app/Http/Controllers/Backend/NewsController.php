<?php namespace App\Http\Controllers\Backend;

/**
 * NewsController
 *
 * This is the controller of the news of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\News\NewsFacade as NewsService;

class NewsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = NewsService::getModel()->select(
                [
                NewsService::getModel()->getTable().'.id',
                NewsService::getModel()->getTable().'.title',
                NewsService::getGroupModel()->getTable().'.title as newsgroup']
            )->where(NewsService::getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
            ->with(array('newsGroup'))        ->leftJoin(NewsService::getGroupModel()->getTable(), NewsService::getGroupModel()->getTable().'.id', '=', 'news_group_id');
            
            $datatables = Datatables::of($query)
            ->filterColumn('title', function ($query, $keyword) {

                $query->where(
                    function ($query) use ($keyword) {
                        $query->whereRaw("news.title like ?", ["%{$keyword}%"]);
                        ;
                    }
                );
            })
            ->addColumn('newsgroup', function ($query) {
                return $query->newstitle;
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('news.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('news.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.news.index')->with('news', NewsService::selectAll());
    }

    public function create()
    {
        return view('backend.news.create')->with(array('groups' => NewsService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function store()
    {
        $result  = NewsService::create($this->request->all());
        return NewsService::notificationRedirect('news.index', $result, 'The news item was inserted.');
    }

    public function edit($newsId)
    {
        return view('backend.news.edit')->with(array('news' => NewsService::find($newsId), 'groups' => NewsService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function reDirectoryAllImages()
    {
        $this->newsImage->reDirectoryAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);

        return redirect()->route('news.index');
    }

    public function refactorAllImages()
    {
        $this->newsImage->refactorAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);

        return redirect()->route('news.index');
    }
    
    public function update($newsId)
    {
        $result  = NewsService::updateById($this->request->all(), $newsId);
        return NewsService::notificationRedirect('news.index', $result, 'The news item was inserted.');
    }

    public function destroy($newsId)
    {
        $result  = NewsService::destroy($newsId);

        if ($result) {
            Notification::success('The news was deleted.');
            return redirect()->route('news.index');
        }
    }
}
