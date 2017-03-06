<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryHighlightProduct extends Model
{



    protected $table = 'product_category_highlight_product';

    // Add the 'avatar' attachment to the fillable array so that it's mass-assignable on this model.
    protected $fillable = ['product_id', 'product_category_id'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function productCategory()
    {
        return $this->belongsTo('App\ProductCategory', 'product_category_id');
    }
}