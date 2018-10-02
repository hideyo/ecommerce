<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\NewsRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        NewsRepository $news
    ) {
        $this->news = $news;

    }

    public function getItem(Request $request, $newsGroupSlug, $slug)
    {
        $news = $this->news->selectOneBySlug(config()->get('app.shop_id'), $slug);
        $newsGroups =  $this->news->selectAllActiveGroupsByShopId(config()->get('app.shop_id'));     

        if ($news) {
            if ($news->slug != $slug or $news->newsGroup->slug != $newsGroupSlug) {
                return redirect()->route('news.item', array('newsGroupSlug' => $news->newsGroup->slug, 'slug' => $news->slug));
            }

            return view('frontend.news.item')->with(array('news' => $news, 'newsGroups' => $newsGroups));
        }

        abort(404);
    }

    public function getByGroup(Request $request, $newsGroupSlug)
    {
        $page = $request->get('page', 1);
        $news = $this->news->selectByGroupAndByShopIdAndPaginate(config()->get('app.shop_id'), $newsGroupSlug, 25);

        $newsGroup = $this->news->selectOneGroupByShopIdAndSlug(config()->get('app.shop_id'), $newsGroupSlug);
        $newsGroups =  $this->news->selectAllActiveGroupsByShopId(config()->get('app.shop_id'));
        
        if ($newsGroup) {
            return view('frontend.news.group')->with(array('selectedPage' => $page, 'news' => $news, 'newsGroups' => $newsGroups, 'newsGroup' => $newsGroup));
        }

        abort(404);
    }

    public function getIndex(Request $request)
    {
        $page = $request->get('page', 1);
        $news = $this->news->selectAllByShopIdAndPaginate(config()->get('app.shop_id'), 25);
        $newsGroups =  $this->news->selectAllActiveGroupsByShopId(config()->get('app.shop_id'));
        if ($news) {
            return view('frontend.news.index')->with(array('selectedPage' => $page, 'news' => $news, 'newsGroups' => $newsGroups));
        }
    }
}