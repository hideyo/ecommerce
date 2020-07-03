<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;
use Browser;
use Cart;

class CartController extends Controller
{
    public function getIndex()
    {
        $sendingMethodsList = SendingMethodService::selectAllActiveByShopId(config()->get('app.shop_id'));
        $paymentMethodsList = $this->getPaymentMethodsList($sendingMethodsList);
  
        if (!Cart::getContent()->count()) {
            return redirect()->to('cart');
        }
            
        if($sendingMethodsList->count() AND !Cart::getConditionsByType('sending_method')->count()) {
            self::updateSendingMethod($sendingMethodsList->first()->id);
        }      

        if ($paymentMethodsList AND !Cart::getConditionsByType('payment_method')->count()) {
            Cart::updatePaymentMethod($paymentMethodsList->first()->id);
        }

        $template = "frontend.cart.index";

        if (Browser::isMobile()) {
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
        $result = Cart::postProduct(
            $request->get('product_id'), 
            $productCombinationId, 
            $request->get('leading_attribute_id'), 
            $request->get('product_attribute_id'),
            $request->get('amount')
        );

        if($result){
            return response()->json(array(
                'result' => true, 
                'producttotal' => Cart::getContent()->count(),
                'total_inc_tax_number_format' => Cart::getTotalWithTax(),
                'total_ex_tax_number_format' => Cart::getTotalWithoutTax()
            ));
        }
        
        return response()->json(false);
    }

    public function deleteProduct($productId)
    {
        $result = Cart::remove($productId);

        if (Cart::getContent()->count()) {
            return response()->json(array('result' => $result, 'totals' => true, 'producttotal' => Cart::getContent()->count()));
        }
        
        return response()->json(false);        
    }

    public function updateAmountProduct(Request $request, $productId, $amount)
    {
        Cart::updateAmountProduct($productId, $amount, $request->get('leading_attribute_id'), $request->get('product_attribute_id'));

        if (Cart::getContent()->count() AND Cart::get($productId)) {
            $product = Cart::get($productId);
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
        
        if (Browser::isMobile()) {
            $template = "frontend.cart._totals-mobile";
        }

        return view('frontend.cart._totals')->with(array('sendingMethodsList' => $sendingMethodsList));  
    }

    public function updateSendingMethod($sendingMethodId)
    {
        Cart::updateSendingMethod($sendingMethodId);
        return response()->json(array('sending_method' => Cart::getConditionsByType('sending_method')));
    }

    public function updatePaymentMethod($paymentMethodId)
    {
        Cart::updatePaymentMethod($paymentMethodId);
        return response()->json(array('payment_method' => Cart::getConditionsByType('payment_method')));
    }
}