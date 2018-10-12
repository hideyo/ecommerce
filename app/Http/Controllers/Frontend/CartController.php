<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\Cart\Entity\CartRepository;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;
use BrowserDetect;
use Cart;

class CartController extends Controller
{
    public function __construct(    
        CartRepository $cart)
    {
        $this->cart = $cart;
    }

    public function getIndex()
    {
        $sendingMethodsList = SendingMethodService::selectAllActiveByShopId(config()->get('app.shop_id'));
        $paymentMethodsList = $this->getPaymentMethodsList($sendingMethodsList);
  
        if (!Cart::getContent()->count()) {
            return redirect()->to('cart');
        }
            
        if($sendingMethodsList->count() AND !app('cart')->getConditionsByType('sending_method')->count()) {
            self::updateSendingMethod($sendingMethodsList->first()->id);
        }      

        if ($paymentMethodsList AND !app('cart')->getConditionsByType('payment_method')->count()) {
            $this->cart->updatePaymentMethod($paymentMethodsList->first()->id);
        }

        $template = "frontend.cart.index";

        if (BrowserDetect::isMobile()) {
            $template = "frontend.cart.index-mobile";
        }

        return view($template)->with(array( 
            'user' => auth('web')->user(), 
            'sendingMethodsList' => $sendingMethodsList
        ));
    }

    public function getPaymentMethodsList($sendingMethodsList) 
    {
        if ($sendingMethodsList->first()) {     
            return $paymentMethodsList = $sendingMethodsList->first()->relatedPaymentMethods;
        }
        
        return $paymentMethodsList = PaymentMethodService::selectAllActiveByShopId(config()->get('app.shop_id'));       
    }

    public function postProduct(Request $request, $productId, $productCombinationId = false)
    {
        $result = $this->cart->postProduct(
            $request->get('product_id'), 
            $productCombinationId, 
            $request->get('leading_attribute_id'), 
            $request->get('product_attribute_id'),
            $request->get('amount')
        );

        if($result){
            return response()->json(array(
                'result' => true, 
                'producttotal' => app('cart')->getContent()->count(),
                'total_inc_tax_number_format' => app('cart')->getTotalWithTax(),
                'total_ex_tax_number_format' => app('cart')->getTotalWithoutTax()
            ));
        }
        
        return response()->json(false);
    }

    public function deleteProduct($productId)
    {
        $result = app('cart')->remove($productId);

        if (app('cart')->getContent()->count()) {
            return response()->json(array('result' => $result, 'totals' => true, 'producttotal' => app('cart')->getContent()->count()));
        }
        
        return response()->json(false);        
    }

    public function updateAmountProduct(Request $request, $productId, $amount)
    {
        $this->cart->updateAmountProduct($productId, $amount, $request->get('leading_attribute_id'), $request->get('product_attribute_id'));

        if (app('cart')->getContent()->count() AND app('cart')->get($productId)) {
            $product = app('cart')->get($productId);
            $amountNa = false;

            if($product->quantity < $amount) {
                $amountNa = view('frontend.cart.amount-na')->with(array('product' => $product))->render();
            }
            
            return response()->json(
                array(
                    'amountNa' => $amountNa,
                    'product_id' => $productId,
                    'product' => $product, 
                    'total_price_inc_tax_number_format' => $product->getOriginalPriceWithTaxSum(),
                    'total_price_ex_tax_number_format' => $product->getOriginalPriceWithoutTaxSum()
                )
            );
        }
        
        return response()->json(false);
    }

    public function getBasketDialog()
    {        
        return view('frontend.cart.basket-dialog');
    }

    public function getTotalReload()
    {
        $sendingMethodsList = SendingMethodService::selectAllActiveByShopId(config()->get('app.shop_id'));
        $paymentMethodsList = $this->getPaymentMethodsList($sendingMethodsList);
        
        $template = "frontend.cart._totals";
        
        if (BrowserDetect::isMobile()) {
            $template = "frontend.cart._totals-mobile";
        }

        return view('frontend.cart._totals')->with(array('sendingMethodsList' => $sendingMethodsList));  
    }

    public function updateSendingMethod($sendingMethodId)
    {
        $this->cart->updateSendingMethod($sendingMethodId);
        return response()->json(array('sending_method' => app('cart')->getConditionsByType('sending_method')));
    }

    public function updatePaymentMethod($paymentMethodId)
    {
        $this->cart->updatePaymentMethod($paymentMethodId);
        return response()->json(array('payment_method' => app('cart')->getConditionsByType('payment_method')));
    }
}