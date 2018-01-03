<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Order;
use Hideyo\Models\OrderProduct;
use Hideyo\Models\OrderAddress;
use Hideyo\Models\OrderSendingMethod;
use Hideyo\Models\OrderPaymentMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Hideyo\Repositories\ClientRepositoryInterface;
use Hideyo\Repositories\OrderAddressRepositoryInterface;
use Hideyo\Repositories\SendingMethodRepositoryInterface;
use Hideyo\Repositories\PaymentMethodRepositoryInterface;
use Hideyo\Repositories\ClientAddressRepositoryInterface;
use DB;
use Carbon\Carbon;
use Auth;
use Cart;
 
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    protected $model;

    public function __construct(
        Order $model,
        OrderProduct $modelOrderProduct,
        ClientRepositoryInterface $client,
        ClientAddressRepositoryInterface $clientAddress,
        OrderAddressRepositoryInterface $orderAddress,
        SendingMethodRepositoryInterface $sendingMethod,
        PaymentMethodRepositoryInterface $paymentMethod
    ) {
        $this->model = $model;
        $this->modelOrderProduct = $modelOrderProduct;
        $this->client = $client;
        $this->orderAddress = $orderAddress;
        $this->clientAddress = $clientAddress;
        $this->paymentMethod = $paymentMethod;
        $this->sendingMethod = $sendingMethod;
    }
  
    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model->fill($attributes);
        $this->model->save();

        if (isset($attributes['categories'])) {
            $this->model->categories()->sync($attributes['categories']);
        }
        
        return $this->model;
    }


    public function createByUserAndShopId(array $attributes, $shopId, $noAccountUser)
    {
        $attributes['shop_id'] = $shopId;
        $attributes['client_id'] = $attributes['user_id'];
        $client  = $this->client->selectOneByShopIdAndId($shopId, $attributes['user_id']);

        $this->model->fill($attributes);
        $this->model->save();

        if (!Cart::getContent()->count()) {
            return false;
        }

        foreach (Cart::getContent()->sortBy('id')  as $product) {

            $quantity = $product->quantity;
            $newProduct = array(
                'product_id' => $product['attributes']['id'],
                'title' => $product['attributes']['title'],
                'original_price_without_tax' => $product['attributes']['price_details']['original_price_ex_tax'],
                'original_price_with_tax' => $product['attributes']['price_details']['original_price_inc_tax'],
                'original_total_price_without_tax' => $quantity * $product['attributes']['price_details']['original_price_ex_tax'],
                'original_total_price_with_tax' => $quantity * $product['attributes']['price_details']['original_price_inc_tax'],
                'price_without_tax' => $product->getOriginalPriceWithoutTaxAndConditions(false),
                'price_with_tax' => $product->getOriginalPriceWithTaxAndConditions(false),
                'total_price_without_tax' => $product->getOriginalPriceWithoutTaxSum(false),
                'total_price_with_tax' => $product->getOriginalPriceWithTaxSum(false),
                'amount' => $quantity,
                'tax_rate' => $product['attributes']['tax_rate'],
                'tax_rate_id' => $product['attributes']['tax_rate_id'],
                'weight' => $product['attributes']['weight'],
                'reference_code' => $product['attributes']['reference_code'],
            );

            if (isset($product['attributes']['product_combination_id'])) {
                $newProduct['product_attribute_id'] = $product['attributes']['product_combination_id'];
                $productCombinationTitleArray = array();

                if (isset($product['attributes']['product_combination_title'])) {
                    $productCombinationTitle = array();

                    foreach ($product['attributes']['product_combination_title'] as $key => $val) {

                        $productCombinationTitle[] = $key.': '.$val;
                    }

                    $newProduct['product_attribute_title'] = implode(', ', $productCombinationTitle);
                
                }
            }

            $newProducts[] = new OrderProduct($newProduct);
        }

        $this->model->products()->saveMany($newProducts);

        if ($client) {
            if ($client->clientDeliveryAddress) {
                $deliveryOrderAddress = new $this->orderAddress(new OrderAddress());
                $deliveryOrderAddress = $deliveryOrderAddress->create($client->clientDeliveryAddress->toArray(), $this->model->id);
            }

            if ($client->clientBillAddress) {
                $billOrderAddress = new $this->orderAddress(new OrderAddress());
                $billOrderAddress = $billOrderAddress->create($client->clientBillAddress->toArray(), $this->model->id);
            }

            $this->model->fill(array('delivery_order_address_id' => $deliveryOrderAddress->id, 'bill_order_address_id' => $billOrderAddress->id));
            $this->model->save();

        } elseif ($noAccountUser) {
            $deliveryOrderAddress = new $this->orderAddress(new OrderAddress());
            if (isset($noAccountUser['delivery'])) {
                $deliveryOrderAddress = $deliveryOrderAddress->create($noAccountUser['delivery'], $this->model->id);
            } else {
                $deliveryOrderAddress = $deliveryOrderAddress->create($noAccountUser, $this->model->id);
            }

            $billOrderAddress = new $this->orderAddress(new OrderAddress());
            $billOrderAddress = $billOrderAddress->create($noAccountUser, $this->model->id);

            $this->model->fill(array('delivery_order_address_id' => $deliveryOrderAddress->id, 'bill_order_address_id' => $billOrderAddress->id));
            $this->model->save();
        }

        if (Cart::getConditionsByType('sending_method')->count()) {

            $attributes = Cart::getConditionsByType('sending_method')->first()->getAttributes();
            $sendingMethod = $this->sendingMethod->find($attributes['data']['id']);
            $price = $sendingMethod->getPriceDetails();
            $sendingMethodArray = $sendingMethod->toArray();
  

            $sendingMethodArray['price_with_tax'] = Cart::getConditionsByType('sending_method')->first()->getAttributes()['data']['price_details']['original_price_inc_tax'];
            $sendingMethodArray['price_without_tax'] = Cart::getConditionsByType('sending_method')->first()->getAttributes()['data']['price_details']['original_price_ex_tax'];
            $sendingMethodArray['tax_rate'] = $price['tax_rate'];
            $sendingMethodArray['sending_method_id'] = $sendingMethod->id;

            $orderSendingMethod = new OrderSendingMethod($sendingMethodArray);
            $this->model->orderSendingMethod()->save($orderSendingMethod);
        }

        if (Cart::getConditionsByType('payment_method')->count()) {

           $attributes = Cart::getConditionsByType('payment_method')->first()->getAttributes();
            $paymentMethod = $this->paymentMethod->find($attributes['data']['id']);
            $price = $paymentMethod->getPriceDetails();

            $paymentMethodArray = $paymentMethod->toArray();

            $paymentMethodArray['price_with_tax'] = Cart::getConditionsByType('payment_method')->first()->getAttributes()['data']['value_inc_tax'];
            $paymentMethodArray['price_without_tax'] = Cart::getConditionsByType('payment_method')->first()->getAttributes()['data']['value_ex_tax'];
            $paymentMethodArray['tax_rate'] = $price['tax_rate'];
            $paymentMethodArray['payment_method_id'] = $paymentMethod->id;
            $orderPaymentMethod = new OrderPaymentMethod($paymentMethodArray);
            $this->model->orderPaymentMethod()->save($orderPaymentMethod);
        }


        if ($this->model->orderPaymentMethod->paymentMethod->order_confirmed_order_status_id) {
            $this->model->fill(array('order_status_id' => $this->model->orderPaymentMethod->paymentMethod->order_confirmed_order_status_id));
            $this->model->save();
        }

        return $this->model;
    }


    public function updateStatus($id, $orderStatusId)
    {
        $this->model = $this->find($id);

        $attributes['order_status_id'] = $orderStatusId;
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }

    public function updateById(array $attributes, $id)
    {
        $this->model = $this->find($id);
        if (auth('hideyobackend')->check()) {
            $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
            $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        }

        return $this->updateEntity($attributes);
    }

    public function selectAllByShopIdAndStatusId($orderStatusId, $startDate = false, $endDate = false, $shopId = false)
    {
        $query = $this->model
        ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
        ->where('order_status_id', '=', $orderStatusId);

        if ($startDate) {
            $dt = Carbon::createFromFormat('d/m/Y', $startDate);
            $query->where('created_at', '>=', $dt->toDateString('Y-m-d'));
        }

        if ($endDate) {
            $dt = Carbon::createFromFormat('d/m/Y', $endDate);
            $query->where('created_at', '<=', $dt->toDateString('Y-m-d'));
        }

        $query->orderBy('created_at', 'ASC');
        return $query->get();
    }

    public function selectAllByAllProductsAndProductCategoryId($productCategoryId)
    {
        return $this->model->select('extra_field.*')->leftJoin('product_category_related_extra_field', 'extra_field.id', '=', 'product_category_related_extra_field.extra_field_id')->where('all_products', '=', 1)->orWhere('product_category_related_extra_field.product_category_id', '=', $productCategoryId)->get();
    }

    public function orderProductsByClientId($clientId, $shopId)
    {

        return $this->modelOrderProduct->with(array('product'))->whereHas('Order', function ($query) use ($clientId, $shopId) {
            $query->where('client_id', '=', $clientId)->where('shop_id', '=', $shopId);
        });
    }

    public function selectAllByCompany()
    {
        return $this->model->leftJoin('shop', 'order.shop_id', '=', 'shop.id')->get();
    }

    public function productsByOrderIds(array $orderIds) 
    {
        $result = DB::table('order_product')
        ->select(DB::raw('DISTINCT(CONCAT_WS(\' - \',order_product.title, IFNULL(order_product.product_attribute_title, \'\'))) as title, order_product.product_attribute_title, order_product.reference_code, order_product.price_with_tax, order_product.price_without_tax,  SUM(order_product.amount) as total_amount'))
        ->whereIn('order_product.order_id', $orderIds)
        ->whereNotNull('order_product.product_attribute_title')
        ->groupBy('order_product.title', 'order_product.product_attribute_title')
        ->get();


        $result2 = DB::table('order_product')
        ->select(DB::raw('DISTINCT(order_product.title) as title, order_product.product_attribute_title, order_product.reference_code, order_product.price_with_tax, order_product.price_without_tax,  SUM(order_product.amount) as total_amount'))
        ->whereIn('order_product.order_id', $orderIds)
        ->whereNull('order_product.product_attribute_title')
        ->groupBy('order_product.title', 'order_product.product_attribute_title')
        ->get();

        $result = array_merge($result, $result2);
        return $result;
    }
}
