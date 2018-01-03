<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Invoice;
use Hideyo\Models\InvoiceRule;
use Hideyo\Models\InvoiceAddress;
use Hideyo\Models\InvoiceSendingMethod;
use Hideyo\Models\InvoicePaymentMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Hideyo\Repositories\OrderRepositoryInterface;
use Hideyo\Repositories\ClientRepositoryInterface;
use Hideyo\Repositories\InvoiceAddressRepositoryInterface;
use Hideyo\Repositories\SendingMethodRepositoryInterface;
use Hideyo\Repositories\PaymentMethodRepositoryInterface;
use Validator;
use Auth;
 
class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{

    protected $model;

    public function __construct(
        Invoice $model,
        OrderRepositoryInterface $order,
        ClientRepositoryInterface $client,
        InvoiceAddressRepositoryInterface $invoiceAddress,
        SendingMethodRepositoryInterface $sendingMethod,
        PaymentMethodRepositoryInterface $paymentMethod
    ) {
        $this->model = $model;
        $this->client = $client;
        $this->order = $order;
        $this->invoiceAddress = $invoiceAddress;
        $this->paymentMethod = $paymentMethod;

        $this->sendingMethod = $sendingMethod;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */
    private function rules($id = false)
    {
        $rules = array(
            'order_id' => 'required|unique:invoice',
        );

        return $rules;
    }

  
    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth()->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = auth()->user()->id;
        $this->model->fill($attributes);
        $this->model->save();

        if (isset($attributes['categories'])) {
            $this->model->categories()->sync($attributes['categories']);
        }
        
        return $this->model;
    }

    public function generateInvoiceFromOrder($orderId)
    {        
        $order = $this->order->find($orderId);

        if ($order->count()) {
            $attributes = $order->toArray();
            $attributes['order_id'] = $order->id;

            $validator = Validator::make($attributes, $this->rules());

            if ($validator->fails()) {
                return $validator;
            }

            $this->model->fill($attributes);
            $this->model->save();
        
            if ($this->model->id) {
                if ($order->products) {
                    foreach ($order->products as $product) {
                        $product = $product->toArray();
                        $product['product_id'] = $product['product_id'];

                        if (isset($product['product_combination_id'])) {
                            $product['product_attribute_id'] = $product['product_combination_id'];
                            $productCombinationTitleArray = array();
                            if (isset($product['product_combination_title']) and is_array($product['product_combination_title'])) {
                                foreach ($product['product_combination_title'] as $key => $val) {
                                    $productCombinationTitle[] = $key.': '.$val;
                                }

                                $product['product_attribute_title'] = implode(', ', $productCombinationTitle);
                            }
                        }

                        $products[] = new InvoiceRule($product);
                    }

                    if ($order->orderSendingMethod) {
                        $invoiceRule = array(
                            'type' => 'sending_cost',
                            'title' => $order->orderSendingMethod->title,
                            'tax_rate_id' =>  $order->orderSendingMethod->tax_rate_id,
                            'tax_rate' =>  $order->orderSendingMethod->tax_rate,
                            'amount' =>  1,
                            'price_with_tax' =>  $order->orderSendingMethod->price_with_tax,
                            'price_without_tax' =>  $order->orderSendingMethod->price_without_tax,
                            'total_price_with_tax' =>  $order->orderSendingMethod->price_with_tax,
                            'total_price_without_tax' =>  $order->orderSendingMethod->price_without_tax,
                        );

                        $products[] = new InvoiceRule($invoiceRule);
                    }

                    if ($order->orderPaymentMethod) {
                        $invoiceRule = array(
                            'type' => 'payment_cost',
                            'title' => $order->orderPaymentMethod->title,
                            'tax_rate_id' =>  $order->orderPaymentMethod->tax_rate_id,
                            'tax_rate' =>  $order->orderPaymentMethod->tax_rate,
                            'amount' =>  1,
                            'price_with_tax' =>  $order->orderPaymentMethod->price_with_tax,
                            'price_without_tax' =>  $order->orderPaymentMethod->price_without_tax,
                            'total_price_with_tax' =>  $order->orderPaymentMethod->price_with_tax,
                            'total_price_without_tax' =>  $order->orderPaymentMethod->price_without_tax,
                        );

                        $products[] = new InvoiceRule($invoiceRule);
                    }

                    $this->model->products()->saveMany($products);
                }

                if ($order->orderBillAddress and $order->orderDeliveryAddress) {
                    $deliveryInvoiceAddress = new InvoiceAddress($order->orderBillAddress->toArray());
       
                    $billInvoiceAddress = new InvoiceAddress($order->orderDeliveryAddress->toArray());

                    $this->model->invoiceAddress()->saveMany(array($deliveryInvoiceAddress, $billInvoiceAddress));
     
                    $this->model->fill(array('delivery_invoice_address_id' => $deliveryInvoiceAddress->id, 'bill_invoice_address_id' => $billInvoiceAddress->id));
                    $this->model->save();
                }
            }

            return $this->model;
        }
    }

    public function updateById(array $attributes, $id)
    {
        $this->model = $this->find($id);
        $attributes['shop_id'] = auth()->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = auth()->user()->id;
        return $this->updateEntity($attributes);
    }

    public function selectAllByAllProductsAndProductCategoryId($productCategoryId)
    {
        return $this->model->select('extra_field.*')->leftJoin('product_category_related_extra_field', 'extra_field.id', '=', 'product_category_related_extra_field.extra_field_id')->where('all_products', '=', 1)->orWhere('product_category_related_extra_field.product_category_id', '=', $productCategoryId)->get();
    }


}
