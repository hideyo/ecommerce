<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductAttribute extends Model
{

    public static $rules = array(
        'product_id' => 'required',
    );

    protected $table = 'product_attribute';

    // Add the 'avatar' attachment to the fillable array so that it's mass-assignable on this model.
    protected $fillable = ['product_id', 'reference_code', 'default_on', 'price', 'commercial_price', 'amount', 'tax_rate_id', 'discount_type', 'discount_value', 'discount_start_date', 'discount_end_date', 'discount_promotion', 'modified_by_user_id'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function setDiscountValueAttribute($value)
    {
        if ($value) {
            $this->attributes['discount_value'] = $value;
        } else {
            $this->attributes['discount_value'] = null;
        }
    }

    public function setDiscountStartDateAttribute($value)
    {
        if ($value) {
            $date = explode('/', $value);

            $value = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
            $this->attributes['discount_start_date'] = $value;
        } else {
            $this->attributes['discount_start_date'] = null;
        }
    }

    public function getDiscountStartDateAttribute($value)
    {
        if ($value) {
            $date = explode('-', $value);
            return $date[2].'/'.$date[1].'/'.$date[0];
        } else {
            return null;
        }
    }

    public function setDiscountEndDateAttribute($value)
    {
        if ($value) {
            $date = explode('/', $value);
            $value = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
            $this->attributes['discount_end_date'] = $value;
        } else {
            $this->attributes['discount_end_date'] = null;
        }
    }

    public function getDiscountEndDateAttribute($value)
    {
        if ($value) {
            $date = explode('-', $value);
            return $date[2].'/'.$date[1].'/'.$date[0];
        } else {
            return null;
        }
    }

    public function combinations()
    {
        return $this->hasMany('App\ProductAttributeCombination');
    }

    public function images()
    {
        return $this->hasMany('App\ProductAttributeImage');
    }


    public function productAttributeCombinations()
    {
        return $this->hasMany('App\ProductAttributeCombination');
    }

    public function setAmountAttribute($value)
    {
        if ($value) {
            $this->attributes['amount'] = (int) $value;
        } else {
            $this->attributes['amount'] = 0;
        }
    }

    public function setPriceAttribute($value)
    {
        if ($value) {
            $this->attributes['price'] = $value;
        } else {
            $this->attributes['price'] = null;
        }
    }

    public function taxRate()
    {
        return $this->belongsTo('App\TaxRate');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }


    public function getPriceDetails()
    {
        
        if ($this->price) {
            $price = $this->price;
        } else {
            $price = $this->product->price;
        }

        if (isset($this->taxRate->rate)) {
            $taxRate = $this->taxRate->rate;
            $price_inc = (($this->taxRate->rate / 100) * $price) + $price;
            $tax_value = $price_inc - $price;
        } else {
            $taxRate = 0;
            $price_inc = 0;
            $tax_value = 0;
        }

        $discount_price_inc = false;
        $discount_price_ex = false;
        $discountTaxRate = 0;
        if ($this->discount_value) {
            if ($this->discount_type == 'amount') {
                $discount_price_inc = $price_inc - $this->discount_value;

                if ($this->shop->wholesale) {
                    $discount_price_ex = $this->price - $this->discount_value;
                } else {
                    $discount_price_ex = $discount_price_inc / 1.21;
                }
            } elseif ($this->discount_type == 'percent') {
                if ($this->shop->wholesale) {
                    $discount = ($this->discount_value / 100) * $this->price;
                    $discount_price_ex = $this->price - $discount;
                } else {
                    $tax = ($this->discount_value / 100) * $price_inc;
                    $discount_price_inc = $price_inc - $tax;
                    $discount_price_ex = $discount_price_inc / 1.21;
                }
            }


            $discountTaxRate = $discount_price_inc - $discount_price_ex;
            $discount_price_inc = $discount_price_inc;
            $discount_price_ex = $discount_price_ex;
        }

        $commercialPrice = null;
        if ($this->commercial_price) {
            $commercialPrice = number_format($this->commercial_price, 2, '.', '');
        }

        return array(
            'orginal_price_ex_tax'  => $price,
            'orginal_price_ex_tax_number_format'  => number_format($price, 2, '.', ''),
            'orginal_price_inc_tax' => $price_inc,
            'orginal_price_inc_tax_number_format' => number_format($price_inc, 2, '.', ''),
            'commercial_price_number_format' => $commercialPrice,
            'tax_rate' => $taxRate,
            'tax_value' => $tax_value,
            'currency' => 'EU',
            'discount_price_inc' => $discount_price_inc,
            'discount_price_inc_number_format' => number_format($discount_price_inc, 2, '.', ''),
            'discount_price_ex' => $discount_price_ex,
            'discount_price_ex_number_format' => number_format($discount_price_ex, 2, '.', ''),
            'discount_tax_value' => $discountTaxRate,
            'discount_value' => $this->discount_value,
            'amount' => $this->amount
        );
    }
}