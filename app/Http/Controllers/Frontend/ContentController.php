<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\ContentRepository;
use Hideyo\Ecommerce\Framework\Repositories\FaqItemRepository;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ContentRepository $content,
        FaqItemRepository $faqItem
    ) { 
        $this->content = $content;
        $this->faqItem = $faqItem;
    }

    public function getItem($slug)
    {
        $content = $this->content->selectOneByShopIdAndSlug(config()->get('app.shop_id'), $slug);

        if ($content) {
            if ($content->slug != $slug) {
                return redirect()->route('text', array('slug' => $content->slug));
            }

            return view('frontend.text.index')->with(array('content' => $content));
        }

        abort(404);
    }

    public function getFaq()
    {
        $faqItems = $this->faqItem->selectAllActiveByShopId(config()->get('app.shop_id'));

        if ($faqItems) {
            return view('frontend.text.faq')->with(array('faqItems' => $faqItems));
        }

        abort(404);
    }
}
