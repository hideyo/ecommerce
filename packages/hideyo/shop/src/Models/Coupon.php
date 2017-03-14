<?php 

namespace Hideyo\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{

    public static $rules = array(
        'title' => 'required',
    );

    protected $table = 'coupon';

    // Add the 'avatar' attachment to the fillable array so that it's mass-assignable on this model.
    protected $fillable = ['active', 'permanent', 'coupon_group_id', 'title', 'value', 'code', 'type', 'discount_way', 'published_at', 'unpublished_at', 'shop_id', 'modified_by_user_id'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function products()
    {
        return $this->belongsToMany('Hideyo\Shop\Models\Product', 'coupon_product');
    }


    public function couponGroup()
    {
        return $this->belongsTo('Hideyo\Shop\Models\CouponGroup');
    }


    public function productCategories()
    {
        return $this->belongsToMany('Hideyo\Shop\Models\ProductCategory', 'coupon_product_category');
    }

    public function sendingMethods()
    {
        return $this->belongsToMany('Hideyo\Shop\Models\SendingMethod', 'coupon_sending_method');
    }

    public function paymentMethods()
    {
        return $this->belongsToMany('Hideyo\Shop\Models\PaymentMethod', 'coupon_payment_method');
    }

    public function setPublishedAtAttribute($value)
    {
        if ($value) {
            $date = explode('/', $value);
            $value = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
            $this->attributes['published_at'] = $value;
        } else {
            $this->attributes['published_at'] = null;
        }
    }

    public function getPublishedAtAttribute($value)
    {
        if ($value) {
            $date = explode('-', $value);
            return $date[2].'/'.$date[1].'/'.$date[0];
        } else {
            return null;
        }
    }


    public function setUnPublishedAtAttribute($value)
    {
        if ($value) {
            $date = explode('/', $value);
            $value = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
            $this->attributes['unpublished_at'] = $value;
        } else {
            $this->attributes['unpublished_at'] = null;
        }
    }

    public function getUnPublishedAtAttribute($value)
    {
        if ($value) {
            $date = explode('-', $value);
            return $date[2].'/'.$date[1].'/'.$date[0];
        } else {
            return null;
        }
    }


    public function setCouponGroupIdAttribute($value)
    {
        if ($value) {
            $this->attributes['coupon_group_id'] = $value;
        } else {
            $this->attributes['coupon_group_id'] = null;
        }
    }
}