<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Content\ContentFacade as ContentService;
use Hideyo\Ecommerce\Framework\Services\Faq\FaqFacade as FaqService;

class ContentController extends Controller
{
    public function getItem($slug)
    {
        $content = ContentService::selectOneByShopIdAndSlug(config()->get('app.shop_id'), $slug);

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
        $faqItems = FaqService::selectAllActiveByShopId(config()->get('app.shop_id'));

        if ($faqItems) {
            return view('frontend.text.faq')->with(array('faqItems' => $faqItems));
        }

        abort(404);
    }
}