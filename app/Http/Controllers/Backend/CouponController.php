<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * CouponController
 *
 * This is the controller for the shop clients
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */


use Hideyo\Ecommerce\Framework\Services\Coupon\Entity\CouponRepository;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\Entity\ProductCategoryRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductRepository;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\Entity\SendingMethodRepository;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\Entity\PaymentMethodRepository;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class CouponController extends Controller
{
    public function __construct(Request $request, SendingMethodRepository $sendingMethod, PaymentMethodRepository $paymentMethod, CouponRepository $coupon, ProductCategoryRepository $productCategory, ProductRepository $product)
    {
        $this->request = $request;
        $this->coupon = $coupon;
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->sendingMethod = $sendingMethod;
        $this->paymentMethod = $paymentMethod;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = $this->coupon->getModel()->select(
                [
                
                $this->coupon->getGroupModel()->getTable().'.title as coupontitle']
            )->where($this->coupon->getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)


            ->with(array('couponGroup'))        ->leftJoin($this->coupon->getGroupModel()->getTable(), $this->coupon->getGroupModel()->getTable().'.id', '=', $this->coupon->getModel()->getTable().'.coupon_group_id');
            
            
            $datatables = Datatables::of($query)

            ->filterColumn('title', function ($query, $keyword) {
                $query->whereRaw("coupon.title like ?", ["%{$keyword}%"]);
            })

            ->filterColumn('grouptitle', function ($query, $keyword) {
                $query->whereRaw("coupon_group.title like ?", ["%{$keyword}%"]);
            })
            ->addColumn('grouptitle', function ($query) {
                return $query->coupontitle;
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax('/admin/coupon/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/coupon/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.coupon.index')->with('coupon', $this->coupon->selectAll());
    }

    public function create()
    {
        return view('backend.coupon.create')->with(array(
            'products'          => $this->product->selectAll()->pluck('title', 'id'),
            'productCategories' => $this->productCategory->selectAll()->pluck('title', 'id'),
            'groups'            => $this->coupon->selectAllGroups()->pluck('title', 'id')->toArray(),
            'sendingMethods'    => $this->sendingMethod->selectAll()->pluck('title', 'id'),
            'paymentMethods'    => $this->paymentMethod->selectAll()->pluck('title', 'id')
        ));
    }

    public function store()
    {
        $result  = $this->coupon->create($this->request->all());
 
        if (isset($result->id)) {
            Notification::success('The coupon was inserted.');
            return redirect()->route('coupon.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function edit($couponId)
    {
        return view('backend.coupon.edit')->with(
            array(
            'coupon' => $this->coupon->find($couponId),
            'products' => $this->product->selectAll()->pluck('title', 'id'),
            'groups' => $this->coupon->selectAllGroups()->pluck('title', 'id')->toArray(),
            'productCategories' => $this->productCategory->selectAll()->pluck('title', 'id'),
            'sendingMethods' => $this->sendingMethod->selectAll()->pluck('title', 'id'),
            'paymentMethods' => $this->paymentMethod->selectAll()->pluck('title', 'id'),
            )
        );
    }

    public function update($couponId)
    {
        $result  = $this->coupon->updateById($this->request->all(), $couponId);

        if (isset($result->id)) {
            Notification::success('The coupon method was updated.');
            return redirect()->route('coupon.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function destroy($couponId)
    {
        $result  = $this->coupon->destroy($couponId);

        if ($result) {
            Notification::success('The coupon was deleted.');
            return redirect()->route('coupon.index');
        }
    }
}
